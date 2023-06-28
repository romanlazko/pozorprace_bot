<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitTitle extends Command
{
    public static $expectation = 'await_propose_title';

    public static $pattern = '/^await_propose_title$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        $text           = $updates->getMessage()?->getText();

        if ($text === null) {
            $this->handleError("*Пришлите пожалуйста название в виде текста.*");
            return $this->bot->executeCommand(Title::$command);
        }

        if (iconv_strlen($text) > 31){
            $this->handleError("*Слишком много символов*");
            return $this->bot->executeCommand(Title::$command);
        }

        $conversation->update([
            'title' => $text
        ]);
        
        return $this->bot->executeCommand(SalaryType::$command);
    }
}
