<?php

declare(strict_types=1);

namespace CuteCode;

use CuteCode\Exception\ProcessorException;
use CuteCode\Output\SimpleOutput;

class Command
{
    /**
     * @param array<int, string> $argv
     */
    public function __construct(private readonly Processor $processor, public array $argv)
    {
    }

    public function execute(): void
    {
        if (!isset($this->argv[1])) {
            SimpleOutput::writeLine('Specify filename');

            return;
        }

        try {
            $file = new \SplFileObject($this->argv[1]);
            $result = $this->processor->process($file);
            SimpleOutput::writeArray($result);
        } catch (ProcessorException $e) {
            SimpleOutput::writeLine($e->getMessage());
        } catch (\RuntimeException) {
            SimpleOutput::writeLine('Wrong filename');
        }
    }
}
