<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\uuids;

class Notification extends Model
{
    use HasFactory, uuids;

    public function user () {
        return $this->belongsTo(User::class);
    }
}
