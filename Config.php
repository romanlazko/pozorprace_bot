<?php

namespace App\Bots\pozorprace_bot;


class Config
{
    public static function getConfig()
    {
        return [
            'inline_data'       => [
                'city'              => null,
                'type'              => null,
                'salary_type'         => null,
                'education'          => null,
                'announcement_id'   => null,
            ],
            'brno'      => '@pozor_test',
            'prague'    => '@pozor_test',
            'admin_ids'         => [
                
            ],
            'bot_username' => 'pozorprace_bot'
        ];
    }
}
