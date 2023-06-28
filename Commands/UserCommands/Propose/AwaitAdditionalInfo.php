<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitAdditionalInfo extends Command
{
    public static $expectation = 'await_propose_additional_info';

    public static $pattern = '/^await_propose_additional_info$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        $text           = $updates->getMessage()?->getText();

        if ($text === null) {
            $this->handleError("*Пришлите пожалуйста требования в виде текста.*");
            return $this->bot->executeCommand(AdditionalInfo::$command);
        }

        if (iconv_strlen($text) > 200){
            $this->handleError("*Слишком много символов*");
            return $this->bot->executeCommand(AdditionalInfo::$command);
        }

        $conversation->update([
            'additional_info' => $text,
        ]);
        
        return $this->bot->executeCommand(Workplace::$command);
    }
}
