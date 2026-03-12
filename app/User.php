<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Default department options used across request forms.
     *
     * @return array
     */
    public static function departmentOptions()
    {
        return [
            'Assy',
            'Mach',
            'PPC',
            'MTC/Facility',
            'Finance',
            'QC',
            'HRGA',
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
