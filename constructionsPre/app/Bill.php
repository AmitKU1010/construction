<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    //protected $fillable = ['service_type_id', 'name', 'price', 'user_id'];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function details(){
        return $this->hasMany('App\BillsDetail', 'bill_id', 'id');
    }
}
