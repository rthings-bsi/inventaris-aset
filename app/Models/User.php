<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    
    protected $primaryKey = 'id_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    public function roleDetail()
    {
        return $this->belongsTo(Role::class, 'role', 'slug');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || ($this->roleDetail && $this->roleDetail->slug === 'admin');
    }

    public function getRoleDisplayNameAttribute(): string
    {
        if ($this->isAdmin()) return 'Administrator';
        return $this->roleDetail ? $this->roleDetail->name : ucfirst($this->role);
    }

    public function hasPermission(string $permission): bool
    {
        return \App\Models\RolePermission::where('role', $this->role)
            ->where('permission', $permission)
            ->exists();
    }
}
