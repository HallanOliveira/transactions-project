<?php

namespace App\Repositories;

use Core\Ports\NotificationRepository as NotificationRepositoryInterface;
use Core\Entities\Notification as EntityNotification;
use App\Models\Notification;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function get(string $id): ?EntityNotification
    {
        $notification = Notification::find($id);
        if (empty($notification)) {
            return null;
        }
        return new EntityNotification(
            id: $notification->id,
            person_id: $notification->person_id,
            created_at: $notification->created_at,
            type: $notification->type,
            message: $notification->message,
            is_sent: $notification->is_sent,
            sent_at: $notification->sent_at
        );
    }

    public function save(EntityNotification $notification): ?bool
    {
        return Notification::query()->upsert([
            'id'         => $notification->getId(),
            'person_id'  => $notification->getPersonId(),
            'type'       => $notification->getType(),
            'message'    => $notification->getMessage(),
            'is_sent'    => $notification->getIsSent(),
            'sent_at'    => $notification->getSentAt(),
            'created_at' => $notification->getCreatedAt()
        ],'id');
    }
}
