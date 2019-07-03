<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $guarded = ['id', 'created_at'];

    public function user(){
        return $this->hasOne('App\User', 'id' , 'employee_id');
    }
    public function dates(){
        return $this->hasMany('App\SalariesDate' , 'salary_id', 'id');
    }
}
