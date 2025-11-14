<?php

use App\Models\Conversation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'conversation_id')) {
                $table->foreignId('conversation_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            }

            if (!Schema::hasColumn('messages', 'type')) {
                $table->string('type')->default('text')->after('rendez_vous_id');
            }
        });

        DB::table('messages')->whereNull('type')->update(['type' => 'text']);

        $messagePairs = DB::table('messages')
            ->select('expediteur_id', 'destinataire_id')
            ->distinct()
            ->get()
            ->map(function ($row) {
                $participants = [$row->expediteur_id, $row->destinataire_id];
                sort($participants);

                return [
                    'key' => implode('-', $participants),
                    'participants' => $participants,
                ];
            })
            ->unique('key');

        foreach ($messagePairs as $pair) {
            [$userA, $userB] = $pair['participants'];

            if ($userA === $userB) {
                continue;
            }

            $conversationId = DB::table('conversations')->insertGetId([
                'type' => Conversation::TYPE_ONE_TO_ONE,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('conversation_participants')->insert([
                [
                    'conversation_id' => $conversationId,
                    'user_id' => $userA,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'conversation_id' => $conversationId,
                    'user_id' => $userB,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            DB::table('messages')
                ->whereNull('conversation_id')
                ->where(function ($query) use ($userA, $userB) {
                    $query->where(function ($sub) use ($userA, $userB) {
                        $sub->where('expediteur_id', $userA)
                            ->where('destinataire_id', $userB);
                    })->orWhere(function ($sub) use ($userA, $userB) {
                        $sub->where('expediteur_id', $userB)
                            ->where('destinataire_id', $userA);
                    });
                })
                ->update([
                    'conversation_id' => $conversationId,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'conversation_id')) {
                $table->dropForeign(['conversation_id']);
                $table->dropColumn('conversation_id');
            }

            if (Schema::hasColumn('messages', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
