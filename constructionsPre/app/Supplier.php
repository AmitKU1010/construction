<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['user_id', 'name', 'address', 'mobile', 'gstin', 'pan', 'email', 'type'];
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
}
