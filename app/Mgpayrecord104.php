<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mgpayrecord104 extends Model
{
    protected $connection = 'mysql3';
    protected $table = 'fsdp_mgpayrecord_91';
    public $primaryKey = 'id';
    public $timestamps = false;

    public static function query($prefix = null)
    {
        if(!$prefix){
            return parent::query();
        }
        $instance = new self;
        $instance->table = 'list_'.$prefix;
        return $instance->newQuery();
    }
}
