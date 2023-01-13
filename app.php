<?php declare(strict_types = 1);

use CuteCode\Binlist\BinlistClient;
use CuteCode\Command;
use CuteCode\CommissionCalculator\CommissionCalculator;
use CuteCode\Processor;
use CuteCode\Exchanger\ApilayerExchanger;
use CuteCode\Exchanger\Client\ApiLayerClient;
use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new ApiLayerClient($_ENV['APILAYER_APIKEY']);
$exchanger = new ApilayerExchanger($client);
$binlistClient = new BinlistClient();
$commissionCalculator = new CommissionCalculator();
$processor = new Processor($exchanger, $binlistClient, $commissionCalculator);
$command = new Command($processor, $argv);

$command->execute();