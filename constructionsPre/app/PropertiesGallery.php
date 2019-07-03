<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertiesGallery extends Model
{
    protected $fillable = ['user_id', 'property_id', 'property_image', 'is_primary'];
    protected $guarded = ['id', 'created_at', 'updated_at','deleted_at'];
}
