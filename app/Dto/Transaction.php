<?php

namespace App\Dto;

class Transaction
{
    public function __construct(
        public float  $amount,
        public string $currency
    )
    {
    }
}
