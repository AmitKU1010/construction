<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name', 'state_id'];
    protected $guarded = ['id'];

    public function citytype(){
        return $this->hasOne('App\State', 'id', 'state_id');
    }
}
