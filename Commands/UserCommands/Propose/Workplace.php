<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozorprace_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Workplace extends Command
{
    public static $command = 'propose_workplace';

    public static $title = [
        'ru' => "Место работы",
        'en' => "Workplace"
    ];

    public static $usage = ['propose_workplace'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitWorkplace::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [
                array('👈 Назад', AdditionalInfo::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')
            ]
        ]);

        $data = [
            'text'          => "Напиши *место работы* (город, район, адрес или удаленно).". "\n\n" ."_Максимально_ *100* _символов, без эмодзи_.",
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons,
            'message_id'    => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
        ];

        return BotApi::returnInline($data);
    }
}
