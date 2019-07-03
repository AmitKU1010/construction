<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','mobile', 'password','role_id', 'department_name', 'employee_designation', 'employee_gender', 'year_of_joining_school', 'dob',  'blood_group','basic','esic','epf','pt','gross_salary','net_salary','acadamic_qualification', 'professional_qualification', 'per_day_salary'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->hasOne('App\Role', 'id', 'role_id');
    }
    public function user_detail(){
        return $this->hasOne('App\UserDetail', 'users_id', 'id');
    }
}
