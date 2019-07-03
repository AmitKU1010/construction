<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemSubcategory extends Model
{
    protected $fillable = ['user_id', 'item_categories_id', 'subcategory_name'];
    protected $guarded = ['id', 'created_at', 'updated_at','deleted_at'];

    public function category(){
    	return $this->hasMany('App\ItemCategory', 'item_categories_id', 'id');
    }
}
