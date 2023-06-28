<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use App\Bots\pozorprace_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Offer extends Command
{
    public static $command = 'propose_offer';

    public static $title = [
        'ru' => 'Коммерческое предложение',
        'en' => 'Propose offer'
    ];

    public static $usage = ['propose_offer'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboardWithLink(
            [
                'text' => "Администрация", 
                'url' => "https://t.me/pozor_support"
            ],
            [
                [
                    array("👈 Назад", Propose::$command, ''),
                    array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')
                ]
            ]
        );

        $text = implode("\n", [
            "Коммерчиские объявления могут быть опубликованы только после согласования с Администрацией паблика."."\n",
            "Свяжитесь с *@pozor_support*, что бы получить подробную информацию."
        ]);

        $data = [
            'text'          => $text,
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons,
            'message_id'    => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
        ];

        return BotApi::returnInline($data);
    }
}
