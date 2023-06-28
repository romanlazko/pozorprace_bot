<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class MenuCommand extends Command
{
    public static $command = '/menu';

    public static $title = [
        'ru' => '🏠 Главное меню',
        'en' => '🏠 Menu'
    ];

    public static $usage = ['/menu', 'menu', 'Главное меню'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(NewAnnouncement::getTitle('ru'), NewAnnouncement::$command, '')],
            [array(MyAnnouncements::getTitle('ru'), MyAnnouncements::$command, '')],
            [array(RullesCommand::getTitle('ru'), RullesCommand::$command, '')]
        ]);

        $data = [
            'text'          =>  "Привет 👋" ."\n\n". "Я помогу тебе создать вакансию в каналах *Pozor! Работа*",
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        return BotApi::returnInline($data);
    }
}
