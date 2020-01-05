<?php
namespace App\ValidationRules;

use Illuminate\Contracts\Validation\Rule;
use App\Repositories\TransactionsRepository;

class TransactionExistsRule implements Rule
{
    public function passes($attribute, $value)
    {
        try { 
            return TransactionsRepository::getTransactionById($value);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function message()
    {
        return 'Transaction with this ID doe\'s not exist';
    }
}