<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SaveSalaryType extends Command
{
    public static $command = 'propose_save_salary_type';

    public static $usage = ['propose_save_salary_type'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        
        $conversation->update([
            'salary_type' => $updates->getInlineData()->getSalaryType(),
        ]);

        return $this->bot->executeCommand(Salary::$command);
    }
}
