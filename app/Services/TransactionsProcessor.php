<?php

namespace App\Services;

use App\Services\Contracts\ProcessorContract;
use App\Services\TransactionProcessors\Supermoney;
use App\Services\TransactionProcessors\Megacash;
use App\Transaction;

class TransactionsProcessor
{
    public function resolveObject(Transaction $model): ProcessorContract
    {
        if (strtolower($model->currency) == 'eur') {
            return new Megacash($model);
        }

        return new Supermoney($model);
    }
}