<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertiesVariant extends Model
{
    protected $fillable = ['user_id', 'property_id', 'block_name', 'floor_no', 'flat_type'];
    protected $guarded = ['id', 'created_at', 'updated_at','deleted_at'];

    public function typeOfFlat(){
    	return $this->hasOne('App\FlatsType', 'id', 'flat_type');
    }
}
