<?php declare(strict_types = 1);

namespace CuteCode\Binlist;

use CuteCode\Binlist\DTO\BinDTO;

interface BinlistClientInterface
{
    public function getBin(int $binId): BinDTO;
}