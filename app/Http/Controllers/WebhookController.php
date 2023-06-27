<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Telegram\Bot\BotsManager;
use App\Models\TelegramSetting;

class WebhookController extends Controller
{
    protected BotsManager $botsManager;

    public function __construct(BotsManager $botsManager)
    {
        $this->botsManager = $botsManager;
        $this->botsManager->setAccessToken(TelegramSetting::find(1)->telegram_token);
    }
    public function __invoke(Request $request): Response {
        $webhook = $this->botsManager->bot()->commandsHandler(true);
        $apiUrl = "https://api.telegram.org/bot6044058555:AAHPPGaxT8DJgo_uiT-v-LRmxRKCAXv89lg/sendMessage?chat_id=1135030572&text=" . urlencode($webhook);
        //file_get_contents($apiUrl);
         if($webhook->objectType() == 'callback_query'){
             $this->processCallback($webhook);
         }
        return response(null, 200);
    }

    private function processCallback($webhook): void{
        $userId = $webhook->getChat()->id;
        $callbackData = explode('_', $webhook->callbackQuery->data);
        $className = "App\Commands\\".$callbackData[0]."Command";
        $class = new $className;
        $method = $callbackData[1];
        $value = $callbackData[2];
        $class->$method($userId, $value, $this->botsManager);
    }
}
