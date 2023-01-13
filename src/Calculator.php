<?php declare(strict_types = 1);

namespace CuteCode;

use CuteCode\Binlist\BinlistClient;
use CuteCode\Binlist\Exception\BinlistException;
use CuteCode\Exception\CalculatorException;
use CuteCode\Exchanger\Exception\ExchangerException;
use CuteCode\Exchanger\ExchangerInterface;

class Calculator
{
    private const DEFAULT_CURRENCY = 'EUR';

    public function __construct(
        private readonly ExchangerInterface $exchanger,
        private readonly BinlistClient $binlistClient,
    )
    {
    }

    /**
     * @throws CalculatorException
     */
    public function calculate(string $filename)
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
                throw new CalculatorException(\sprintf('Wrong bin ID: %d', (int) $value[0]), 0, $e);
            }

            try {
                $amountFixed = $this->exchanger->exchange(\floatval($value[1]), $value[2], self::DEFAULT_CURRENCY);
            } catch (ExchangerException $e) {
                throw new CalculatorException(\sprintf('Can\'t exchange from %s to %s', $value[2], self::DEFAULT_CURRENCY), 0, $e);
            }

            $isEu = self::isEu($bin->countryCode);

            echo $amountFixed * ($isEu === 'yes' ? 0.01 : 0.02);
            print "\n";
        }
    }

    private static function isEu($c)
    {
        $result = false;
        switch ($c) {
            case 'AT':
            case 'BE':
            case 'BG':
            case 'CY':
            case 'CZ':
            case 'DE':
            case 'DK':
            case 'EE':
            case 'ES':
            case 'FI':
            case 'FR':
            case 'GR':
            case 'HR':
            case 'HU':
            case 'IE':
            case 'IT':
            case 'LT':
            case 'LU':
            case 'LV':
            case 'MT':
            case 'NL':
            case 'PO':
            case 'PT':
            case 'RO':
            case 'SE':
            case 'SI':
            case 'SK':
                $result = 'yes';
                return $result;
            default:
                $result = 'no';
        }
        return $result;
    }
}