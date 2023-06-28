<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use App\Bots\pozorprace_bot\Commands\UserCommands\AnnouncementType;
use App\Bots\pozorprace_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Propose extends Command
{
    public static $command = 'propose';

    public static $title = [
        'ru' => 'Предлагаю работу',
        'en' => 'Propose work'
    ];

    public static $usage = ['propose'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        
        $conversation->update([
            'type' => $updates->getInlineData()->getType(),
        ]);

        $buttons = BotApi::inlineKeyboard([
            [array('Да', Offer::$command, 'Не требуется')],
            [array('Нет', Title::$command, 'Среднее')],
            [
                array('👈 Назад', AnnouncementType::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')
            ]
        ]);

        $data = [
            'text'          => "Вы являетесь представителем Pracovní Agentury?",
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons,
            'message_id'    => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
        ];

        return BotApi::returnInline($data);
    }
}
