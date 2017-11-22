<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementOrder extends Model
{
    protected $table = 'phplooprecords_order_201711';
    public $primaryKey = 'id';
    public $timestamps = false;

    public static function query($prefix = null)
    {
        if(!$prefix){
            return parent::query();
        }
        $instance = new self;
        $instance->table = 'phplooprecords_order_'.$prefix;
        return $instance->newQuery();
    }
}
