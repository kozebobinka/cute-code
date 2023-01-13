<?php

declare(strict_types=1);

namespace CuteCode;

use CuteCode\Binlist\BinlistClientInterface;
use CuteCode\Binlist\Exception\BinlistException;
use CuteCode\CommissionCalculator\CommissionCalculatorInterface;
use CuteCode\DTO\TransactionDTO;
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
    ) {
    }

    /**
     * @return float[]
     * @throws ProcessorException
     */
    public function process(\SplFileObject $file): array
    {
        $file->openFile();
        $result = [];

        while (!$file->eof()) {
            $row = $file->fgets();

            if (\trim($row) === '') {
                continue;
            }

            try {
                $rowObject = \json_decode($row, false, 512, \JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                throw new ProcessorException('Can\'t decode line', 0, $e);
            }

            if (!isset($rowObject->bin, $rowObject->amount, $rowObject->currency)) {
                throw new ProcessorException('Can\'t find mandatory fields');
            }

            $transaction = new TransactionDTO((int) $rowObject->bin, \floatval($rowObject->amount), $rowObject->currency);

            try {
                $this->binlistClient->updateTransaction($transaction);
                $amountFixed = $this->exchanger->exchange($transaction->amount, $transaction->currency, self::DEFAULT_CURRENCY);
            } catch (BinlistException $e) {
                throw new ProcessorException(\sprintf('Wrong bin ID: %d', $transaction->binId), 0, $e);
            } catch (ExchangerException $e) {
                throw new ProcessorException(\sprintf('Can\'t exchange from %s to %s', $transaction->currency, self::DEFAULT_CURRENCY), 0, $e);
            }

            if ($transaction->countryCode === null) {
                throw new ProcessorException('Can\'t find country code');
            }

            $result[] = $this->commissionCalculator->calculate($amountFixed, $transaction->countryCode);
        }

        return $result;
    }
}
