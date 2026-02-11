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

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'role',
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
     * Map of roles to departments.
     */
    public static $roleMap = [
        'adminppic' => 'PPIC',
        'adminqcfitting' => 'QC Fitting',
        'adminqcflange' => 'QC Flange',
        'qcinspektorpd' => 'QC Inspektor PD',
        'qcinspectorfl' => 'QC Inspector FL',
        'admink3' => 'K3',
        'sales' => 'Sales',
        'adminsparepart' => 'Sparepart',
        'mr' => 'MR',
        'direktur' => 'Direktur'
    ];

    /**
     * Get the department associated with the user's role.
     */
    public function getDepartment(): ?string
    {
        return self::$roleMap[$this->role] ?? null;
    }

    /**
     * Check if the user is a global administrator.
     */
    public function isGlobalAdmin(): bool
    {
        return in_array($this->role, ['direktur', 'mr']);
    }
}
