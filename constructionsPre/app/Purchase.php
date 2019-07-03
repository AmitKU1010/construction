<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $guarded = ['id', 'created_at', 'updated_at','deleted_at'];

    public function customer(){
    	return $this->hasOne('App\User', 'id', 'customer_id');
    }
    public function installment(){
    	return $this->hasMany('App\PurchasesInstallment', 'purchase_id', 'id');
    }
    public function property(){
    	return $this->hasOne('App\Property', 'id', 'property_id');
    }
}
