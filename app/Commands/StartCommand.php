<?php

namespace App\Commands;

use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected $name = 'start';
    protected $description = 'Запуск';

    public function handle()
    {
        $message = '<b> bolt text </b>';
        $this->replyWithMessage([
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);
    }

}