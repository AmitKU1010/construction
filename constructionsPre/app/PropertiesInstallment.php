<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertiesInstallment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    protected $fillable = ['user_id', 'property_id', 'installment_no', 'installment_price', 'installment_desc'];
    protected $guarded = ['id', 'created_at', 'updated_at','deleted_at'];
}
