<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    protected $fillable = ['user_id', 'firm_name', 'address','contact', 'gstin', 'pan', 'branch', 'account_no', 'ifsc'];
    protected $guarded = ['id', 'created_at', 'updated_at','deleted_at'];

    
}
