<?php

namespace App\Http\Controllers\Toodo;

use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Pagination\Paginator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\GdOrder;

class GdOrderController extends Controller
{
    public function order(Request $request)
    {
        ini_set('memory_limit', '1024M');
        DB::connection()->disableQueryLog();

        $size = $request->input('size', 15, 10, 20);

        $begin = $request->input('begin');//开始
        $end = $request->input('end');//结束
        $inputFacility = $request->input('inputFacility');
        $amount_list = $request->input('amount_list');
        $order_status = $request->input('order_status');

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

        $verifyStatus0 = '5';
        $verifyStatus0_0 = '0';
        $verifyStatus1 = '1';
        $verifyStatus10 = '10';

        $amount_than19800 = '19800';
        $amount_than19800_2 = '19800';
        $amount_than10 = '10';

        if ( $order_status && $amount_list && ($begin && $end) ){
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            if (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '2') {

                if($amount_list == $amount_than19800){

                    if($order_status == $verifyStatus0){

                        $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                        whereIn('fee', [$amount_list])->
                        where('state', $verifyStatus0_0)->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                        whereIn('fee', [$amount_list])->
                        where('state', $verifyStatus0_0)->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('fee', [$amount_list])->
                        where('state', $verifyStatus0_0)->
                        get();

                        $collection = collect([$paginate1,$paginate3, $paginate2]);
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

                    }elseif($order_status == $verifyStatus1){

                        $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                        whereIn('fee', [$amount_list])->
                        where('state', $order_status)->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                        whereIn('fee', [$amount_list])->
                        where('state', $order_status)->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('fee', [$amount_list])->
                        where('state', $order_status)->
                        get();

                        $collection = collect([$paginate1, $paginate3,$paginate2]);
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

                    }elseif($order_status == $verifyStatus10){

                        $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                        whereIn('fee', [$amount_list])->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                        whereIn('fee', [$amount_list])->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('fee', [$amount_list])->
                        get();

                        $collection = collect([$paginate1, $paginate3,$paginate2]);
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
                    }

                }elseif ($amount_list == $amount_than10){

                    if($order_status == $verifyStatus0){

                        $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                        where('fee', '<',$amount_than19800_2)->
                        where('state', $verifyStatus0_0)->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                        where('fee', '<',$amount_than19800_2)->
                        where('state', $verifyStatus0_0)->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        where('fee', '<',$amount_than19800_2)->
                        where('state', $verifyStatus0_0)->
                        get();

                        $collection = collect([$paginate1, $paginate3,$paginate2]);
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

                    }elseif($order_status == $verifyStatus1){

                        $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                        where('fee', '<',$amount_than19800_2)->
                        where('state', $order_status)->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                        where('fee', '<',$amount_than19800_2)->
                        where('state', $order_status)->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        where('fee', '<',$amount_than19800_2)->
                        where('state', $order_status)->
                        get();

                        $collection = collect([$paginate1, $paginate3,$paginate2]);
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

                    }elseif($order_status == $verifyStatus10){

                        $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                        where('fee', '<',$amount_than19800_2)->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                        where('fee', '<',$amount_than19800_2)->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        where('fee', '<',$amount_than19800_2)->
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
                    }
                }

            }else if (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '1') {

                if($amount_list == $amount_than19800){

                    if($order_status == $verifyStatus0){

                        $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                        whereIn('fee', [$amount_list])->
                        where('state', $verifyStatus0_0)->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                        whereIn('fee', [$amount_list])->
                        where('state', $verifyStatus0_0)->
                        whereBetween('date', [$begin, $end])->
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

                    }elseif($order_status == $verifyStatus1){

                        $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                        whereIn('fee', [$amount_list])->
                        where('state', $order_status)->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                        whereIn('fee', [$amount_list])->
                        where('state', $order_status)->
                        whereBetween('date', [$begin, $end])->
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

                    }elseif($order_status == $verifyStatus10){

                        $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                        whereIn('fee', [$amount_list])->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                        whereIn('fee', [$amount_list])->
                        whereBetween('date', [$begin, $end])->
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
                    }

                }elseif ($amount_list == $amount_than10){

                    if($order_status == $verifyStatus0){

                        $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                        where('fee', '<',$amount_than19800_2)->
                        where('state', $verifyStatus0_0)->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                        where('fee', '<',$amount_than19800_2)->
                        where('state', $verifyStatus0_0)->
                        whereBetween('date', [$begin, $end])->
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

                    }elseif($order_status == $verifyStatus1){

                        $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                        where('fee', '<',$amount_than19800_2)->
                        where('state', $order_status)->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                        where('fee', '<',$amount_than19800_2)->
                        where('state', $order_status)->
                        whereBetween('date', [$begin, $end])->
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

                    }elseif($order_status == $verifyStatus10){

                        $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                        where('fee', '<',$amount_than19800_2)->
                        whereBetween('date', [$begin, $end])->
                        get();

                        $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                        where('fee', '<',$amount_than19800_2)->
                        whereBetween('date', [$begin, $end])->
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
                    }
                }

            }else if ($begin_list[0] . $begin_list[1] == $end_list[0] . $end_list[1]){

                if($amount_list == $amount_than19800){

                    if($order_status == $verifyStatus0){

                        $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                        whereIn('fee', [$amount_list])->
                        where('state', $verifyStatus0_0)->
                        whereBetween('date', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    }elseif($order_status == $verifyStatus1){

                        $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                        whereIn('fee', [$amount_list])->
                        where('state', $order_status)->
                        whereBetween('date', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    }elseif($order_status == $verifyStatus10){

                        $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                        whereIn('fee', [$amount_list])->
                        whereBetween('date', [$begin, $end])->
                        paginate($size);
                        return $paginate;
                    }

                }elseif ($amount_list == $amount_than10){

                    if($order_status == $verifyStatus0){

                        $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                        where('fee', '<',$amount_than19800_2)->
                        where('state', $verifyStatus0_0)->
                        whereBetween('date', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    }elseif($order_status == $verifyStatus1){

                        $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                        where('fee', '<',$amount_than19800_2)->
                        where('state', $order_status)->
                        whereBetween('date', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    }elseif($order_status == $verifyStatus10){

                        $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                        where('fee', '<',$amount_than19800_2)->
                        whereBetween('date', [$begin, $end])->
                        paginate($size);
                        return $paginate;
                    }
                }
            }

        }else if ($order_status && ($begin && $end)){

            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            if (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '2') {

                if($order_status == $verifyStatus0){

                    $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                    where('state', $verifyStatus0_0)->
                    whereBetween('date', [$begin, $end])->
                    get();

                    $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                    where('state', $verifyStatus0_0)->
                    whereBetween('date', [$begin, $end])->
                    get();

                    $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                    where('state', $verifyStatus0_0)->
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

                }elseif($order_status == $verifyStatus1){

                    $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                    where('state', $order_status)->
                    whereBetween('date', [$begin, $end])->
                    get();

                    $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                    where('state', $order_status)->
                    whereBetween('date', [$begin, $end])->
                    get();

                    $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                    where('state', $order_status)->
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

                }elseif($order_status == $verifyStatus10){

                    $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                    whereBetween('date', [$begin, $end])->
                    get();

                    $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                    whereBetween('date', [$begin, $end])->
                    get();

                    $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                    get();

                    $collection = collect([$paginate1,$paginate3 , $paginate2]);
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

                }

            }else if (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '1') {

                if($order_status == $verifyStatus0){

                    $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                    where('state', $verifyStatus0_0)->
                    whereBetween('date', [$begin, $end])->
                    get();

                    $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                    where('state', $verifyStatus0_0)->
                    whereBetween('date', [$begin, $end])->
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

                }elseif($order_status == $verifyStatus1){

                    $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                    where('state', $order_status)->
                    whereBetween('date', [$begin, $end])->
                    get();

                    $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                    where('state', $order_status)->
                    whereBetween('date', [$begin, $end])->
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

                }elseif($order_status == $verifyStatus10){

                    $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                    whereBetween('date', [$begin, $end])->
                    get();

                    $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                    whereBetween('date', [$begin, $end])->
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

                }

            }else if ($begin_list[0] . $begin_list[1] == $end_list[0] . $end_list[1]){

                if($order_status == $verifyStatus0){

                    $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                    where('state', $verifyStatus0_0)->
                    whereBetween('date', [$begin, $end])->
                    paginate($size);
                    return $paginate;

                }elseif($order_status == $verifyStatus1){

                    $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                    where('state', $order_status)->
                    whereBetween('date', [$begin, $end])->
                    paginate($size);
                    return $paginate;

                }elseif($order_status == $verifyStatus10){

                    $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                    whereBetween('date', [$begin, $end])->
                    paginate($size);
                    return $paginate;
                }
            }
        }
        $paginate = GdOrder::query($currentMonth)->paginate($size);
        return $paginate;
    }
}
