<?php

declare(strict_types=1);

namespace CuteCode\Binlist;

use CuteCode\Binlist\Exception\BinlistException;
use CuteCode\DTO\TransactionDTO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BinlistClient extends Client implements BinlistClientInterface
{
    public const GET_BIN_URL = 'https://lookup.binlist.net/%d';

    /**
     * @throws BinlistException
     */
    public function updateTransaction(TransactionDTO $transactionDTO): void
    {
        try {
            $response = $this->request('GET', \sprintf(self::GET_BIN_URL, $transactionDTO->binId));
            $content = \json_decode($response->getBody()->getContents(), false, 512, \JSON_THROW_ON_ERROR);
        } catch (GuzzleException $e) {
            throw new BinlistException('Error connecting to binlist', 0, $e);
        } catch (\JsonException $e) {
            throw new BinlistException('Error decoding content', 0, $e);
        } catch (\Throwable $e) {
            throw new BinlistException('Unknown error', 0, $e);
        }

        if (!isset($content->country->alpha2)) {
            throw new BinlistException('Can\'t find country code');
        }

        $transactionDTO->countryCode = $content->country->alpha2;
    }
}
