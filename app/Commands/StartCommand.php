<?php

namespace App\Commands;

use App\Models\TelegramSetting;
use Telegram\Bot\Commands\Command;
use App\Models\TelegramUser;

class StartCommand extends Command
{
    protected $name = 'start';
    protected $description = 'Запуск';
    protected TelegramUser $telegramUser;
    protected $request = array(
        'webservice' => "/webservice/rest/server.php?wstoken=",
        'moodleToken' => "",
        'getUser' => "&wsfunction=get_user_by_field_tg&username_tg=",
        'format' => "&moodlewsrestformat=json"
    );

    public function __construct(TelegramUser $telegramUser)
    {
        $this->telegramUser = $telegramUser;
        $this->request['webservice'] = TelegramSetting::findOrFail(1)->moodle_url . $this->request['webservice'];
        $this->request['moodleToken'] = TelegramSetting::findOrFail(1)->moodle_token;
    }

    public function handle()
    {
        $userData = $this->getUpdate()->message->from;
        $moodleId = json_decode(file_get_contents($this->request['webservice'] .
                                        $this->request['moodleToken'] .
                                        $this->request['getUser'] .
                                        $userData->username .
                                        $this->request['format']));
        if ($moodleId == 0){
            $this->replyWithMessage([
                'text' =>  'У вас не указан username',
                'parse_mode' => 'HTML'
            ]);
            exit;
        }
        $user = $this->telegramUser->firstOrCreate(['user_id' => $userData->id],
        [
            'user_id' => $userData->id,
            'username' => $userData->username,
            'moodle_id' => $moodleId
        ]);
        if ($user->wasRecentlyCreated){
            $user = $this->telegramUser->firstOrCreate(['user_id' => $userData->id],
            [
                'user_id' => $userData->id,
                'username' => $userData->username,
                'moodle_id' => $moodleId
            ]);
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
