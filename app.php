<?php

use CuteCode\Calculator;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

Calculator::calculate($argv[1]);