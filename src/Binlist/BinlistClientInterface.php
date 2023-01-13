<?php

declare(strict_types=1);

namespace CuteCode\Binlist;

use CuteCode\Binlist\Exception\BinlistException;
use CuteCode\DTO\TransactionDTO;

interface BinlistClientInterface
{
    /**
     * @throws BinlistException
     */
    public function updateTransaction(TransactionDTO $transactionDTO): void;
}
