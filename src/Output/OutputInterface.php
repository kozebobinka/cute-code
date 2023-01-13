<?php declare(strict_types = 1);

namespace CuteCode\Output;

interface OutputInterface
{
    public static function writeLine(mixed $line): void;
    public static function writeArray(array $array): void;
}