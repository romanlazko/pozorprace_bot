<?php

namespace App\Bots\pozorprace_bot\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PraceAnnouncementPhoto extends Model
{
    use HasFactory; use SoftDeletes;

    protected $fillable = [
        'file_id'
    ];
}
