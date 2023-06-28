<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozorprace_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Title extends Command
{
    public static $command = 'propose_title';

    public static $title = [
        'ru' => 'Добавить заголовок',
        'en' => 'Add title'
    ];

    public static $usage = ['propose_title'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitTitle::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [
                array('👈 Назад', Propose::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')
            ]
        ]);

        $data = [
            'text'          => "Напиши *название вакансии*."."\n\n"."_Максимально_ *30* _символов, без эмодзи_.",
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons
        ];

        return BotApi::returnInline($data);
    }




}
