<?php

namespace App\Services;

use App\Dto\Request;
use App\Dto\Transaction;

class RequestMoneyValidator
{
    /**
     * Validates if the request matches the transaction within the accepted deviation.
     *
     * @param Request $request
     * @param Transaction $transaction
     * @return bool
     */
    public function validate(Request $request, Transaction $transaction): bool
    {
        // Check for currency equivalence (case-insensitive)
        if (strtolower($request->currency) !== strtolower($transaction->currency)) {
            return false;
        }

        $deviation = config('services.transaction_validation.deviation');

        // Calculate the deviation limit
        $lowerLimit = $request->amount * (1 - $deviation / 100);
        $upperLimit = $request->amount * (1 + $deviation / 100);

        // Check if the transaction amount falls within the deviation limits
        return $transaction->amount >= $lowerLimit && $transaction->amount <= $upperLimit;
    }
}
