<?php

declare(strict_types=1);

namespace CuteCode\Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

abstract class AbstractClientTest extends TestCase
{
    protected function getClientWithResponseFromFile(string $filename): Client
    {
        $file = new \SplFileObject(__DIR__ . \DIRECTORY_SEPARATOR . 'Media' . \DIRECTORY_SEPARATOR . 'Responses' . \DIRECTORY_SEPARATOR . $filename);
        $file->openFile();

        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->method('getContents')
            ->willReturn($file->fgets());
        $response = $this->createMock(ResponseInterface::class);
        $response
            ->method('getBody')
            ->willReturn($stream);
        $client = $this->createMock(Client::class);
        $client
            ->method('request')
            ->willReturn($response);

        return $client;
    }
}
