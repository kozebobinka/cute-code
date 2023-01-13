<?php declare(strict_types = 1);

namespace CuteCode\Binlist;

use CuteCode\Binlist\DTO\BinDTO;
use CuteCode\Binlist\Exception\BinlistException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BinlistClient extends Client
{
    public const GET_BIN_URL = 'https://lookup.binlist.net/%d';

    /**
     * @throws BinlistException
     */
    public function getBin(int $binId): BinDTO
    {
        try {
            $response = $this->request('GET', \sprintf(self::GET_BIN_URL, $binId));
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

        return new BinDTO($binId, $content->country->alpha2);
    }
}