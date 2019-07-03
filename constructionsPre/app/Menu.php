<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name', 'url', 'parent_id', 'sort', 'has_submenu', 'role_id', 'trash', 'icon'
    ];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*
		Let's Create a Relationship with Role Model
    */
    
}
