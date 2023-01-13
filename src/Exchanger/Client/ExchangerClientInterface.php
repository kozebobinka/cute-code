<?php

declare(strict_types=1);

namespace CuteCode\Exchanger\Client;

use CuteCode\Exchanger\DTO\RatesDTO;

interface ExchangerClientInterface
{
    public function getRates(): RatesDTO;
}
