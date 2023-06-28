<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozorprace_bot\Commands\UserCommands\MenuCommand;
use App\Bots\pozorprace_bot\Http\DataTransferObjects\Announcement;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;
use Romanlazko\Telegram\Exceptions\TelegramUserException;

class ShowAnnouncement extends Command
{
    public static $command = 'propose_show_announcement';

    public static $usage = ['propose_show_announcement'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $notes = $this->getConversation()->notes;

        try {
            $announcement = Announcement::fromObject((object) $notes);
            
            BotApi::sendMessageWithMedia([
                'text'                      => $announcement->prepare($notes),
                'chat_id'                   => $updates->getChat()->getId(),
                'media'                     => $announcement->photos ?? null,
                'parse_mode'                => 'HTML',
            ]);
        }
        catch (TelegramException $exception) {
            throw new TelegramUserException("Ошибка публикации: {$exception->getMessage()}");
        }
        
        return $this->sendConfirmMessage($updates);
    }

    private function sendConfirmMessage(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(PublicAnnouncement::getTitle('ru'), PublicAnnouncement::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ]);

        return BotApi::sendMessage([
            'text'          => "Так будет выглядеть твое объявление." ."\n\n". "*Публикуем?*", 
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => 'Markdown',
            'reply_markup'  => $buttons
        ]);
    }
}