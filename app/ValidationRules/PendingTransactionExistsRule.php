<?php
namespace App\ValidationRules;

use Illuminate\Contracts\Validation\Rule;
use App\Repositories\TransactionsRepository;

class PendingTransactionExistsRule implements Rule
{
    public function passes($attribute, $value)
    {
        try { 
            return TransactionsRepository::getPendingTransactionById($value);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function message()
    {
        return 'Pending transaction with this ID doe\'s not exist';
    }
}