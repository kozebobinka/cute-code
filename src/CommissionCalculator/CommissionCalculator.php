<?php declare(strict_types = 1);

namespace CuteCode\CommissionCalculator;

class CommissionCalculator implements CommissionCalculatorInterface
{
    private const EU_COMMISSION_COEFFICIENT = 0.01;
    private const OTHERS_COMMISSION_COEFFICIENT = 0.02;
    private const EU_COUNTRIES = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    public function calculate(float $amount, string $countryCode): float
    {
        $coefficient = (\in_array($countryCode, self::EU_COUNTRIES))
            ? self::EU_COMMISSION_COEFFICIENT
            : self::OTHERS_COMMISSION_COEFFICIENT;

        return \ceil($amount * $coefficient * 100) / 100;
    }
}
