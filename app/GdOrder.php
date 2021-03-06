<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GdOrder extends Model
{

    protected $fillable = [
        'order_id',
        'user_id',
        'app_type',
        'app_id',
        'currency_type',
        'fee',
        'date',
        'state',
        'event',
        'title',
        'real_name',
        'phone',
        'address',
        'update_time'
    ];

    protected $table = 'order_201710';
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
