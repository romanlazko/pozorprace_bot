<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitWorkplace extends Command
{
    public static $expectation = 'await_propose_workplace';

    public static $pattern = '/^await_propose_workplace$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        $text           = $updates->getMessage()?->getText();

        if ($text === null) {
            $this->handleError("*Пришлите пожалуйста адрес в виде текста.*");
            return $this->bot->executeCommand(Workplace::$command);
        }

        if (iconv_strlen($text) > 100){
            $this->handleError("*Слишком много символов*");
            return $this->bot->executeCommand(Workplace::$command);
        }

        $conversation->update([
            'workplace' => $text,
        ]);
        
        return $this->bot->executeCommand(ShowAnnouncement::$command);
    }
}
