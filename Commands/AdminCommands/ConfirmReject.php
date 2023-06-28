<?php 

namespace App\Bots\pozorprace_bot\Commands\AdminCommands;

use App\Bots\pozorprace_bot\Events\AnnouncementRejected;
use App\Bots\pozorprace_bot\Models\PraceAnnouncement;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;
use Romanlazko\Telegram\Exceptions\TelegramUserException;

class ConfirmReject extends Command
{
    public static $command = 'confirm_reject';

    public static $title = [
        'ru' => 'Отклонить',
        'en' => 'Reject',
    ];

    public static $usage = ['confirm_reject'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $announcement = PraceAnnouncement::findOr($updates->getInlineData()?->getAnnouncementId(), function () {
            throw new TelegramUserException("Объявление не найдено");
        });

        if ($announcement->status !== 'new') {
            throw new TelegramUserException("Объявление уже обработано");
        }

        event(new AnnouncementRejected($announcement, $this->bot));

        $announcement->update([
            'status' => 'rejected'
        ]);

        return $this->bot->executeCommand(MenuCommand::$command);
    }

    private function sendRejectedNotification(PraceAnnouncement $announcement)
    {
        try {
            return BotApi::sendMessage([
                'chat_id'       => $announcement->t_chat->chat_id,
                'text'          => "Ваше объявление отклонено", 
                'parse_mode'    => 'HTML',
            ]);
        }
        catch (TelegramException $exception) {
            throw new TelegramUserException("Ошибка отправки уведомления пользователю: {$exception->getMessage()}");
        }
    }
}
