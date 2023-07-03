<?php

namespace App\Commands;

use App\Models\TelegramSetting;
use Telegram\Bot\BotsManager;
use Telegram\Bot\Commands\Command;
use App\Models\TelegramUser;

class GetFaqCommand extends Command
{
    protected $name = 'getfaq';
    protected $description = 'get FAQ';
    protected $request = array(
        'webservice' => "/webservice/rest/server.php?wstoken=",
        'moodleToken' => "",
        'getCourses' => "&wsfunction=core_enrol_get_users_courses&userid=",
        'getTelegaramContent' => "&wsfunction=get_telegrambotcontent&id=4",
        'format' => "&moodlewsrestformat=json"
    );

    public function __construct()
    {
        $this->request['webservice'] = TelegramSetting::findOrFail(1)->moodle_url . $this->request['webservice'];
        $this->request['moodleToken'] = TelegramSetting::findOrFail(1)->moodle_token;
    }

    public function handle()
    {
        $userData = $this->getUpdate()->message->from;
        $user = TelegramUser::where('user_id', $userData->id)->first();
        if ($user == null) {
            $this->replyWithMessage([
                'text' => "<b> Для начала работы с ботом нужно прописать /start </b>",
                'parse_mode' => 'HTML'
            ]);
            return;
        }
        $moodleId = $user->moodle_id;
        $courses = json_decode(file_get_contents($this->request['webservice'] .
            $this->request['moodleToken'] .
            $this->request['getCourses'] .
            $moodleId .
            $this->request['format']));
        $keyboard = array();
        if (empty($courses)) {
            $this->replyWithMessage([
                'text' => "<b>Вы не записаны ни на один курс </b>",
                'parse_mode' => 'HTML'
            ]);
            return;
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

    public function getQuestion($userId, $value, BotsManager $bot)
    {
        $courses = json_decode(file_get_contents(
            $this->request['webservice'] .
            $this->request['moodleToken'] .
            $this->request['getTelegaramContent'] .
            $this->request['format']), true);
        $keyboard = array();
        $courses = array_reverse($courses);
        foreach ($courses as $course) {
            if ($course['course'] == $value) {
                $questions = json_decode($course['structure'], true);
                foreach ($questions as $question) {
                    $callback_data = 'GetFaq_' . 'getAnswer_' . $value . ' ' . $question['id'];
                    array_push($keyboard, [['text' => $question['name'], 'callback_data' => $callback_data]]);
                }
            }
        }
        if (empty($keyboard)) {
            $bot->sendMessage([
                'chat_id' => $userId,
                'text' => "<b>На этом курсе не реализован этот модуль </b>",
                'parse_mode' => 'HTML',
            ]);
            return;
        }
        $encodeMarkup = json_encode(array('inline_keyboard' => $keyboard));
        $bot->sendMessage([
            'chat_id' => $userId,
            'text' => "<b>Выберите интересующий вас вопрос: </b>",
            'parse_mode' => 'HTML',
            'reply_markup' => (string)$encodeMarkup
        ]);
    }

    public function getAnswer($userId, $value, BotsManager $bot)
    {
        $values = explode(' ', $value);
        $id_course = $values[0];
        $id_question = $values[1];
        $courses = json_decode(file_get_contents(
            $this->request['webservice'] .
            $this->request['moodleToken'] .
            $this->request['getTelegaramContent'] .
            $this->request['format']), true);
        $courses = array_reverse($courses);
        $text = '';
        foreach ($courses as $course) {
            if ($course['course'] == $id_course) {
                $questions = json_decode($course['structure'], true);
                foreach ($questions as $question) {
                    if ($question['id'] == $id_question) {
                        $text = $question['answer'];
                    }
                }
            }
        }
        $bot->sendMessage([
            'chat_id' => $userId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}
