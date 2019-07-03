<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    protected $fillable = ['name', 'user_id'];
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
