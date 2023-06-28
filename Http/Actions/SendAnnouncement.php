<?php 

namespace App\Bots\pozorprace_bot\Http\Actions;

use App\Bots\pozorprace_bot\Models\PraceAnnouncement;
use Romanlazko\Telegram\App\Telegram;

class SendAnnouncement
{
    public function __invoke(Telegram $telegram, PraceAnnouncement $announcement, int $chat_id)
    {
        $photos = $announcement->photos->pluck('file_id')->take(9);

        return $telegram::sendMessageWithMedia([
            'text'                      => $announcement->prepare(),
            'chat_id'                   => $chat_id,
            'media'                     => $photos ?? null,
            'parse_mode'                => 'HTML',
            'reply_markup'              => $buttons ?? null,
        ]);
    }
}
