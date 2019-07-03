<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = ['user_id', 'reference_name', 'follow_up_days','commision_amt', 'report', 'advance_amt'];
    protected $guarded = ['id', 'created_at', 'updated_at','deleted_at'];

    
}
