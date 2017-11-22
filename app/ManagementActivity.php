<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementActivity extends Model
{
    protected $table = 'phplooprecords_activity_201711';
    public $primaryKey = 'id';
    public $timestamps = false;

    public static function query($prefix = null)
    {
        if(!$prefix){
            return parent::query();
        }
        $instance = new self;
        $instance->table = 'phplooprecords_activity_'.$prefix;
        return $instance->newQuery();
    }
}
