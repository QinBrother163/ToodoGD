<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsumelogGd103 extends Model
{
    protected $connection = 'mysql5';
    protected $table = 'consumelog_gd';
    public $primaryKey = 'id';
    public $timestamps = false;

    public static function query($prefix = null)
    {
        if(!$prefix){
            return parent::query();
        }
        $instance = new self;
        $instance->table = 'consumelog_'.$prefix;
        return $instance->newQuery();
    }
}
