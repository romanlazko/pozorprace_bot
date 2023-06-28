<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use App\Bots\pozorprace_bot\Http\DataTransferObjects\Announcement;
use App\Bots\pozorprace_bot\Models\PraceAnnouncement;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SaveAnnouncement extends Command
{
    public static $command = 'propose_save_announcement';

    public static $usage = ['propose_save_announcement'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $notes = $this->getConversation()->notes;

        $announcement = Announcement::fromObject((object) $notes);

        $announcement = PraceAnnouncement::updateOrCreate([
            'chat'              => DB::getChat($updates->getChat()->getId())->id,
            'city'              => $announcement->city,
            'type'              => $announcement->type,
            'title'             => $announcement->title,
            'caption'           => $announcement->caption,
            'additional_info'   => $announcement->additional_info,
            'salary_type'       => $announcement->salary_type,
            'salary'            => $announcement->salary,
            'education'         => $announcement->education,
            'workplace'         => $announcement->workplace,
            'status'            => 'new',
        ]);

        if (array_key_exists('photos', $notes)) {
            foreach ($announcement->photos as $id => $file_id) {
                $announcement->photos()->updateOrCreate([
                    'file_id' => $file_id,
                ]);
            }
        }
        
        return $this->bot->executeCommand(Published::$command);
    }
}