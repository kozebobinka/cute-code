<?php

declare(strict_types=1);

namespace CuteCode\Tests\Exchanger\Client;

use CuteCode\Exchanger\Client\ApiLayerClient;
use CuteCode\Exchanger\Exception\ExchangerException;
use CuteCode\Tests\AbstractClientTest;

class ApilayerClientTestCase extends AbstractClientTest
{
    /**
     * @throws ExchangerException
     */
    public function testGetRates(): void
    {
        $apiLayerClient = new ApiLayerClient($this->getClientWithResponseFromFile('apilayer.json'), '');

        $rates = $apiLayerClient->getRates();

        self::assertEquals('EUR', $rates->base);
        self::assertCount(3, $rates->rates);
    }
}
