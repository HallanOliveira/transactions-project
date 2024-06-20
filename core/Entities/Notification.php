<?php

namespace Core\Entities;

use Core\Enums\NotificationType;
use Core\Ports\UuidGeneratorProvider;

class Notification
{
    public function __construct(
        private string  $id,
        private int     $person_id,
        private ?string $created_at = null,
        private ?int    $type       = null,
        private ?string $message    = null,
        private ?bool   $is_sent    = null,
        private ?string $sent_at    = null
    ) {
    }

    public static function create(
        UuidGeneratorProvider $uuidGeneratorProvider,
        int                   $person_id,
        string                $message,
        int                   $type = null
    ): Notification {
        $id        = $uuidGeneratorProvider->generate();
        $createdAt = date('Y-m-d H:i:s');
        return new Notification(
            id: $id,
            message: $message,
            person_id: $person_id,
            type: $type,
            created_at: $createdAt
        );
    }

    public function getTypeToSend(Person $person): int
    {
        if (empty($this->type)) {
            $type = empty($person->getPhone())
                ? NotificationType::EMAIL->value
                : NotificationType::EMAIL_AND_SMS->value;
            $this->type = $type;
        }
        return $this->type;
    }

    public function markAsSent(): void
    {
        if (! $this->is_sent) {
            $this->is_sent = true;
            $this->sent_at = date('Y-m-d H:i:s');
        }
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPersonId(): ?int
    {
        return $this->person_id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getIsSent(): ?bool
    {
        return $this->is_sent ?? false;
    }

    public function getSentAt(): ?string
    {
        return $this->sent_at;
    }
}
