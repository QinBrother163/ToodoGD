<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementMgpayrecord extends Model
{
    protected $table = 'phplooprecords_mgpayrecord_201711';
    public $primaryKey = 'id';
    public $timestamps = false;

    public static function query($prefix = null)
    {
        if(!$prefix){
            return parent::query();
        }
        $instance = new self;
        $instance->table = 'phplooprecords_mgpayrecord_'.$prefix;
        return $instance->newQuery();
    }
}
