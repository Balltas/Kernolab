<?php
namespace App\ValidationRules;

use Illuminate\Contracts\Validation\Rule;
use App\Repositories\TransactionsRepository;

class TransactionsPerTimeIntervalRule implements Rule
{
    const LIMIT = 10;

    public function passes($attribute, $value)
    {
        return TransactionsRepository::getLatestCount($value) < self::LIMIT;
    }

    public function message()
    {
        return 'Can\'t create transaction. Transactions per hour exceeds '.self::LIMIT;
    }
}