<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    //protected $fillable = ['user_id', 'project_name', 'project_desc','country_id', 'state_id', 'city_id', 'map_long', 'map_lat', 'supplier_id', 'total_price', 'down_payment', 'super_build_up_area', 'build_up_area'];
    protected $guarded = ['id', 'created_at', 'updated_at','deleted_at'];

    public function gallery(){
    	return $this->hasMany('App\PropertiesGallery', 'property_id', 'id');
    }
    public function country(){
    	return $this->hasOne('App\Country', 'id', 'country_id');
    }
    public function state(){
    	return $this->hasOne('App\State', 'id', 'state_id');
    }
    public function city(){
    	return $this->hasOne('App\City', 'id', 'city_id');
    }
    public function supplier(){
    	return $this->hasOne('App\Supplier', 'id', 'supplier_id');
    }
    public function installments(){
    	return $this->hasMany('App\PropertiesInstallment', 'property_id', 'id');
    }
    public function facility(){
    	return $this->hasMany('App\PropertiesFacility', 'property_id', 'id');
    }
    public function flats_type(){
    	return $this->hasOne('App\FlatsType', 'id', 'flat_type');
    }
    // public function properties_rooms(){
    //     return $this->hasOne('App\PropertiesRoom', 'property_id', 'id');
    // }
}
