<?php

namespace Clipper;

class CliPrinter
{
    protected function newLine(int $repeat = 0): string
    {
        return str_repeat(PHP_EOL, $repeat);
    }

    public function print(string $message): void
    {
        echo sprintf(
            "%s%s%s",
            $this->newLine(),
            $message,
            $this->newLine(2)
        );
    }
}
