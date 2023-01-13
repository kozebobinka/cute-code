<?php

namespace CuteCode\Tests;

use CuteCode\Binlist\BinlistClient;
use CuteCode\Command;
use CuteCode\CommissionCalculator\CommissionCalculator;
use CuteCode\Exchanger\ApilayerExchanger;
use CuteCode\Exchanger\Client\ApiLayerClient;
use CuteCode\Processor;

class CommandTestCase extends AbstractClientTest
{
    /**
     * @dataProvider filesDataProvider
     */
    public function testExecute(?string $filename, string $result): void
    {
        $client = new ApiLayerClient($this->getClientWithResponseFromFile('apilayer.json'), '');
        $exchanger = new ApilayerExchanger($client);
        $binlistClient = new BinlistClient($this->getClientWithResponseFromFile('binlist.json'));
        $commissionCalculator = new CommissionCalculator();
        $processor = new Processor($exchanger, $binlistClient, $commissionCalculator);
        $command = new Command($processor, $filename === null ? [] : [1 => $filename]);

        $this->expectOutputString($result);

        $command->execute();
    }

    /**
     * @return array<string, mixed[]>
     */
    public function filesDataProvider(): array
    {
        $dir = __DIR__ . \DIRECTORY_SEPARATOR . 'Media' . \DIRECTORY_SEPARATOR . 'Input' . \DIRECTORY_SEPARATOR;
        return [
            'no file' => [null, "Specify filename\n"],
            'wrong file' => ['wrong.txt', "Wrong filename\n"],
            'good file' => [$dir . 'input.txt', "2\n0.93\n"],
            'good file with blank lines' => [$dir . 'input_blank_lines.txt', "2\n0.93\n"],
            'file with wrong currency' => [$dir . 'input_wrong_currency.txt', "Can't exchange from WWW to EUR\n"],
            'file with wrong data' => [$dir . 'input_wrong_data.txt', "Can't find mandatory fields\n"],
            'file with wrong json' => [$dir . 'input_wrong_json.txt', "Can't decode line\n"],
        ];
    }
}
