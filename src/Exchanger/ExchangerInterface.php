<?php declare(strict_types = 1);

namespace CuteCode\Exchanger;

use CuteCode\Exchanger\Exception\ExchangerException;

interface ExchangerInterface
{
    /**
     * @throws ExchangerException
     */
    public function exchange(float $amount, string $from, string $to);
}
