<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mgpayrecord extends Model
{
    protected $table = 'fsdp_mgpayrecord_list_201710';
    public $primaryKey = 'id';
    public $timestamps = false;

    public static function query($prefix = null)
    {
        if(!$prefix){
            return parent::query();
        }
        $instance = new self;
        $instance->table = 'fsdp_mgpayrecord_list_'.$prefix;
        return $instance->newQuery();
    }
}
