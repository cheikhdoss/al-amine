<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'subject',
    ];

    public const TYPE_ONE_TO_ONE = 'one_to_one';

    public function participants()
    {
        return $this->hasMany(ConversationParticipant::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->withPivot(['role', 'last_read_at'])
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function addParticipant(int $userId, ?string $role = null): ConversationParticipant
    {
        return $this->participants()->firstOrCreate([
            'user_id' => $userId,
        ], [
            'role' => $role,
        ]);
    }

    public function otherParticipant(int $userId): ?User
    {
        return $this->users()->where('users.id', '<>', $userId)->first();
    }

    public function scopeBetweenUsers($query, int $userA, int $userB)
    {
        return $query->whereHas('participants', function ($q) use ($userA) {
            $q->where('user_id', $userA);
        })->whereHas('participants', function ($q) use ($userB) {
            $q->where('user_id', $userB);
        });
    }
}
