<?php

declare(strict_types=1);

namespace CuteCode\CommissionCalculator;

interface CommissionCalculatorInterface
{
    public function calculate(float $amount, string $countryCode): float;
}
