<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertiesRoom extends Model
{
    protected $fillable = ['block_name','floor_number', 'flate_number', 'flate_type', 'sq_ft','rate'];
   // protected $guarded = ['id', 'created_at', 'updated_at'];
} 
