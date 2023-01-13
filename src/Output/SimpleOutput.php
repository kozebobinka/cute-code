<?php declare(strict_types = 1);

namespace CuteCode\Output;

class SimpleOutput implements OutputInterface
{
    public static function writeLine(mixed $line): void
    {
        echo $line . "\n";
    }

    public static function writeArray(array $array): void
    {
        foreach ($array as $line) {
            self::writeLine($line);
        }
    }
}