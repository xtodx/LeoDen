<?php

namespace App\Dto;

class Request
{
    public function __construct(
        public float  $amount,
        public string $currency
    )
    {
    }
}
