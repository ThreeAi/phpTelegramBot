<?php

namespace App\Commands;

use Telegram\Bot\Commands\Command;
use App\Models\TelegramUser;

class StartCommand extends Command
{
    protected $name = 'start';
    protected $description = 'Запуск';
    protected TelegramUser $telegramUser;
    protected $request = array(
        'webservice' => "https://moodle-monolith.spbstu.ru/webservice/rest/server.php?wstoken=",
        'moodleBotToken' => "c2f09f0db3b317e3f3ee96a15dde2871",
        'getUser' => "&wsfunction=get_user_by_field_tg&username_tg=",
        'format' => "&moodlewsrestformat=json"
    );

    public function __construct(TelegramUser $telegramUser)
    {
        $this->telegramUser = $telegramUser;
    }

    public function handle()
    {
        $userData = $this->getUpdate()->message->from;
        $moodleId = json_decode(file_get_contents($this->request['webservice'] . 
                                        $this->request['moodleBotToken'] . 
                                        $this->request['getUser'] . 
                                        $userData->username . 
                                        $this->request['format']));
        $user = $this->telegramUser->firstOrCreate(['user_id' => $userData->id],
        [
            'user_id' => $userData->id,
            'username' => $userData->username,
            'moodle_id' => $moodleId
        ]);
        if ($moodleId == 0){
            $this->replyWithMessage([
                'text' =>  'У вас не указан username',
                'parse_mode' => 'HTML'
            ]);
        }
        else if ($user->wasRecentlyCreated){
            $this->replyWithMessage([
                'text' =>  'Вы зарегистирированы в базе бота',
                'parse_mode' => 'HTML'
            ]);
        }
        else{
            $this->replyWithMessage([
                'text' =>  '<b>Рады видеть вас снова!</b>',
                'parse_mode' => 'HTML'
            ]);
        }
    }

}