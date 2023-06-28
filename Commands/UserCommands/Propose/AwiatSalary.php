<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwiatSalary extends Command
{
    public static $expectation = 'await_propose_salary';

    public static $pattern = '/^await_propose_salary$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        $text           = $updates->getMessage()?->getText();

        if ($text === null) {
            $this->handleError("*Пришлите пожалуйста размер оплаты в виде текстового сообщения.*");
            return $this->bot->executeCommand(Salary::$command);
        }

        if ($text === '0' OR $text == '0' OR $text === 0 OR $text == 0) {
            $this->handleError("*Размер оплаты не может быть нулевым*");
            return $this->bot->executeCommand(Salary::$command);
        }

        if (iconv_strlen($text) > 12){
            $this->handleError("*Слишком много символов*");
            return $this->bot->executeCommand(Salary::$command);
        }

        if (!is_numeric($text)){
            $this->handleError("*Размер оплаты должн быть указан только цифрами*");
            return $this->bot->executeCommand(Salary::$command);
        }

        $conversation->update([
            'salary' => $text,
        ]);

        return $this->bot->executeCommand(Education::$command);
    }
}
