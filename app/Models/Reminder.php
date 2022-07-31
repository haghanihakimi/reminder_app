<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reminder extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pid',
        'user_id',
        'reminder_title',
        'reminder_descriptions',
        'privacy',
        'due_date',
        'link',
    ];

    public function user () {
        return $this->belongsTo(User::class);
    }

    public function sharing () {
        return $this->hasOne(Sharing::class);
    }
}
