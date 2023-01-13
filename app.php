<?php declare(strict_types = 1);

use CuteCode\Calculator;
use CuteCode\Exception\CalculatorException;
use CuteCode\Exchanger\ApilayerExchanger;
use CuteCode\Exchanger\Client\ApiLayerClient;
use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new ApiLayerClient($_ENV['APILAYER_APIKEY']);
$exchanger = new ApilayerExchanger($client);
$calculator = new Calculator($exchanger);

try {
    $calculator->calculate($argv[1]);
} catch (CalculatorException $e) {
    echo $e->getMessage() . "\n";
}