<?php

namespace App\Services;

use App\Repositories\TransactionsRepository;
use Illuminate\Http\Request;
use App\Responses\JsonResponse;

class TransactionService
{
    //sum that lower percentage is applyed if exceeds
    const SUM_EXCEEDS = 100;

    //percentages aplyed in both cases
    const PERCENTAGE_EXCEEDS = 5;
    const PERCENTAGE_NOT_EXCEEDS = 10;

    public function getFeePercentage(int $userId): int
    {
        if (TransactionsRepository::getAmountWithoutFees($userId) > self::SUM_EXCEEDS) {
            return self::PERCENTAGE_EXCEEDS;
        }

        return self::PERCENTAGE_NOT_EXCEEDS;
    }

    public function getFeeAmount(int $userId, float $amount): float
    {
        return $amount * $this->getFeePercentage($userId)/100;
    }

    public function getTotalAmount(int $userId, float $amount): float
    {
        return $amount + $this->getFeeAmount($userId, $amount);
    }

    public function create(Request $request): JsonResponse
    {
        return TransactionsRepository::create($request, $this);
    }

    public function confirm(Request $request): JsonResponse
    {
        return TransactionsRepository::confirm($request);
    }
}