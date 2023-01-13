<?php

declare(strict_types=1);

namespace CuteCode\Tests\CommissionCalculator;

use CuteCode\CommissionCalculator\CommissionCalculator;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTestCase extends TestCase
{
    /**
     * @dataProvider calculateDataProvider
     */
    public function testCalculate(float $amount, string $countryCode, float $result): void
    {
        $commissionCalculator = new CommissionCalculator();

        self::assertEquals($result, $commissionCalculator->calculate($amount, $countryCode));
    }

    /**
     * @return array<string, mixed>
     */
    public function calculateDataProvider(): array
    {
        return [
            'eu' => [100, 'AT', 1],
            'not eu' => [100, 'UA', 2],
            'test ceil' => [101.11, 'UA', 2.03],
        ];
    }
}
