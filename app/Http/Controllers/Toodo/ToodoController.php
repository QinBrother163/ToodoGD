<?php

namespace App\Http\Controllers\Toodo;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\activity;

class ToodoController extends Controller
{
    public function order(Request $request)
    {

        ini_set('memory_limit', '1024M');
        DB::connection()->disableQueryLog();

        $size = $request->input('size', 15, 10, 20);

        $begin = $request->input('begin');//开始
        $end = $request->input('end');//结束
        $inputFacility = $request->input('inputFacility');//输入设备

        $tmp_date = date("Ym");
        //切割出年份
        $tmp_year = substr($tmp_date, 0, 4);
        //切割出月份
        $tmp_mon = substr($tmp_date, 4, 2);
        $lastLastMonth = mktime(0, 0, 0, $tmp_mon - 2, 1, $tmp_year);
        $lastMonth = mktime(0, 0, 0, $tmp_mon - 1, 1, $tmp_year);
        $lastLastMonth = date("Ym", $lastLastMonth);
        $lastMonth = date("Ym", $lastMonth);
        $currentMonth = date("Ym");
        $end_list = explode('-', $end);
        $begin_list = explode('-', $begin);

        if ($inputFacility && ($begin && $end)) {
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            if (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '1') {

                $paginate1 = activity::query($begin_list[0] . $begin_list[1])->
                where('devNO', $inputFacility)->
                whereBetween('createDate', [$begin, $end])->
                get();

                $paginate2 = activity::query($end_list[0] . $end_list[1])->
                where('devNO', $inputFacility)->
                whereBetween('createDate', [$begin, $end])->
                get();

                $collection = collect([$paginate1, $paginate2]);
                $collapsed = $collection->collapse();
                $collapsed->all();

                if ($request->has('page')) {
                    $current_page = $request->input('page');
                    $current_page = $current_page <= 0 ? 1 : $current_page;
                } else {
                    $current_page = 1;
                }
                $item = array_slice(json_decode(json_encode($collapsed), true), ($current_page - 1) * $size, $size); //注释1
                $total = count($collapsed);

                $paginator = new LengthAwarePaginator($item, $total, $size, $current_page, [
                    'path' => Paginator::resolveCurrentPath(),  //注释2
                    'pageName' => 'page',
                ]);
                return $paginator;

            }elseif (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '2'){

                $paginate1 = activity::query($begin_list[0] . $begin_list[1])->
                where('devNO', $inputFacility)->
                whereBetween('createDate', [$begin, $end])->
                get();

                $paginate2 = activity::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                where('devNO', $inputFacility)->
                get();

                $paginate3 = activity::query($end_list[0] . $end_list[1])->
                where('devNO', $inputFacility)->
                whereBetween('createDate', [$begin, $end])->
                get();

                $collection = collect([$paginate1, $paginate2, $paginate3]);
                $collapsed = $collection->collapse();
                $collapsed->all();

                if ($request->has('page')) {
                    $current_page = $request->input('page');
                    $current_page = $current_page <= 0 ? 1 : $current_page;
                } else {
                    $current_page = 1;
                }
                $item = array_slice(json_decode(json_encode($collapsed), true), ($current_page - 1) * $size, $size); //注释1
                $total = count($collapsed);

                $paginator = new LengthAwarePaginator($item, $total, $size, $current_page, [
                    'path' => Paginator::resolveCurrentPath(),  //注释2
                    'pageName' => 'page',
                ]);
                return $paginator;

            }elseif ($begin_list[0] . $begin_list[1] == $end_list[0] . $end_list[1]) {

                $paginate = activity::query($end_list[0] . $end_list[1])->where('devNO', $inputFacility)->
                whereBetween('createDate', [$begin, $end])->
                paginate($size);
                return $paginate;

            }

        } elseif ($begin && $end) {
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            if (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '1') {

                $paginate1 = activity::query($begin_list[0] . $begin_list[1])->orderBy('createDate')->
                whereBetween('createDate', [$begin, $end])->get();

                $paginate2 = activity::query($end_list[0] . $end_list[1])->orderBy('createDate')->
                whereBetween('createDate', [$begin, $end])->get();

                $collection = collect([$paginate1, $paginate2]);
                $collapsed = $collection->collapse();
                $collapsed->all();

                if ($request->has('page')) {
                    $current_page = $request->input('page');
                    $current_page = $current_page <= 0 ? 1 : $current_page;
                } else {
                    $current_page = 1;
                }
                $item = array_slice(json_decode(json_encode($collapsed), true), ($current_page - 1) * $size, $size); //注释1
                $total = count($collapsed);

                $paginator = new LengthAwarePaginator($item, $total, $size, $current_page, [
                    'path' => Paginator::resolveCurrentPath(),  //注释2
                    'pageName' => 'page',
                ]);
                return $paginator;

            }elseif (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '2'){

                $paginate1 = activity::query($begin_list[0] . $begin_list[1])->orderBy('createDate')->
                whereBetween('createDate', [$begin, $end])->get();

                $paginate2 = activity::query((int)($begin_list[0] . $begin_list[1]) + 1)->orderBy('createDate')->get();

                $paginate3 = activity::query($end_list[0] . $end_list[1])->orderBy('createDate')->
                whereBetween('createDate', [$begin, $end])->get();

                $collection = collect([$paginate1, $paginate2, $paginate3]);
                $collapsed = $collection->collapse();
                $collapsed->all();

                if ($request->has('page')) {
                    $current_page = $request->input('page');
                    $current_page = $current_page <= 0 ? 1 : $current_page;
                } else {
                    $current_page = 1;
                }
                $item = array_slice(json_decode(json_encode($collapsed), true), ($current_page - 1) * $size, $size); //注释1
                $total = count($collapsed);

                $paginator = new LengthAwarePaginator($item, $total, $size, $current_page, [
                    'path' => Paginator::resolveCurrentPath(),  //注释2
                    'pageName' => 'page',
                ]);
                return $paginator;

            }else if ($begin_list[0] . $begin_list[1] == $end_list[0] . $end_list[1]) {

                $paginate = activity::query($end_list[0] . $end_list[1])->whereBetween('createDate', [$begin, $end])->
                paginate($size);
                return $paginate;

            }

        } elseif ($inputFacility) {
            $paginate = activity::query($currentMonth)->where('devNO', $inputFacility)->
            paginate($size);
            return $paginate;
        }

//        $paginate = TdoOrderData::orderBy('OrderNo')->paginate($size);
//        $paginate1 = activity::query($lastLastMonth)->where('id','<=', '6000');
//        $paginate2 = activity::query($lastMonth)->where('id', '<=', '6000')->union($paginate1)->get();

        $paginate = activity::query($currentMonth)->paginate($size);
        return $paginate;
    }
}
