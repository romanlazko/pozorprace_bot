<?php
namespace App\Bots\pozorprace_bot\Commands;

use Romanlazko\Telegram\App\Commands\CommandsList as DefaultCommandsList;

class CommandsList extends DefaultCommandsList
{
    static private $commands = [
        'admin'     => [
            AdminCommands\StartCommand::class,
            AdminCommands\MenuCommand::class,
            AdminCommands\ShowAnnouncement::class,
            AdminCommands\PublicAnnouncement::class,
            AdminCommands\RejectAnnouncement::class,
            AdminCommands\ConfirmReject::class,
        ],
        'user'      => [
            UserCommands\StartCommand::class,
            UserCommands\MenuCommand::class,
            UserCommands\NewAnnouncement::class,
            
            UserCommands\AnnouncementType::class,

            //Propose work
            UserCommands\Propose\Propose::class,
            UserCommands\Propose\Offer::class,
            UserCommands\Propose\Photo::class,
            UserCommands\Propose\WithoutPhoto::class,
            UserCommands\Propose\AwaitPhoto::class,
            UserCommands\Propose\Title::class,
            UserCommands\Propose\AwaitTitle::class,
            UserCommands\Propose\SalaryType::class,
            UserCommands\Propose\SaveSalaryType::class,
            UserCommands\Propose\Salary::class,
            UserCommands\Propose\AwiatSalary::class,
            UserCommands\Propose\Education::class,
            UserCommands\Propose\SaveEducation::class,
            UserCommands\Propose\Caption::class,
            UserCommands\Propose\AwaitCaption::class,
            UserCommands\Propose\AdditionalInfo::class,
            UserCommands\Propose\AwaitAdditionalInfo::class,
            UserCommands\Propose\Workplace::class,
            UserCommands\Propose\AwaitWorkplace::class,
            UserCommands\Propose\ShowAnnouncement::class,
            UserCommands\Propose\SaveAnnouncement::class,
            UserCommands\Propose\PublicAnnouncement::class,
            UserCommands\Propose\Published::class,

            UserCommands\MyAnnouncements::class,
            UserCommands\RullesCommand::class,
            UserCommands\ShowMyAnnouncement::class,
            UserCommands\IrrelevantAnnouncement::class,
            UserCommands\GetOwnerContact::class,
        ],
        'supergroup' => [
        ],
        'default'   => [
        ],
    ];

    static public function getCommandsList(?string $auth)
    {
        return array_merge(
            (self::$commands[$auth] ?? []), 
            (self::$default_commands[$auth] ?? [])
        ) 
        ?? self::getCommandsList('default');
    }

    static public function getAllCommandsList()
    {
        foreach (self::$commands as $auth => $commands) {
            $commands_list[$auth] = self::getCommandsList($auth);
        }
        return $commands_list;
    }
}
