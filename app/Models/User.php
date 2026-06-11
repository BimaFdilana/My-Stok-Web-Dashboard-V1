<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'username',
        'nama_pemilik',
        'name',
        'email',
        'foto',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    public function permissions()
    {
        return $this->hasMany(KasirPermission::class);
    }

    public function schedules()
    {
        return $this->hasMany(KasirSchedule::class);
    }

    public function hasMenuAccess(string $menuKey): bool
    {
        if ($this->isAdmin()) return true;
        return $this->permissions()->where('menu_key', $menuKey)->exists();
    }
}
