<?php

declare(strict_types=1);

namespace CuteCode\DTO;

class TransactionDTO
{
    public function __construct(
        public int $binId,
        public float $amount,
        public string $currency,
        public ?string $countryCode = null,
    ) {
    }
}
