<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order103 extends Model
{
    protected $connection = 'mysql4';
    protected $table = 'order_201711';
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
