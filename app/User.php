<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public static function departmentOptions()
    {
        return [
            'Office IT',
            'HRD & GA',
            'Production',
            'Marketing',
            'Finance',
            'Warehouse',
            'Purchasing',
            'Assy',
            'Mach',
            'PPC',
            'IT',
            'GA',
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'department'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
