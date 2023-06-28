<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozorprace_bot\Commands\UserCommands\Propose\Propose;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AnnouncementType extends Command
{
    public static $command = 'type';

    public static $title = '';

    public static $usage = ['type'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();

        $conversation->update([
            'city' => $updates->getInlineData()->getCity()
        ]);

        $buttons = BotApi::inlineKeyboard([
            [
                // array('Ищу работу', AnnouncementSearch::$command, 'search'),
                array(Propose::getTitle('ru'), Propose::$command, 'propose')
            ],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ], 'type');

        $data = [
            'text'          => "Какой *тип* объявления ты хочешь прислать?",
            'reply_markup'  => $buttons,
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            'parse_mode'    => "Markdown"
        ];
                
        return BotApi::editMessageText($data);
        
    }
}
