<?php declare(strict_types = 1);

namespace CuteCode\Binlist\DTO;

class BinDTO
{
    public function __construct(public int $id, public string $countryCode)
    {
    }
}