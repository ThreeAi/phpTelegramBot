<?php

namespace App\Commands;

use Telegram\Bot\BotsManager;
use Telegram\Bot\Commands\Command;
use App\Models\TelegramUser;

class GetFaqCommand extends Command
{
    protected $name = 'getfaq';
    protected $description = 'get FAQ';
    protected $request = array(
        'webservice' => "https://moodle-monolith.spbstu.ru/webservice/rest/server.php?wstoken=",
        'moodleBotToken' => "c2f09f0db3b317e3f3ee96a15dde2871",
        'getCourses' => "&wsfunction=core_enrol_get_users_courses&userid=",
        'getTelegaramContent' => "&wsfunction=get_telegrambotcontent&id=4",
        'format' => "&moodlewsrestformat=json"
    );

    public function handle()
    {
        $userData = $this->getUpdate()->message->from;
        $user = TelegramUser::where('user_id', $userData->id)->first();
        dump($this->getUpdate());
        if ($user == null) {
            $this->replyWithMessage([
                'text' => "<b> Для начала работы с ботом нужно прописать /start </b>",
                'parse_mode' => 'HTML'
            ]);
            exit;
        }
        $moodleId = $user->moodle_id;
        $courses = json_decode(file_get_contents($this->request['webservice'] .
            $this->request['moodleBotToken'] .
            $this->request['getCourses'] .
            $moodleId .
            $this->request['format']));
        $keyboard = array();
        if (empty($courses)){
            $this->replyWithMessage([
                'text' => "<b>Вы не записаны ни на один курс </b>",
                'parse_mode' => 'HTML'
            ]);
            exit;
        }
        foreach ($courses as $course) {
            array_push($keyboard, [['text' => $course->shortname, 'callback_data' => 'GetFaq_' . 'getQuestion_' . $course->id]]);
        }
        $encodeMarkup = json_encode(array('inline_keyboard' => $keyboard));
        $this->replyWithMessage([
            'text' => "<b>Курсы на которые вы записаны: </b>",
            'parse_mode' => 'HTML',
            'reply_markup' => (string)$encodeMarkup
        ]);
    }

    public function getQuestion($userId, $value, BotsManager $bot){
        $courses = json_decode(file_get_contents($this->request['webservice'] .
            $this->request['moodleBotToken'] .
            $this->request['getTelegaramContent'] .
            $this->request['format']), true);
        $keyboard = array();
        $courses = array_reverse($courses);
        foreach ($courses as $course){
            if ($course['course'] == $value){
                $questions = json_decode($course['structure'], true);
                foreach ($questions as $question) {
                    array_push($keyboard, [['text' => $question['name'], 'callback_data' => 'GetFaq_' . 'getAnswer_' . $question['answer']]]);
                }
            }
        }
        $encodeMarkup = json_encode(array('inline_keyboard' => $keyboard));
        $bot->sendMessage([
            'chat_id' => $userId,
            'text' => "<b>Выберите интересующий вас вопрос: </b>",
            'parse_mode' => 'HTML',
            'reply_markup' => (string)$encodeMarkup
        ]);
    }

    public function getAnswer($userId, $value, BotsManager $bot){
        $bot->sendMessage([
            'chat_id' => $userId,
            'text' => $value,
            'parse_mode' => 'HTML',
        ]);
    }
}
