<?php

namespace App\Bots\pozorprace_bot\Http\DataTransferObjects;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Romanlazko\Telegram\App\Config;

class Announcement
{
    public function __construct(
        public ?int $id = null,
        public ?int $chat = null,
        public ?string $city = null,
        public ?string $type = null,
        public ?string $title = null,
        public ?string $caption = null,
        public ?string $additional_info = null,
        public ?string $salary_type = null,
        public ?string $salary = null,
        public ?string $education = null,
        public ?string $workplace = null,
        public ?int $views = null,
        public ?string $status = null,
        public Collection|array|null $photos = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
    )
    {

    }

    public static function fromObject($data): Announcement
    {
        $photos = null;

        if (isset($data->photos)){
            if ($data->photos instanceof Collection) {
                $photos = $data->photos->pluck('file_id')->take(9);
            } elseif (is_array($data->photos)) {
                $photos = collect($data->photos)->map(function ($photo) {
                    return ['file_id' => $photo];
                })->pluck('file_id')->take(9);
            }
        }

        return new self(
            id: $data->id ?? null,
            chat: $data->chat ?? null,
            city: $data->city ?? null,
            type: $data->type ?? null,
            title: $data->title ?? null,
            caption: $data->caption ?? null,
            additional_info: $data->additional_info ?? null,
            salary_type: $data->salary_type ?? null,
            salary: $data->salary ?? null,
            education: $data->education ?? null,
            workplace: $data->workplace ?? null,
            views: $data->views ?? null,
            status: $data->status ?? null,
            photos: $photos,
            created_at: $data->created_at ?? null,
            updated_at: $data->updated_at ?? null,
        );
    }
    
    public function prepare() {

        $text = [];

        if ($this->type) {
            $text[] = $this->type === 'propose' ? "#предлагаю_работу" : "#ищу_работу";
        }

        if ($this->title) {
            $text[] = "<b>{$this->title}</b>";
        }

        if ($this->caption) {
            $text[] = $this->caption;
        }

        if ($this->additional_info) {
            $text[] = $this->additional_info;
        }

        if ($this->salary_type AND $this->salary) {
            $salary_type_arr = [
                'hour' => "в час",
                'day' => "в день",
                'month' => "в месяц",
                'ex_post' => "за выполненную работу",
            ];
            $text[] = "<i>Предлагаемая оплата:</i> {$this->salary} CZK {$salary_type_arr[$this->salary_type]}";
        }

        if ($this->education) {
            $education_arr = [
                'not_required' => "Не требуется",
                'secondary' => "Среднее",
                'higher' => "Высшее",
                'special' => "Специальное",
            ];
            $text[] = "<i>Требуемое образование:</i> {$education_arr[$this->education]}";
        }

        if ($this->workplace) {
            $text[] = "<i>Место работы:</i> {$this->workplace}";
        }

        if ($this->id) {
            $text[] = "<a href='https://t.me/". Config::get('bot_username') ."?start=announcement={$this->id}'>🔗Контакт</a> (<i>Перейди по ссылке и нажми <b>Начать</b></i>)";
        }

        return implode("\n\n", $text);
    }
}
