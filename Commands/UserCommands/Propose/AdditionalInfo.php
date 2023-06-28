<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands\Propose;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozorprace_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AdditionalInfo extends Command
{
    public static $command = 'propose_additional_info';

    public static $usage = ['propose_additional_info'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->unsetNote('additional_info');

        $updates->getFrom()->setExpectation(AwaitAdditionalInfo::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [array('Пропустить', Workplace::$command, '')],
            [
                array('👈 Назад', Caption::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')
            ]
        ]);

        $data = [
            'text'          => "Напиши *дополнительные требования* (по желанию).". "\n\n" ."Например: наличие водительского удостоверения, специальные сертификаты, знание определенных языков.". "\n\n" ."_Максимально_ *200* _символов, без эмодзи_.",
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons,
            'message_id'    => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
        ];

        return BotApi::returnInline($data);
    }
}
