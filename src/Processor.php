<?php declare(strict_types = 1);

namespace CuteCode;

use CuteCode\Binlist\BinlistClientInterface;
use CuteCode\Binlist\Exception\BinlistException;
use CuteCode\CommissionCalculator\CommissionCalculatorInterface;
use CuteCode\Exception\ProcessorException;
use CuteCode\Exchanger\Exception\ExchangerException;
use CuteCode\Exchanger\ExchangerInterface;

class Processor
{
    private const DEFAULT_CURRENCY = 'EUR';

    public function __construct(
        private readonly ExchangerInterface $exchanger,
        private readonly BinlistClientInterface $binlistClient,
        private readonly CommissionCalculatorInterface $commissionCalculator,
    )
    {
    }

    /**
     * @throws ProcessorException
     */
    public function process(string $filename): void
    {
        foreach (explode("\n", file_get_contents($filename)) as $row) {

            if (empty($row)) break;
            $p = explode(",", $row);
            $p2 = explode(':', $p[0]);
            $value[0] = trim($p2[1], '"');
            $p2 = explode(':', $p[1]);
            $value[1] = trim($p2[1], '"');
            $p2 = explode(':', $p[2]);
            $value[2] = trim($p2[1], '"}');

            try {
                $bin = $this->binlistClient->getBin((int)$value[0]);
            } catch (BinlistException $e) {
                throw new ProcessorException(\sprintf('Wrong bin ID: %d', (int) $value[0]), 0, $e);
            }

            try {
                $amountFixed = $this->exchanger->exchange(\floatval($value[1]), $value[2], self::DEFAULT_CURRENCY);
            } catch (ExchangerException $e) {
                throw new ProcessorException(\sprintf('Can\'t exchange from %s to %s', $value[2], self::DEFAULT_CURRENCY), 0, $e);
            }

            echo $this->commissionCalculator->calculate($amountFixed, $bin->countryCode);
            print "\n";
        }
    }
}