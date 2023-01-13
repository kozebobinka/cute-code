<?php

declare(strict_types=1);

namespace CuteCode\Exchanger\Client;

use CuteCode\Exchanger\DTO\RatesDTO;
use CuteCode\Exchanger\Exception\ExchangerException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiLayerClient implements ExchangerClientInterface
{
    private const LATEST_URL = 'https://api.apilayer.com/exchangerates_data/latest';
    private const APIKEY_KEY = 'apikey';

    public function __construct(private readonly Client $client, private readonly string $apikey)
    {
    }

    /**
     * @throws ExchangerException
     */
    public function getRates(): RatesDTO
    {
        try {
            $response = $this->client->request('GET', self::LATEST_URL, ['headers' => [self::APIKEY_KEY => $this->apikey]]);
            $content = \json_decode($response->getBody()->getContents(), true, 512, \JSON_THROW_ON_ERROR);
        } catch (GuzzleException $e) {
            throw new ExchangerException('Error connecting to apilayer', ExchangerException::CODE_CLIENT_ERROR, $e);
        } catch (\JsonException $e) {
            throw new ExchangerException('Error decoding content', ExchangerException::CODE_DECODE_CONTENT_ERROR, $e);
        } catch (\Throwable $e) {
            throw new ExchangerException('Unknown error', ExchangerException::CODE_UNKNOWN_ERROR, $e);
        }

        if (!isset($content['rates']) || !\is_array($content['rates'])) {
            throw new ExchangerException('Can\'t find rates', ExchangerException::CODE_DECODE_CONTENT_ERROR);
        }

        if (!isset($content['base']) || !\is_string($content['base'])) {
            throw new ExchangerException('Can\'t find base', ExchangerException::CODE_DECODE_CONTENT_ERROR);
        }

        return new RatesDTO($content['rates'], $content['base']);
    }
}
