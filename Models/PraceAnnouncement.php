<?php

namespace App\Bots\pozorprace_bot\Models;

use App\Bots\pozorprace_bot\Http\DataTransferObjects\Announcement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Romanlazko\Telegram\Models\TelegramChat;

class PraceAnnouncement extends Model
{
    use HasFactory; use SoftDeletes;

    protected $guarded = [];

    public function photos()
    {
        return $this->hasMany(PraceAnnouncementPhoto::class, 'announcement_id', 'id');
    }

    public function chat()
    {
        return $this->belongsTo(TelegramChat::class, 'chat', 'id');
    }

    public function dto()
    {
        return Announcement::fromObject($this);
    }

    public function prepare()
    {
        return $this->dto()->prepare();
    }

    public function getPhotoAttribute()
    {
        return $this->dto()->photos;
    }
}
