<?php

namespace App\Commands;

use Telegram\Bot\BotsManager;
use Telegram\Bot\Commands\Command;
use App\Models\TelegramUser;
use App\Models\TelegramSetting;

class GetDeadlinesCommand extends Command
{
    protected $name = 'getdeadlines';
    protected $description = 'get deadlines';
    protected $request = array(
        'webservice' => "/webservice/rest/server.php?wstoken=",
        'moodleToken' => "",
        'getCourses' => "&wsfunction=core_enrol_get_users_courses&userid=",
        'getAssigment' => "&wsfunction=mod_assign_get_assignments&courseids[0]=",
        'getQuizzes' => "&wsfunction=mod_quiz_get_quizzes_by_courses&courseids[0]=",
        'format' => "&moodlewsrestformat=json"
    );

    public function __construct(){
        $this->request['webservice'] = TelegramSetting::find(1)->moodle_url . $this->request['webservice'];
        $this->request['moodleToken'] = TelegramSetting::find(1)->moodle_token;
    }

    public function handle()
    {
        $userData = $this->getUpdate()->message->from;
        $user = TelegramUser::where('user_id', $userData->id)->first();
        if ($user == null){
            $this->replyWithMessage([
                'text' =>  "<b> Для начала работы с ботом нужно прописать /start </b>",
                'parse_mode' => 'HTML'
            ]);
            exit;
        }
        $moodleId = $user->moodle_id;
        $courses = json_decode(file_get_contents($this->request['webservice'] .
                                        $this->request['moodleToken'] .
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
        foreach ($courses as $course){
            array_push($keyboard , [['text' => $course->shortname, 'callback_data' => 'GetDeadlines_' . 'getTime_' . $course->id]]);
        }
        $encodeMarkup = json_encode(array('inline_keyboard' => $keyboard));
        $this->replyWithMessage([
            'text' =>  "<b>Курсы на которые вы записаны: </b>",
            'parse_mode' => 'HTML',
            'reply_markup' => (string)$encodeMarkup
        ]);
    }

    public function getTime($userId, $value, BotsManager $bot){
        $contentAssignments = json_decode(file_get_contents($this->request['webservice'] .
            $this->request['moodleToken'] .
            $this->request['getAssigment'] .
            $value .
            $this->request['format']), true);
        $assignments = $contentAssignments["courses"][0]["assignments"];
        $tasks = "";
        foreach($assignments as $assignment){
            $name = $assignment["name"];
            $deadline = $assignment["duedate"];
            if ($deadline == 0){
                $deadline = "без дедлайна";
            }
            else{
                $deadline = date('Y-m-d H:i:s', $deadline);
            }
            $tasks = $tasks . $name . ':      ' . $deadline . PHP_EOL;
        }
        if (strlen($tasks) != 0){
            $tasks = "<b> задания \n</b>" . $tasks;
        }
        $contentQuizzes = json_decode(file_get_contents($this->request['webservice'] .
            $this->request['moodleToken'] .
            $this->request['getQuizzes'] .
            $value .
            $this->request['format']), true);
        $quizzes = $contentQuizzes["quizzes"];
        $tests = "";
        foreach($quizzes as $quiz){
            $name = $quiz["name"];
            $deadline = $quiz["timeclose"];
            if ($deadline == 0){
                $deadline = "без дедлайна";
            }
            else{
                $deadline = date('Y-m-d H:i:s', $deadline);
            }
            $tests = $tests . $name . ':      ' . $deadline . PHP_EOL;
        }
        if (strlen($tests) != 0){
            $tests = "<b>тесты \n</b>" . $tests;
        }
        $text = $tasks . $tests;
        if (strlen($text) == 0){
            $text = "заданий нет";
        }
        $bot->sendMessage([
            'chat_id'  => $userId,
            'text' => $text,
            'parse_mode' => 'HTML'
        ]);
    }
}
