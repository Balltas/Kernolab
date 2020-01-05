<?php
namespace App\ValidationRules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use App\Services\TransactionService;
use App\Repositories\TransactionsRepository;

class MaxBalanceRule implements Rule
{
    const LIMIT = 1000;

    private $request;
    private $transactionService;

    public function __construct(Request $request, TransactionService $transactionService)
    {
        $this->request = $request;
        $this->transactionService = $transactionService;
    }

    public function passes($attribute, $value)
    {
        $currentTransactionAmount = $this->transactionService->getTotalAmount(
            $this->request->user_id,
            $this->request->amount
        );
        
        $totalAmountByCurrency = TransactionsRepository::getTotalAmount(
            $this->request->user_id,
            $this->request->currency
        );

        return ($currentTransactionAmount + $totalAmountByCurrency) <= self::LIMIT;
    }

    public function message()
    {
        return 'Can\'t create transaction. Maximum amount per currency exceeds '.self::LIMIT;
    }
}