<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozorprace_bot\Models\PraceAnnouncement;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class MyAnnouncements extends Command
{
    public static $command = 'my_announcements';

    public static $title = [
        'ru' => 'Мои объявления',
        'en' => 'My announsements',
    ];

    public static $usage = ['my_announcements'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $user_id = $updates->getFrom()->getId();

        $announcements = PraceAnnouncement::where('chat', DB::getChat($updates->getChat()->getId())->id)
            ->whereIn('status', ['new', 'published'])
            ->paginate(10);

        if ($announcements->isEmpty()) {
            return BotApi::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text'              => "У Вас еще нет ни одного объявления",
                'show_alert'        => true
            ]);
        }

        $buttons = $announcements->map(function (PraceAnnouncement $announcement) {
            $status = $announcement->status === 'new' ? '🆕' : '✅';
            $title  = $status. " " . substr(($announcement->title ?? $announcement->caption), 0, 30);
            return [array($title, ShowMyAnnouncement::$command, $announcement->id)];
        })->toArray();
        
        $buttons = BotApi::inlineKeyboard([
            ...$buttons,
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ], 'announcement_id');

        $data = [
            'text'          => "Мои объявления",
            'reply_markup'  => $buttons,
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            'parse_mode'    => "Markdown"
        ];
                                
        return BotApi::editMessageText($data);
    }
}
