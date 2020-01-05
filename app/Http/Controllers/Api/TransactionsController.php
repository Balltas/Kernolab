<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\ValidationRules\TransactionsPerTimeIntervalRule;
use App\ValidationRules\MaxBalanceRule;
use App\ValidationRules\CodeRule;
use App\ValidationRules\PendingTransactionExistsRule;
use App\ValidationRules\TransactionExistsRule;
use App\Services\TransactionService;
use App\Transaction;

class TransactionsController extends Controller
{
    public function createTransaction(Request $request, TransactionService $transactionService)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['bail', 'required', 'integer', new TransactionsPerTimeIntervalRule],
            'details' => 'string|required',
            'receiver_account' => 'string|required',
            'receiver_name' => 'string|required',
            'amount' => ['bail', 'required', 'numeric', new MaxBalanceRule($request, $transactionService)],
            'currency' => 'required|string|min:3|max:3',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $response = $transactionService->create($request);
        return response()->json($response->messages(), $response->status());
    }

    public function confirmTransaction(Request $request, TransactionService $transactionService)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => ['required', 'integer', new PendingTransactionExistsRule],
            'code' => ['bail', 'required', 'integer', new CodeRule],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $response = $transactionService->confirm($request);
        return response()->json($response->messages(), $response->status());
    }

    public function getTransaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => ['required', 'integer', new TransactionExistsRule],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        return response()->json(Transaction::find($request->transaction_id), 200);
    }
}