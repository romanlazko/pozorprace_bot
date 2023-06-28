<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozorprace_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Photo extends Command
{
    public static $command = 'photo';

    public static $title = [
        'ru' => 'Загрузить фотографии',
        'en' => 'Load photos',
    ];

    public static $usage = ['photo'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->unsetNote('photo');

        $updates->getFrom()->setExpectation('photo|1');

        $buttons = BotApi::inlineKeyboard([
            [array('Без фотографий', Title::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ]);

        $data = [
            'text'          =>  "Пришли мне *фотографии* имеющие отношение к предлагаемой вакансии, *максимально 9 фото*."."\n\n".
                                "_Если фотографий нет, нажми_ *'Без фотографий'*.",
            'reply_markup'  =>  $buttons,
            'chat_id'       =>  $updates->getChat()->getId(),
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            'parse_mode'    =>  "Markdown"
        ];     
                        
        return BotApi::returnInline($data);
    }
}
