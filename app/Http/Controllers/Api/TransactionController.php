<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\TranferApiRequest;
use Core\DTOs\TransactionDTO;
use Core\Enums\TransactionTypes;
use Core\UseCases\TransferBetweenUsers;
use Exception;

class TransactionController extends BaseApiController
{
    /**
     * @param TranferApiRequest $request
     * @param TransferBetweenUsers $transferBetweenUsers
     * @return \Illuminate\Http\JsonResponse
     */
    public function transfer(TranferApiRequest $request, TransferBetweenUsers $transferBetweenUsers)
    {
        try {
            $payload = $request->validated();
            $input   = new TransactionDTO(
                $payload['payer'],
                $payload['payee'] ?? null,
                TransactionTypes::TRANSFER->value,
                $payload['value']
            );
            $output = $transferBetweenUsers->execute($input);
            return $this->successResponse('Transaferência realizada com sucesso', $output->toArray());
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }
}