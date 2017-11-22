<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity104 extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'fsdp_activity_91';
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
