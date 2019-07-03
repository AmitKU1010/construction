<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = ['user_id', 'location', 'address','avatar', 'build_up_area'];
    protected $guarded = ['id', 'created_at', 'updated_at','deleted_at'];

    
}
