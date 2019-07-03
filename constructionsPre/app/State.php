<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = ['name', 'country_id'];
    protected $guarded = ['id'];

    public function statetype(){
        return $this->hasOne('App\Country', 'id', 'country_id');
    }
}
