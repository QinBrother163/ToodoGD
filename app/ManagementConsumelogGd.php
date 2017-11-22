<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementConsumelogGd extends Model
{
    protected $table = 'phplooprecords_consumelog_gd';
    public $primaryKey = 'id';
    public $timestamps = false;

    public static function query($prefix = null)
    {
        if(!$prefix){
            return parent::query();
        }
        $instance = new self;
        $instance->table = 'phplooprecords_consumelog_'.$prefix;
        return $instance->newQuery();
    }
}
