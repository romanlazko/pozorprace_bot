<?php 

namespace App\Bots\pozorprace_bot\Commands\AdminCommands;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozorprace_bot\Models\PraceAnnouncement;
use App\Bots\pozorprace_bot\Services\Announcement;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;
use Romanlazko\Telegram\Exceptions\TelegramUserException;

class ShowAnnouncement extends Command
{
    public static $command = 'show_announcement';

    public static $usage = ['show_announcement'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $announcement = PraceAnnouncement::findOr($updates->getInlineData()?->getAnnouncementId(), function () {
            throw new TelegramUserException("Объявление не найдено");
        });

        if ($announcement->status !== 'new') {
            throw new TelegramUserException("Объявление уже обработано");
        }

        try {
            BotApi::sendMessageWithMedia([
                'text'                      => $announcement->prepare(),
                'chat_id'                   => $updates->getChat()->getId(),
                'media'                     => $announcement->dto()->photos ?? null,
                'parse_mode'                => 'HTML',
                'disable_web_page_preview'  => 'true',
            ]);
        }
        catch (TelegramException $exception) {
            throw new TelegramUserException("Ошибка публикации: {$exception->getMessage()}");
        }
        
        return $this->sendConfirmMessage($updates, $announcement);
    }

    private function sendConfirmMessage(Update $updates, PraceAnnouncement $announcement): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(PublicAnnouncement::getTitle('ru'), PublicAnnouncement::$command, $announcement->id)],
            [array(RejectAnnouncement::getTitle('ru'), RejectAnnouncement::$command, $announcement->id)],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ], 'announcement_id');

        $contact = $announcement->chat()->first()->username 
            ? "*@{$announcement->chat()->first()->username}*" 
            : "[{$announcement->chat()->first()->first_name} {$announcement->chat()->first()->last_name}](tg://user?id={$announcement->chat()->first()->chat_id})";

        $text = implode("\n", [
            "Так будет выглядеть объявление." ."\n",
            "Автор: {$contact}" . "\n",
            "*Публикуем?*", 
        ]);

        return BotApi::sendMessage([
            'text'          => $text,
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => 'Markdown',
            'reply_markup'  => $buttons
        ]);
    }
}
