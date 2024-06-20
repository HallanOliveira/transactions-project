<?php

namespace Core\UseCases;

use Core\Entities\Notification;
use Core\DTOs\NotificationDTO;
use Core\DTOs\TransactionDTO;
use Core\Enums\NotificationType;
use Core\Ports\PersonRepository;
use Core\Ports\NotificationRepository;
use Core\Ports\TransactionRepository;
use Core\Ports\NotificationProvider;
use Core\Ports\UuidGeneratorProvider;

class SendNotificationTransfer
{
    public function __construct(
        private readonly PersonRepository       $personRepository,
        private readonly TransactionRepository  $transactionRepository,
        private readonly NotificationRepository $notificationRepository,
        private readonly UuidGeneratorProvider  $uuidGeneratorProvider,
        private readonly NotificationProvider   $notificationProvider
    ) {
    }

    public function execute(TransactionDTO $transactionDTO): bool
    {
        $personOrigin = $this->personRepository->get($transactionDTO->person_destination_id);
        $personDest   = $this->personRepository->get($transactionDTO->person_destination_id);
        $transaction  = $this->transactionRepository->get($transactionDTO->id);
        $notification = Notification::create(
            uuidGeneratorProvider: $this->uuidGeneratorProvider,
            person_id: $personOrigin->getId(),
            message: $transaction->getMessageNotifyTransfer($personOrigin, $personDest),
            type: $personOrigin->hasPhone() ? NotificationType::EMAIL_AND_SMS->value : NotificationType::EMAIL->value
        );

        $this->notificationRepository->save($notification);

        $inputDTO = new NotificationDTO(
            id: $notification->getId(),
            person_id: $notification->getPersonId(),
            type: $notification->getTypeToSend($personOrigin),
            message: $notification->getMessage(),
            created_at: $notification->getCreatedAt(),
            is_sent: $notification->getIsSent(),
            sent_at: $notification->getSentAt()
        );

        $success = $this->notificationProvider->send($inputDTO);
        if ($success) {
            $notification->markAsSent();
            $this->notificationRepository->save($notification);
            return true;
        }
        return false;
    }
}
