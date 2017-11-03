<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class activity extends Model
{

    protected $table = 'fsdp_activity_list_201710';
    public $primaryKey = 'id';
    public $timestamps = false;

    public static function query($prefix = null)
    {
        if(!$prefix){
            return parent::query();
        }
        $instance = new self;
        $instance->table = 'fsdp_activity_list_'.$prefix;
        return $instance->newQuery();
    }


}
