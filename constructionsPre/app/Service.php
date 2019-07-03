<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['service_type_id', 'name', 'price', 'user_id'];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function type(){
        return $this->hasOne('App\ServiceType', 'id', 'service_type_id');
    }
}
