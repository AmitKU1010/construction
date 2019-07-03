<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemMaster extends Model
{
    protected $fillable = ['user_id', 'item_categories_id', 'item_subcategories_id','particular', 'unit'];
    protected $guarded = ['id', 'created_at', 'updated_at','deleted_at'];

    
}
