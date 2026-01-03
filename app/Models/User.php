<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'bio',
        'location',
    ];

    // Relasi role (wajib untuk middleware)
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function journalEntries()
    {
        return $this->hasMany(\App\Models\JournalEntry::class, 'user_id');
    }

    public function accountRequests()
    {
        return $this->hasMany(\App\Models\AccountRequest::class);
    }
}
