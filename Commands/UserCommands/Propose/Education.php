<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozorprace_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Education extends Command
{
    public static $command = 'propose_education';

    public static $usage = ['propose_education'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array('Не требуется', SaveEducation::$command, 'not_required')],
            [array('Среднее', SaveEducation::$command, 'secondary')],
            [array('Высшее', SaveEducation::$command, 'higher')],
            [array('Специальное', SaveEducation::$command, 'special')],
            [
                array('👈 Назад', SalaryType::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')
            ]
        ], 'education');

        $data = [
            'text'          =>  "Укажи *требуемое образование*",
            'reply_markup'  =>  $buttons,
            'chat_id'       =>  $updates->getChat()->getId(),
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            'parse_mode'    =>  "Markdown"
        ];     
                        
        return BotApi::returnInline($data);
    }
}
