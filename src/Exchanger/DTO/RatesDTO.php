<?php

declare(strict_types=1);

namespace CuteCode\Exchanger\DTO;

class RatesDTO
{
    /**
     * @param array<string, float> $rates
     */
    public function __construct(public array $rates, public ?string $base = null)
    {
    }
}
