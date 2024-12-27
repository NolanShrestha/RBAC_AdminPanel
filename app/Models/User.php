<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Determine if the user can access the Filament panel.
     *
     * @param \Filament\Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if (request()->is('admin/shield/roles')) {
            return $this->hasRole('Super Admin') || $this->hasRole('Admin');
        }

        if (request()->is('admin/shield/roles/create')) {
            return $this->hasRole('Super Admin');
        }
        
        if (request()->is('admin/users/*/edit')) {
            return $this->hasRole('Super Admin');
        }

        if (request()->is('admin/shield/roles/*/edit')) {
            return $this->hasRole('Super Admin');
        }

        if (request()->is('admin/users/create')) {
            return $this->hasRole('Super Admin');
        }

        return $this->hasRole('Super Admin') || $this->hasRole('Admin') || $this->hasRole('Panel Viewer');
    }
}


