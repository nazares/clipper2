<?php

declare(strict_types=1);

namespace Console\Command\Hello;

use Clipper\CommandController;
use Clipper\Console;

class NameController extends CommandController
{
    public function handle(): void
    {
        $name = $this->hasParam('user') ? $this->getParam('user') : 'World';
        Console::print(sprintf("Hello, %s", $name));
    }
}
