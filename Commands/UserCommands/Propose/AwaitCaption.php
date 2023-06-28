<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitCaption extends Command
{
    public static $expectation = 'await_propose_caption';

    public static $pattern = '/^await_propose_caption$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        $text           = $updates->getMessage()?->getText();

        if ($text === null) {
            $this->handleError("*Пришлите пожалуйста описание в виде текста.*");
            return $this->bot->executeCommand(Caption::$command);
        }

        if (iconv_strlen($text) > 600){
            $this->handleError("*Слишком много символов*");
            return $this->bot->executeCommand(Caption::$command);
        }

        $conversation->update([
            'caption' => $text,
        ]);
        
        return $this->bot->executeCommand(AdditionalInfo::$command);
    }
}
