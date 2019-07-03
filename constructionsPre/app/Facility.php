<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at','deleted_at'];
}
