<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozorprace_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SalaryType extends Command
{
    public static $command = 'propose_salary_type';

    public static $usage = ['propose_salary_type'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array('Ставка в час', SaveSalaryType::$command, 'hour')],
            [array('Ставка в день', SaveSalaryType::$command, 'day')],
            [array('Оклад в месяц', SaveSalaryType::$command, 'month')],
            [array('За выполенную работу', SaveSalaryType::$command, 'ex_post')],
            [
                array('👈 Назад', Title::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')
            ]
        ], 'salary_type');

        $data = [
            'text'          =>  "Укажи *тип предлагаемой оплаты*",
            'reply_markup'  =>  $buttons,
            'chat_id'       =>  $updates->getChat()->getId(),
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            'parse_mode'    =>  "Markdown"
        ];     
                        
        return BotApi::returnInline($data);
    }
}
