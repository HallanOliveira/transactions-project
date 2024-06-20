<?php

namespace Tests\Unit;

use App\Adapters\Gateways\NotificationGateway;
use App\Adapters\Gateways\TransactionAuthorizerGateway;
use App\Repositories\PersonRepository;
use App\Repositories\TransactionRepository;
use Tests\TestCase;
use Core\DTOs\TransactionDTO;
use Core\Enums\PersonDocumentType;
use Core\UseCases\SendNotificationTransfer;
use Core\UseCases\TransferBetweenUsers;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class NotificationTest extends TestCase
{
    use DatabaseTransactions;

    protected TransferBetweenUsers     $useCaseTransfer;
    protected TransactionRepository    $transactionRepository;
    protected PersonRepository         $personRepository;

    public function setUp(): void
    {
        parent::setUp();
        $transactionAuthorizerMock = $this->createMock(TransactionAuthorizerGateway::class);
        $transactionAuthorizerMock->method('execute')->willReturn(true);
        $this->app->instance(TransactionAuthorizerGateway::class, $transactionAuthorizerMock);

        $this->useCaseTransfer       = app(TransferBetweenUsers::class);
        $this->transactionRepository = app(TransactionRepository::class);
        $this->personRepository      = app(PersonRepository::class);
    }

    /**
     * @test
     */
    public function test_send_notification(): void
    {
        $notificationMock = $this->createMock(NotificationGateway::class);
        $notificationMock->method('send')->willReturn(true);
        $this->app->instance(NotificationGateway::class, $notificationMock);

        $useCaseNotification = app(SendNotificationTransfer::class);
        $idOrigin            = 2;
        $idDest              = 3;
        $personEntity        = $this->personRepository->get($idOrigin);
        $personEntity->changeDocumentType(PersonDocumentType::CPF);
        $this->personRepository->save($personEntity);

        $input = new TransactionDTO(
            person_origin_id: $idOrigin,
            person_destination_id: $idDest,
            type: 1,
            amount: 10
        );
        $output  = $this->useCaseTransfer->execute($input);
        $success = $useCaseNotification->execute($output);
        $this->assertTrue($success);
    }

        /**
     * @test
     */
    public function test_send_notification_failed(): void
    {
        $notificationMock = $this->createMock(NotificationGateway::class);
        $notificationMock->method('send')->willReturn(false);
        $this->app->instance(NotificationGateway::class, $notificationMock);

        $useCaseNotification = app(SendNotificationTransfer::class);
        $idOrigin            = 2;
        $idDest              = 3;
        $personEntity        = $this->personRepository->get($idOrigin);
        $personEntity->changeDocumentType(PersonDocumentType::CPF);
        $this->personRepository->save($personEntity);

        $input = new TransactionDTO(
            person_origin_id: $idOrigin,
            person_destination_id: $idDest,
            type: 1,
            amount: 10
        );
        $output  = $this->useCaseTransfer->execute($input);
        $success = $useCaseNotification->execute($output);
        $this->assertFalse($success);
    }
}
