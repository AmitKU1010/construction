<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlatsType extends Model
{
    protected $fillable = ['flat_type'];
    protected $guarded = ['id', 'created_at', 'updated_at','deleted_at'];
}
