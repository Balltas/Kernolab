<?php

namespace App\Repositories;

use DB;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\TransactionService;
use App\Responses\JsonResponse;

class TransactionsRepository
{
    const LIMIT = 1;

    public static function getLatestCount(int $userId): int
    {
        return Transaction::select(DB::raw('ifnull(count(user_id), 0) as transactions_count'))
            ->where('user_id', $userId)
            ->where('created_at', '>', Carbon::now()->subHours(self::LIMIT))
            ->first()
            ->transactions_count;
    }

    public static function getAmountWithoutFees(int $userId): float
    {
        return Transaction::select(DB::raw('ifnull(sum(amount), 0) as sum_amount'))
            ->where('user_id', $userId)
            ->first()
            ->sum_amount;
    }

    public static function getTotalAmount(int $userId, string $currency): float
    {
        return Transaction::select(DB::raw('ifnull(sum(total), 0) as sum_amount'))
            ->where('user_id', $userId)
            ->where('currency', $currency)
            ->first()
            ->sum_amount;
    }

    public static function getPendingTransactionById(int $transactionId)
    {
        return Transaction::where('status', 'pending')->findOrFail($transactionId);
    }

    public static function getTransactionById(int $transactionId)
    {
        return Transaction::findOrFail($transactionId);
    }

    public static function create(Request $request, TransactionService $ts): JsonResponse
    {
        try {
            $transaction = new Transaction();
            $transaction->user_id = $request->user_id;
            $transaction->details = $request->details;
            $transaction->receiver_account = $request->receiver_account;
            $transaction->receiver_name = $request->receiver_name;
            $transaction->amount = $request->amount;
            $transaction->fee_percentage = $ts->getFeePercentage($request->user_id);
            $transaction->fee_amount = $ts->getFeeAmount($request->user_id, $request->amount);
            $transaction->total = $ts->getTotalAmount($request->user_id, $request->amount);
            $transaction->currency = $request->currency;

            $transaction->save();

            return new JsonResponse([
                'message' => 'Transaction saved',
                'data' => $transaction
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'Transaction failed while inserting to database',
            ], 500);
        }
    }

    public static function confirm(Request $request): JsonResponse
    {
        try {
            $transaction = Transaction::find($request->transaction_id);
            $transaction->status = 'processing';
            $transaction->save();

            return new JsonResponse([
                'message' => 'Transaction confirmed',
                'data' => $transaction
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'process failed while updating to database',
            ], 500);
        }
    }
}