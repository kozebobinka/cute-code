<?php

declare(strict_types=1);

namespace CuteCode\Tests\Exchanger;

use CuteCode\Exchanger\ApilayerExchanger;
use CuteCode\Exchanger\Client\ApiLayerClient;
use CuteCode\Exchanger\Exception\ExchangerException;
use CuteCode\Tests\AbstractClientTest;

class ApilayerExchangerTestCase extends AbstractClientTest
{
    private ApilayerExchanger $apilayerExchanger;

    public function setUp(): void
    {
        $apiLayerClient = new ApiLayerClient($this->getClientWithResponseFromFile('apilayer.json'), '');
        $this->apilayerExchanger = new ApilayerExchanger($apiLayerClient);

        parent::setUp();
    }

    /**
     * @dataProvider exchangeDataProvider
     * @throws ExchangerException
     */
    public function testExchange(float $amount, string $from, string $to, float $result): void
    {
        self::assertEquals($result, $this->apilayerExchanger->exchange($amount, $from, $to));
    }

    /**
     * @dataProvider wrongCurrencyExchangeDataProvider
     */
    public function testWrongCurrencyExchange(float $amount, string $from, string $to): void
    {
        $this->expectException(ExchangerException::class);

        $this->apilayerExchanger->exchange($amount, $from, $to);
    }

    /**
     * @return array<string, mixed>
     */
    public function exchangeDataProvider(): array
    {
        return [
            'usd' => [10, 'USD', 'EUR', 9.233107337642732],
            'eur' => [20, 'EUR', 'EUR', 20],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function wrongCurrencyExchangeDataProvider(): array
    {
        return [
            'wrong from' => [10, 'QQQ', 'EUR'],
            'wrong to' => [20, 'EUR', 'USD'],
        ];
    }
}
