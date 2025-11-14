<?php

namespace App\Services;

use App\Events\ConversationMessageSent;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ChatService
{
    public function listConversationsForUser(int $userId): Collection
    {
        return Conversation::query()
            ->whereHas('participants', fn ($q) => $q->where('user_id', $userId))
            ->with(['participants.user', 'messages' => fn ($q) => $q->latest()->limit(1)])
            ->withCount(['messages as unread_count' => fn ($q) => $q->where('destinataire_id', $userId)->where('lu', false)])
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn (Conversation $conversation) => $this->formatConversation($conversation, $userId));
    }

    public function getOrCreateOneToOne(int $userIdA, int $userIdB): Conversation
    {
        if ($userIdA === $userIdB) {
            throw new \InvalidArgumentException('Impossible de créer une conversation avec soi-même.');
        }

        $conversation = Conversation::query()
            ->where('type', Conversation::TYPE_ONE_TO_ONE)
            ->whereHas('participants', fn ($q) => $q->where('user_id', $userIdA))
            ->whereHas('participants', fn ($q) => $q->where('user_id', $userIdB))
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'type' => Conversation::TYPE_ONE_TO_ONE,
            ]);

            $conversation->addParticipant($userIdA);
            $conversation->addParticipant($userIdB);
        }

        return $conversation->fresh(['participants.user']);
    }

    public function ensureParticipant(Conversation $conversation, int $userId): void
    {
        if (!$conversation->participants()->where('user_id', $userId)->exists()) {
            abort(403, 'Accès à la conversation refusé.');
        }
    }

    public function conversationMessages(Conversation $conversation, int $userId, int $perPage = 50): Paginator
    {
        return $conversation->messages()
            ->with(['expediteur:id,prenom,name', 'destinataire:id,prenom,name'])
            ->orderBy('created_at')
            ->paginate($perPage)
            ->through(fn (Message $message) => $this->formatMessage($message, $userId));
    }

    public function sendMessage(Conversation $conversation, int $senderId, string $content, string $type = 'text'): array
    {
        $conversation->loadMissing(['participants']);
        $recipient = $conversation->otherParticipant($senderId);

        if (!$recipient) {
            throw new \RuntimeException('Destinataire introuvable pour cette conversation.');
        }

        $message = $conversation->messages()->create([
            'expediteur_id' => $senderId,
            'destinataire_id' => $recipient->id,
            'type' => $type,
            'contenu' => $content,
            'lu' => false,
        ]);

        $conversation->touch();

        broadcast(new ConversationMessageSent($conversation->id, $this->formatMessage($message->fresh(['expediteur']), $senderId)))->toOthers();

        return $this->formatMessage($message, $senderId);
    }

    public function markConversationAsRead(Conversation $conversation, int $userId): void
    {
        $conversation->participants()
            ->where('user_id', $userId)
            ->update(['last_read_at' => Carbon::now()]);

        $conversation->messages()
            ->where('destinataire_id', $userId)
            ->where('lu', false)
            ->update([
                'lu' => true,
                'lu_at' => Carbon::now(),
            ]);
    }

    public function formatConversation(Conversation $conversation, int $userId): array
    {
        $conversation->loadMissing(['participants.user', 'messages' => fn ($q) => $q->latest()->limit(1)]);

        $otherUser = $conversation->otherParticipant($userId);
        $lastMessage = $conversation->messages->first();

        return [
            'id' => $conversation->id,
            'type' => $conversation->type,
            'subject' => $conversation->subject,
            'other_user' => $otherUser ? [
                'id' => $otherUser->id,
                'name' => $otherUser->nom_complet,
                'initials' => mb_substr($otherUser->prenom, 0, 1).mb_substr($otherUser->name, 0, 1),
                'role' => $otherUser->role,
                'role_label' => match ($otherUser->role) {
                    'SECRETAIRE' => 'Secrétaire',
                    'PATIENT' => 'Patient',
                    'PRATICIEN' => 'Praticien',
                    default => 'Utilisateur',
                },
            ] : null,
            'last_message' => $lastMessage ? $this->formatMessage($lastMessage, $userId) : null,
            'unread_count' => $conversation->unread_count ?? $conversation->messages()
                ->where('destinataire_id', $userId)
                ->where('lu', false)
                ->count(),
            'updated_at' => $conversation->updated_at?->toIso8601String(),
        ];
    }

    public function formatMessage(Message $message, int $currentUserId): array
    {
        return [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_id' => $message->expediteur_id,
            'recipient_id' => $message->destinataire_id,
            'type' => $message->type,
            'content' => $message->contenu,
            'is_mine' => $message->expediteur_id === $currentUserId,
            'read' => $message->lu,
            'created_at' => $message->created_at?->toIso8601String(),
            'time_for_humans' => $message->created_at?->format('d/m/Y H:i'),
        ];
    }
}
