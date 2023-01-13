<?php

declare(strict_types=1);

namespace CuteCode\Tests\Binlist;

use CuteCode\Binlist\BinlistClient;
use CuteCode\Binlist\Exception\BinlistException;
use CuteCode\DTO\TransactionDTO;
use CuteCode\Tests\AbstractClientTest;

class BinListClientTestCase extends AbstractClientTest
{
    /**
     * @throws BinlistException
     */
    public function testUpdateTransaction(): void
    {
        $binlistClient = new BinlistClient($this->getClientWithResponseFromFile('binlist.json'));

        $transaction = new TransactionDTO(12345, 123.4567, 'EUR');
        $binlistClient->updateTransaction($transaction);

        self::assertEquals('GB', $transaction->countryCode);
    }
}
