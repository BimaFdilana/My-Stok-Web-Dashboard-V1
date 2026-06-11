<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasirSchedule extends Model
{
    protected $fillable = ['user_id', 'hari', 'jam_masuk', 'jam_keluar'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
