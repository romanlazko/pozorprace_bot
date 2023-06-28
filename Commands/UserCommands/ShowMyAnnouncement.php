<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozorprace_bot\Models\PraceAnnouncement;
use App\Bots\pozorprace_bot\Services\Announcement;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;
use Romanlazko\Telegram\Exceptions\TelegramUserException;

class ShowMyAnnouncement extends Command
{
    public static $command = 'show_my_announcement';

    public static $usage = ['show_my_announcement'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $announcement = PraceAnnouncement::findOr($updates->getInlineData()?->getAnnouncementId(), function () {
            throw new TelegramUserException("Объявление не найдено");
        });

        if ($announcement->status === 'irrelevant') {
            throw new TelegramUserException("Объявление уже не актуально");
        }

        if ($announcement->status === 'rejected') {
            throw new TelegramUserException("Объявление отклонено");
        }

        try {
            BotApi::sendMessageWithMedia([
                'text'                      => $announcement->prepare(),
                'chat_id'                   => $updates->getChat()->getId(),
                'media'                     => $announcement->dto()->photos ?? null,
                'parse_mode'                => 'HTML',
                'disable_web_page_preview'  => 'true',
            ]);

            return $this->sendConfirmMessage($updates, $announcement);
        }
        catch (TelegramException $exception) {
            throw new TelegramUserException("Ошибка публикации: {$exception->getMessage()}");
        }
    }
    
    private function sendConfirmMessage(Update $updates, PraceAnnouncement $announcement): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(IrrelevantAnnouncement::getTitle('ru'), IrrelevantAnnouncement::$command, $announcement->id)],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ], 'announcement_id');

        return BotApi::sendMessage([
            'text'          => "Дата публикации: *{$announcement->created_at->format('d.m.Y')}*", 
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => 'Markdown',
            'reply_markup'  => $buttons
        ]);
    }
}
