<?php declare(strict_types = 1);

namespace CuteCode\Exchanger\Exception;

class ExchangerException extends \Exception
{
    public const CODE_UNKNOWN_ERROR = 1;
    public const CODE_CLIENT_ERROR = 2;
    public const CODE_DECODE_CONTENT_ERROR = 3;
    public const CODE_WRONG_CURRENCY_FROM = 4;
    public const CODE_WRONG_CURRENCY_TO = 5;
    public const CODE_WRONG_RATE = 6;
}