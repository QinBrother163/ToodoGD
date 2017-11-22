<?php

namespace App\Http\Controllers\Toodo;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\GDConsumeLog;

class GDConsumeLogController extends Controller
{
    public function order(Request $request)
    {
        ini_set('memory_limit', '1024M');
        DB::connection()->disableQueryLog();

        $size = $request->input('size', 15, 10, 20);

        $begin = $request->input('begin');//开始
        $end = $request->input('end');//结束

        $currentMonth = date("Ym");
        $end_list = explode('-', $end);
        $begin_list = explode('-', $begin);

        if($begin && $end){

            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            $paginate = GDConsumeLog::query("gd")->
            whereBetween('time', [$begin, $end])->
            orderBy('time')->
            paginate($size);
            return $paginate;

        }


        $paginate = GDConsumeLog::query("gd")->
        orderBy('time')->
        paginate($size);
        return $paginate;
    }
}
