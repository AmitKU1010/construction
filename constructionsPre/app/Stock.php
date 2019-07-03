<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $softDelete = true;    
    
    //protected $fillable = ['service_type_id', 'name', 'price', 'user_id'];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function supplier(){
        return $this->hasOne('App\Supplier', 'id', 'supplier_id');
    }
}
