<?php declare(strict_types = 1);

namespace CuteCode\Exchanger;


use CuteCode\Exchanger\Client\ExchangerClientInterface;
use CuteCode\Exchanger\DTO\RatesDTO;
use CuteCode\Exchanger\Exception\ExchangerException;

class ApilayerExchanger implements ExchangerInterface
{
    private ?RatesDTO $ratesDTO = null;

    public function __construct(private readonly ExchangerClientInterface $client)
    {
    }

    /**
     * @throws ExchangerException
     */
    public function exchange(float $amount, string $from, string $to): float
    {
        if ($this->ratesDTO === null) {
            $this->ratesDTO = $this->client->getRates();
        }

        $currencyFrom = \strtoupper($from);
        $currencyTo = \strtoupper($to);

        if ($currencyFrom === $currencyTo) {
            return $amount;
        }

        if ($currencyTo !== $this->ratesDTO->base) {
            throw new ExchangerException(\sprintf('Wrong currency to: %s', $currencyTo), ExchangerException::CODE_WRONG_CURRENCY_TO);
        }

        if (!isset($this->ratesDTO->rates[$currencyFrom])) {
            throw new ExchangerException(\sprintf('Wrong currency from: %s', $currencyFrom), ExchangerException::CODE_WRONG_CURRENCY_FROM);
        }

        if ($this->ratesDTO->rates[$currencyFrom] <= 0) {
            throw new ExchangerException(\sprintf('Wrong rate for currency %s: %f', $currencyFrom, $this->ratesDTO->rates[$currencyFrom]), ExchangerException::CODE_WRONG_RATE);
        }

        return $amount / $this->ratesDTO->rates[$currencyFrom];
    }
}