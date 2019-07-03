<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['user_id', 'customer_name', 'dob','contact', 'email', 'down_payment', 'adhhar', 'address', 'office_address', 'gender', 'total_price', 'supper_build_up_area', 'build_up_area'];
    protected $guarded = ['id', 'created_at', 'updated_at','deleted_at'];

    
}
