<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GdOrder extends Model
{
    protected $table = 'order_201710';
    public $primaryKey = 'order_id';
    public $timestamps = false;

    public static function query($prefix = null)
    {
        if(!$prefix){
            return parent::query();
        }
        $instance = new self;
        $instance->table = 'order_'.$prefix;
        return $instance->newQuery();
    }
}
