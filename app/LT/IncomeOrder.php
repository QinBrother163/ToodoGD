<?php

namespace App\LT;

use Illuminate\Database\Eloquent\Model;

class IncomeOrder extends Model
{
    protected $connection = 'mysql6';
    protected $table = 'orderdata';
    public $primaryKey = 'Id';
    public $timestamps = false;

    public static function query($prefix = null)
    {
        if(!$prefix){
            return parent::query();
        }
        $instance = new self;
        $instance->table = $prefix;
        return $instance->newQuery();
    }



}
