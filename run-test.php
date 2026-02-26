<?php

// Setup Laravel
require __DIR__ . '/bootstrap/app.php';

$kernel = app(\Illuminate\Contracts\Console\Kernel::class);

// Run the command
exit($kernel->handle(
    $input = new \Symfony\Component\Console\Input\ArrayInput([
        'command' => 'test:appointment',
        '--email' => 'ac5892496@gmail.com',
        '--phone' => '833 181 8600'
    ]),
    new \Symfony\Component\Console\Output\ConsoleOutput()
));
