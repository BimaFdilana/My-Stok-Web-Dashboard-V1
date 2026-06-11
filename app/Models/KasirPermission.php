<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasirPermission extends Model
{
    protected $fillable = ['user_id', 'menu_key'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
