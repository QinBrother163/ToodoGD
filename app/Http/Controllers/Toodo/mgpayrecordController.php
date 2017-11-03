<?php

namespace App\Http\Controllers\Toodo;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\mgpayrecord;

class mgpayrecordController extends Controller
{
    public function order(Request $request)
    {
        ini_set('memory_limit', '1024M');
        DB::connection()->disableQueryLog();

        $size = $request->input('size', 15, 10, 20);
        $begin = $request->input('begin');//开始
        $end = $request->input('end');//结束

        $amount_list = $request->input('amount_list');//金额
        $order_status = $request->input('order_status');//设备状态
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

        $amountNumber3 = '3';//金额长度
        $amountNumber2 = '2';
        $amountNumber1 = '1';

        $verifyStatus0 = '5';//设备状态 【0 飞奔】【1 拓捷】【10 不限】
        $verifyStatus0_0 = '0';//设备状态 【0 飞奔】【1 拓捷】【10 不限】
        $verifyStatus1 = '1';
        $verifyStatus10 = '10';


        if ($inputFacility && $order_status && $amount_list && ($begin && $end)) {

            $amount_arr_list = explode(',', $amount_list);
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            if (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '2') {

                if (count($amount_arr_list) == $amountNumber3) {

                    $arr_list_0 = $amount_arr_list[0];
                    $arr_list_1 = $amount_arr_list[1];
                    $arr_list_2 = $amount_arr_list[2];

                    if ($order_status == $verifyStatus0) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $verifyStatus0_0)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $verifyStatus0_0)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $verifyStatus0_0)->
                        where('devNO', $inputFacility)->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $order_status)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $order_status)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $order_status)->
                        where('devNO', $inputFacility)->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('devNO', $inputFacility)->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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
                } elseif (count($amount_arr_list) == $amountNumber2) {
                    $arr_list_0 = $amount_arr_list[0];
                    $arr_list_1 = $amount_arr_list[1];

                    if ($order_status == $verifyStatus0) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $verifyStatus0_0)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $verifyStatus0_0)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $verifyStatus0_0)->
                        where('devNO', $inputFacility)->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $order_status)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $order_status)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $order_status)->
                        where('devNO', $inputFacility)->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]))->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('devNO', $inputFacility)->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                } elseif (count($amount_arr_list) == $amountNumber1) {
                    $arr_list_0 = $amount_arr_list[0];

                    if ($order_status == $verifyStatus0) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $verifyStatus0_0)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $verifyStatus0_0)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $verifyStatus0_0)->
                        where('devNO', $inputFacility)->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $order_status)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $order_status)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]))->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $order_status)->
                        where('devNO', $inputFacility)->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0])->
                        where('devNO', $inputFacility)->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

            } else if (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '1') {

                if (count($amount_arr_list) == $amountNumber3) {

                    $arr_list_0 = $amount_arr_list[0];
                    $arr_list_1 = $amount_arr_list[1];
                    $arr_list_2 = $amount_arr_list[2];

                    if ($order_status == $verifyStatus0) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $verifyStatus0_0)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $verifyStatus0_0)->
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

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $order_status)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $order_status)->
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

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
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

                    }
                } elseif (count($amount_arr_list) == $amountNumber2) {
                    $arr_list_0 = $amount_arr_list[0];
                    $arr_list_1 = $amount_arr_list[1];

                    if ($order_status == $verifyStatus0) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $verifyStatus0_0)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $verifyStatus0_0)->
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

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $order_status)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $order_status)->
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

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
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

                    }

                } elseif (count($amount_arr_list) == $amountNumber1) {
                    $arr_list_0 = $amount_arr_list[0];

                    if ($order_status == $verifyStatus0) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $verifyStatus0_0)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $verifyStatus0_0)->
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

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $order_status)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $order_status)->
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

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0])->
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

                    }
                }

            } else if ($begin_list[0] . $begin_list[1] == $end_list[0] . $end_list[1]) {

                if (count($amount_arr_list) == $amountNumber3) {

                    $arr_list_0 = $amount_arr_list[0];
                    $arr_list_1 = $amount_arr_list[1];
                    $arr_list_2 = $amount_arr_list[2];

                    if ($order_status == $verifyStatus0) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $verifyStatus0_0)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $order_status)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;
                    }
                } elseif (count($amount_arr_list) == $amountNumber2) {
                    $arr_list_0 = $amount_arr_list[0];
                    $arr_list_1 = $amount_arr_list[1];

                    if ($order_status == $verifyStatus0) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $verifyStatus0_0)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $order_status)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;
                    }

                } elseif (count($amount_arr_list) == $amountNumber1) {
                    $arr_list_0 = $amount_arr_list[0];

                    if ($order_status == $verifyStatus0) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0])->
                        where('CPID', $verifyStatus0_0)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0])->
                        where('CPID', $order_status)->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0])->
                        where('devNO', $inputFacility)->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;
                    }
                }

            }

        } else if ($order_status && $amount_list && ($begin && $end)) {
            $amount_arr_list = explode(',', $amount_list);
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            if (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '2') {

                if (count($amount_arr_list) == $amountNumber3) {
                    $arr_list_0 = $amount_arr_list[0];
                    $arr_list_1 = $amount_arr_list[1];
                    $arr_list_2 = $amount_arr_list[2];

                    if ($order_status == $verifyStatus0) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $verifyStatus0_0)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $verifyStatus0_0)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $verifyStatus0_0)->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $order_status)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $order_status)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $order_status)->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                } elseif (count($amount_arr_list) == $amountNumber2) {
                    $arr_list_0 = $amount_arr_list[0];
                    $arr_list_1 = $amount_arr_list[1];

                    if ($order_status == $verifyStatus0) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $verifyStatus0_0)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $verifyStatus0_0)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $verifyStatus0_0)->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $order_status)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $order_status)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $order_status)->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                } elseif (count($amount_arr_list) == $amountNumber1) {
                    $arr_list_0 = $amount_arr_list[0];

                    if ($order_status == $verifyStatus0) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $verifyStatus0_0)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $verifyStatus0_0)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $verifyStatus0_0)->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $order_status)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $order_status)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $order_status)->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0])->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0])->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                        whereIn('price', [$arr_list_0])->
                        get();

                        $collection = collect([$paginate1, $paginate3, $paginate2]);
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

            } else if (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '1') {

                if (count($amount_arr_list) == $amountNumber3) {
                    $arr_list_0 = $amount_arr_list[0];
                    $arr_list_1 = $amount_arr_list[1];
                    $arr_list_2 = $amount_arr_list[2];

                    if ($order_status == $verifyStatus0) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $verifyStatus0_0)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $verifyStatus0_0)->
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

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $order_status)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $order_status)->
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

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
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

                    }

                } elseif (count($amount_arr_list) == $amountNumber2) {
                    $arr_list_0 = $amount_arr_list[0];
                    $arr_list_1 = $amount_arr_list[1];

                    if ($order_status == $verifyStatus0) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $verifyStatus0_0)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $verifyStatus0_0)->
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

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $order_status)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $order_status)->
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

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0, $arr_list_1])->
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

                    }

                } elseif (count($amount_arr_list) == $amountNumber1) {
                    $arr_list_0 = $amount_arr_list[0];

                    if ($order_status == $verifyStatus0) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $verifyStatus0_0)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $verifyStatus0_0)->
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

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $order_status)->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0])->
                        where('CPID', $order_status)->
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

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                        whereIn('price', [$arr_list_0])->
                        whereBetween('createDate', [$begin, $end])->
                        get();

                        $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                        whereIn('price', [$arr_list_0])->
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

                    }
                }

            } else if ($begin_list[0] . $begin_list[1] == $end_list[0] . $end_list[1]) {

                if (count($amount_arr_list) == $amountNumber3) {
                    $arr_list_0 = $amount_arr_list[0];
                    $arr_list_1 = $amount_arr_list[1];
                    $arr_list_2 = $amount_arr_list[2];

                    if ($order_status == $verifyStatus0) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $verifyStatus0_0)->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        where('CPID', $order_status)->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    }

                } elseif (count($amount_arr_list) == $amountNumber2) {
                    $arr_list_0 = $amount_arr_list[0];
                    $arr_list_1 = $amount_arr_list[1];

                    if ($order_status == $verifyStatus0) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $verifyStatus0_0)->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0, $arr_list_1])->
                        where('CPID', $order_status)->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0, $arr_list_1])->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;
                    }

                } elseif (count($amount_arr_list) == $amountNumber1) {
                    $arr_list_0 = $amount_arr_list[0];

                    if ($order_status == $verifyStatus0) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0])->
                        where('CPID', $verifyStatus0_0)->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    } elseif ($order_status == $verifyStatus1) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0])->
                        where('CPID', $order_status)->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;

                    } elseif ($order_status == $verifyStatus10) {

                        $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->whereIn('price', [$arr_list_0])->
                        whereBetween('createDate', [$begin, $end])->
                        paginate($size);
                        return $paginate;
                    }
                }

            }

        } elseif ($inputFacility && ($begin && $end)) {
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            if (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '2') {

                $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                where('devNO', $inputFacility)->
                whereBetween('createDate', [$begin, $end])->
                get();

                $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                where('devNO', $inputFacility)->
                whereBetween('createDate', [$begin, $end])->
                get();

                $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                where('devNO', $inputFacility)->
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

            }else if (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '1') {

                $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                where('devNO', $inputFacility)->
                whereBetween('createDate', [$begin, $end])->
                get();

                $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
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

            }else if ($begin_list[0] . $begin_list[1] == $end_list[0] . $end_list[1]) {

                $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                where('devNO', $inputFacility)->
                whereBetween('createDate', [$begin, $end])->
                paginate($size);
                return $paginate;

            }

        } else if ($inputFacility && $order_status && $amount_list) {
            $amount_arr_list = explode(',', $amount_list);

            if (count($amount_arr_list) == $amountNumber3) {

                $arr_list_0 = $amount_arr_list[0];
                $arr_list_1 = $amount_arr_list[1];
                $arr_list_2 = $amount_arr_list[2];

                if ($order_status == $verifyStatus0) {

                    $paginate = mgpayrecord::query($currentMonth)->whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                    where('CPID', $verifyStatus0_0)->
                    where('devNO', $inputFacility)->
                    paginate($size);
                    return $paginate;

                } elseif ($order_status == $verifyStatus1) {

                    $paginate = mgpayrecord::query($currentMonth)->whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                    where('CPID', $order_status)->
                    where('devNO', $inputFacility)->
                    paginate($size);
                    return $paginate;

                } elseif ($order_status == $verifyStatus10) {

                    $paginate = mgpayrecord::query($currentMonth)->whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                    where('devNO', $inputFacility)->
                    paginate($size);
                    return $paginate;
                }
            } elseif (count($amount_arr_list) == $amountNumber2) {

                $arr_list_0 = $amount_arr_list[0];
                $arr_list_1 = $amount_arr_list[1];

                if ($order_status == $verifyStatus0) {

                    $paginate = mgpayrecord::query($currentMonth)->whereIn('price', [$arr_list_0, $arr_list_1])->
                    where('CPID', $verifyStatus0_0)->
                    where('devNO', $inputFacility)->
                    paginate($size);
                    return $paginate;

                } elseif ($order_status == $verifyStatus1) {

                    $paginate = mgpayrecord::query($currentMonth)->whereIn('price', [$arr_list_0, $arr_list_1])->
                    where('CPID', $order_status)->
                    where('devNO', $inputFacility)->
                    paginate($size);
                    return $paginate;

                } elseif ($order_status == $verifyStatus10) {

                    $paginate = mgpayrecord::query($currentMonth)->whereIn('price', [$arr_list_0, $arr_list_1])->
                    where('devNO', $inputFacility)->
                    paginate($size);
                    return $paginate;
                }
            } elseif (count($amount_arr_list) == $amountNumber1) {

                $arr_list_0 = $amount_arr_list[0];

                if ($order_status == $verifyStatus0) {

                    $paginate = mgpayrecord::query($currentMonth)->whereIn('price', [$arr_list_0])->
                    where('CPID', $verifyStatus0_0)->
                    where('devNO', $inputFacility)->
                    paginate($size);
                    return $paginate;

                } elseif ($order_status == $verifyStatus1) {

                    $paginate = mgpayrecord::query($currentMonth)->whereIn('price', [$arr_list_0])->
                    where('CPID', $order_status)->
                    where('devNO', $inputFacility)->
                    paginate($size);
                    return $paginate;

                } elseif ($order_status == $verifyStatus10) {

                    $paginate = mgpayrecord::query($currentMonth)->whereIn('price', [$arr_list_0])->
                    where('devNO', $inputFacility)->
                    paginate($size);
                    return $paginate;
                }
            }


        } elseif ($order_status && $amount_list) {
            $amount_arr_list = explode(',', $amount_list);

            if (count($amount_arr_list) == $amountNumber3) {

                $arr_list_0 = $amount_arr_list[0];
                $arr_list_1 = $amount_arr_list[1];
                $arr_list_2 = $amount_arr_list[2];

                if ($order_status == $verifyStatus0) {

                    $paginate = mgpayrecord::query($currentMonth)->
                    whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                    where('CPID', $verifyStatus0_0)->
                    paginate($size);
                    return $paginate;

                } elseif ($order_status == $verifyStatus1) {

                    $paginate = mgpayrecord::query($currentMonth)->
                    whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                    where('CPID', $order_status)->
                    paginate($size);
                    return $paginate;

                } elseif ($order_status == $verifyStatus10) {

                    $paginate = mgpayrecord::query($currentMonth)->
                    whereIn('price', [$arr_list_0, $arr_list_1, $arr_list_2])->
                    paginate($size);
                    return $paginate;

                }

            } elseif (count($amount_arr_list) == $amountNumber2) {
                $arr_list_0 = $amount_arr_list[0];
                $arr_list_1 = $amount_arr_list[1];

                if ($order_status == $verifyStatus0) {

                    $paginate = mgpayrecord::query($currentMonth)->
                    whereIn('price', [$arr_list_0, $arr_list_1])->
                    where('CPID', $verifyStatus0_0)->
                    paginate($size);
                    return $paginate;

                } elseif ($order_status == $verifyStatus1) {

                    $paginate = mgpayrecord::query($currentMonth)->
                    whereIn('price', [$arr_list_0, $arr_list_1])->
                    where('CPID', $order_status)->
                    paginate($size);
                    return $paginate;

                } elseif ($order_status == $verifyStatus10) {

                    $paginate = mgpayrecord::query($currentMonth)->
                    whereIn('price', [$arr_list_0, $arr_list_1])->
                    paginate($size);
                    return $paginate;

                }
            } elseif (count($amount_arr_list) == $amountNumber1) {
                $arr_list_0 = $amount_arr_list[0];

                if ($order_status == $verifyStatus0) {

                    $paginate = mgpayrecord::query($currentMonth)->
                    whereIn('price', [$arr_list_0])->
                    where('CPID', $verifyStatus0_0)->
                    paginate($size);
                    return $paginate;

                } elseif ($order_status == $verifyStatus1) {

                    $paginate = mgpayrecord::query($currentMonth)->
                    whereIn('price', [$arr_list_0])->
                    where('CPID', $order_status)->
                    paginate($size);
                    return $paginate;

                } elseif ($order_status == $verifyStatus10) {

                    $paginate = mgpayrecord::query($currentMonth)->
                    whereIn('price', [$arr_list_0])->
                    paginate($size);
                    return $paginate;

                }
            }

        } elseif ($inputFacility) {

            $paginate = mgpayrecord::query($currentMonth)->where('devNO', $inputFacility)->
            paginate($size);
            return $paginate;

        } elseif ($order_status && ($begin && $end)) {

            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            if (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '2') {

                if ($order_status == $verifyStatus0) {

                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                    where('CPID', $verifyStatus0_0)->
                    whereBetween('createDate', [$begin, $end])->
                    get();

                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                    where('CPID', $verifyStatus0_0)->
                    whereBetween('createDate', [$begin, $end])->
                    get();

                    $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                    where('CPID', $verifyStatus0_0)->
                    get();

                    $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                } elseif ($order_status == $verifyStatus1) {

                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                    where('CPID', $order_status)->
                    whereBetween('createDate', [$begin, $end])->
                    get();

                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                    where('CPID', $order_status)->
                    whereBetween('createDate', [$begin, $end])->
                    get();

                    $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                    where('CPID', $order_status)->
                    get();

                    $collection = collect([$paginate1, $paginate3, $paginate2]);
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

                } elseif ($order_status == $verifyStatus10) {

                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                    whereBetween('createDate', [$begin, $end])->
                    get();

                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                    whereBetween('createDate', [$begin, $end])->
                    get();

                    $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                    get();

                    $collection = collect([$paginate1, $paginate3, $paginate2]);
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

            } else if (((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) == '1') {

                if ($order_status == $verifyStatus0) {

                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                    where('CPID', $verifyStatus0_0)->
                    whereBetween('createDate', [$begin, $end])->
                    get();

                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                    where('CPID', $verifyStatus0_0)->
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

                } elseif ($order_status == $verifyStatus1) {

                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                    where('CPID', $order_status)->
                    whereBetween('createDate', [$begin, $end])->
                    get();

                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                    where('CPID', $order_status)->
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

                } elseif ($order_status == $verifyStatus10) {

                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                    whereBetween('createDate', [$begin, $end])->
                    get();

                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
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
                }

            } else if ($begin_list[0] . $begin_list[1] == $end_list[0] . $end_list[1]) {

                if ($order_status == $verifyStatus0) {

                    $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                    where('CPID', $verifyStatus0_0)->
                    whereBetween('createDate', [$begin, $end])->
                    paginate($size);
                    return $paginate;

                } elseif ($order_status == $verifyStatus1) {

                    $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                    where('CPID', $order_status)->
                    whereBetween('createDate', [$begin, $end])->
                    paginate($size);
                    return $paginate;

                } elseif ($order_status == $verifyStatus10) {

                    $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                    whereBetween('createDate', [$begin, $end])->
                    paginate($size);
                    return $paginate;

                }
            }
        }

        $paginate = mgpayrecord::query($currentMonth)->paginate($size);
        return $paginate;
    }
}
