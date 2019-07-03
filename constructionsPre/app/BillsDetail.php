<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillsDetail extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function servicePerson(){
        return $this->hasOne('App\User', 'id', 'service_person_id');
    }
    public function serviceType(){
        return $this->hasOne('App\ServiceType', 'id', 'service_type_id');
    }
    public function service(){
        return $this->hasOne('App\Service', 'id', 'service_id');
    }

}
