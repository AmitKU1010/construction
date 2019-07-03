<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConstructionSite extends Model
{
    protected $fillable = ['user_id', 'site_name','project_name', 'block_name', 'floor_no', 'variation',  'description' , 'address' , 'advance', 'no_of_installments' , 'installment_details'];
    protected $guarded = ['id', 'created_at', 'updated_at','deleted_at'];

    
}
