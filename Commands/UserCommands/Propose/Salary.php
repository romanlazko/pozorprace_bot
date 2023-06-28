<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozorprace_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Salary extends Command
{
    public static $command = 'propose_salary';

    public static $title = [
        'ru' => 'Указать оплату',
        'en' => 'Add salary'
    ];

    public static $usage = ['propose_salary'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwiatSalary::$expectation);
        
        $buttons = BotApi::inlineKeyboard([
            [
                array('👈 Назад', SalaryType::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')
            ]
        ]);

        $data = [
            'text'          => "Укажи в кронах *предлагаемую оплату*."."\n\n"."_Максимально_ *12* _символов_.",
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons,
            'message_id'    => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
        ];

        return BotApi::returnInline($data);
    }
}
