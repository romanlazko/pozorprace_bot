<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SaveEducation extends Command
{
    public static $command = 'propose_save_education';

    public static $usage = ['propose_save_education'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        
        $conversation->update([
            'education' => $updates->getInlineData()->getEducation(),
        ]);

        return $this->bot->executeCommand(Caption::$command);
    }
}
