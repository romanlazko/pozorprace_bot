<?php 

namespace App\Bots\pozorprace_bot\Commands\AdminCommands;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozorprace_bot\Models\PraceAnnouncement;
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
        $announcements = PraceAnnouncement::where('status', 'new')->paginate(10);

        $buttons = $announcements->map(function (PraceAnnouncement $announcement) {
            return [array($announcement->title ?? $announcement->caption, ShowAnnouncement::$command, $announcement->id)];
        })->toArray();

        $buttons = count($buttons) > 0 ? BotApi::inlineKeyboard($buttons, 'announcement_id') : null;  

        return BotApi::returnInline([
            'text'          => "Все объявления: {$announcements->total()}",
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()?->getMessage()->getMessageId(),
            'reply_markup'  => $buttons,
        ]);
    }
}
