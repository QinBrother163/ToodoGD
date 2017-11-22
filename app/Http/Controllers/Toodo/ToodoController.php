<?php

namespace App\Http\Controllers\Toodo;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\activity;
use Illuminate\Support\Facades\Cache;

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

        $currentMonth = date("Ym");


        function query($currentMonth, $begin, $end, $inputFacility, $size)
        {
            $paginate = activity::query($currentMonth)->
            orderBy('createDate')->
            paginate($size);
            return $paginate;
        }

        function query_date($begin, $end, $inputFacility, $size, $request)
        {
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));
            $end_list = explode('-', $end);
            $begin_list = explode('-', $begin);


            switch ((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) {
                case 1:
                    $pag1_date = $begin_list[0] . $begin_list[1];
                    $paginate1 = activity::query($pag1_date)->
                    whereBetween('createDate', [$begin, $end])->
                    orderBy('createDate')->
                    get();

                    $pag2_date = $end_list[0] . $end_list[1];
                    $paginate2 = activity::query($pag2_date)->
                    whereBetween('createDate', [$begin, $end])->
                    orderBy('createDate')->
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
                    break;
                case 2:
                    $pag1_date = $begin_list[0] . $begin_list[1];
                    $paginate1 = activity::query($pag1_date)->
                    whereBetween('createDate', [$begin, $end])->
                    orderBy('createDate')->
                    get();

                    $pag2_date = $end_list[0] . $end_list[1];
                    $paginate2 = activity::query($pag2_date)->
                    whereBetween('createDate', [$begin, $end])->
                    orderBy('createDate')->
                    get();

                    $pag3_date = (int)($begin_list[0] . $begin_list[1]) + 1;
                    $paginate3 = activity::query($pag3_date)->
                    orderBy('createDate')->
                    get();

                    $collection = collect([$paginate1, $paginate3 ,$paginate2]);
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
                    break;
                default:
                    $date_AMonth = $begin_list[0] . $begin_list[1];
                    $paginate = activity::query($date_AMonth)->
                    whereBetween('createDate', [$begin, $end])->
                    orderBy('createDate')->
                    paginate($size);
                    return $paginate;
                    break;
            }
        }

        function query_inputFacility($currentMonth, $begin, $end, $inputFacility, $size)
        {
            $paginate = activity::query($currentMonth)->
            where('devNO', $inputFacility)->
            orderBy('createDate')->
            paginate($size);
            return $paginate;
        }

        function query_date_inputFacility($begin, $end, $inputFacility, $size, $request)
        {
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));
            $end_list = explode('-', $end);
            $begin_list = explode('-', $begin);


            switch ((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) {
                case 1:
                    $pag1_date = $begin_list[0] . $begin_list[1];
                    $paginate1 = activity::query($pag1_date)->
                    where('devNO', $inputFacility)->
                    whereBetween('createDate', [$begin, $end])->
                    orderBy('createDate')->
                    get();

                    $pag2_date = $end_list[0] . $end_list[1];
                    $paginate2 = activity::query($pag2_date)->
                    where('devNO', $inputFacility)->
                    whereBetween('createDate', [$begin, $end])->
                    orderBy('createDate')->
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
                    break;
                case 2:
                    $pag1_date = $begin_list[0] . $begin_list[1];
                    $paginate1 = activity::query($pag1_date)->
                    where('devNO', $inputFacility)->
                    whereBetween('createDate', [$begin, $end])->
                    orderBy('createDate')->
                    get();

                    $pag2_date = $end_list[0] . $end_list[1];
                    $paginate2 = activity::query($pag2_date)->
                    where('devNO', $inputFacility)->
                    whereBetween('createDate', [$begin, $end])->
                    orderBy('createDate')->
                    get();

                    $pag3_date = (int)($begin_list[0] . $begin_list[1]) + 1;
                    $paginate3 = activity::query($pag3_date)->
                    orderBy('createDate')->
                    get();

                    $collection = collect([$paginate1, $paginate3 ,$paginate2]);
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
                    break;
                default:
                    $date_AMonth = $begin_list[0] . $begin_list[1];
                    $paginate = activity::query($date_AMonth)->
                    where('devNO', $inputFacility)->
                    whereBetween('createDate', [$begin, $end])->
                    orderBy('createDate')->
                    paginate($size);
                    return $paginate;
                    break;
            }
        }

        if ($begin && $end && $inputFacility){
            return query_date_inputFacility($begin, $end, $inputFacility, $size, $request);
        }elseif ($begin && $end) {
            return query_date($begin, $end, $inputFacility, $size, $request);
        }elseif ($inputFacility){
            return query_inputFacility($currentMonth, $begin, $end, $inputFacility, $size);
        }

        return query($currentMonth, $begin, $end, $inputFacility, $size);


//        $value = Cache::remember('fsdp_activity_list_201710', 30, function() {
//
//            $users = DB::table('fsdp_activity_list_201708');
//            $users1 = DB::table('fsdp_activity_list_201709');
//            return $users2 = DB::table('fsdp_activity_list_201710')->union($users1)->union($users)->get();
//
//        });
//
//        if ($request->has('page')) {
//            $current_page = $request->input('page');
//            $current_page = $current_page <= 0 ? 1 : $current_page;
//        } else {
//            $current_page = 1;
//        }
//        $item = array_slice(json_decode(json_encode($value), true), ($current_page - 1) * $size, $size); //注释1
//        $total = count($value);
//
//        $paginator = new LengthAwarePaginator($item, $total, $size, $current_page, [
//            'path' => Paginator::resolveCurrentPath(),  //注释2
//            'pageName' => 'page',
//        ]);
//        return $paginator;

    }
}
