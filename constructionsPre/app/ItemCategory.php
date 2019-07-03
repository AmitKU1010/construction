<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    protected $fillable = ['user_id', 'category_name'];
    protected $guarded = ['id', 'created_at', 'updated_at','deleted_at'];

    
}
