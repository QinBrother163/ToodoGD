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

        $currentMonth = date("Ym");

        function processingPaging2($paginate1, $paginate2, $size, $request)
    {

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

        function processingPaging3($paginate1, $paginate2, $paginate3, $size, $request)
        {

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

        }

        function query($currentMonth, $begin, $end, $inputFacility, $size)
        {
            $paginate = mgpayrecord::query($currentMonth)->
            orderBy('createDate')->
            paginate($size);
            return $paginate;
        }

        function query_date($currentMonth, $begin, $end, $inputFacility, $size, $request)
        {
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            $end_list = explode('-', $end);
            $begin_list = explode('-', $begin);

            switch ((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) {
                case 2:
                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                    whereBetween('createDate', [$begin, $end])->
                    get();

                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                    whereBetween('createDate', [$begin, $end])->
                    get();

                    $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                    get();

                    return processingPaging3($paginate1, $paginate3, $paginate2, $size, $request);
                    break;
                case 1:

                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                    whereBetween('createDate', [$begin, $end])->
                    get();

                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                    whereBetween('createDate', [$begin, $end])->
                    get();

                    return processingPaging2($paginate1, $paginate2, $size, $request);
                    break;
                default:
                    $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                    whereBetween('createDate', [$begin, $end])->
                    orderBy('createDate')->
                    paginate($size);
                    return $paginate;
                    break;
            }
        }

        function query_inputFacility($currentMonth, $begin, $end, $inputFacility, $size)
        {
            $paginate = mgpayrecord::query($currentMonth)->
            where('devNO', $inputFacility)->
            orderBy('createDate')->
            paginate($size);
            return $paginate;
        }

        function query_date_inputFacility($currentMonth, $begin, $end, $inputFacility, $size, $request)
        {
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            $end_list = explode('-', $end);
            $begin_list = explode('-', $begin);

            switch ((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) {
                case 2:
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

                    return processingPaging3($paginate1, $paginate3, $paginate2, $size, $request);
                    break;
                case 1:

                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                    where('devNO', $inputFacility)->
                    whereBetween('createDate', [$begin, $end])->
                    get();

                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                    where('devNO', $inputFacility)->
                    whereBetween('createDate', [$begin, $end])->
                    get();

                    return processingPaging2($paginate1, $paginate2, $size, $request);
                    break;
                default:

                    $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                    where('devNO', $inputFacility)->
                    whereBetween('createDate', [$begin, $end])->
                    orderBy('createDate')->
                    paginate($size);
                    return $paginate;
                    break;
            }
        }

        function query_order_status($currentMonth, $begin, $end, $order_status, $inputFacility, $size)
        {

            $verifyStatus0 = '0';

            switch ($order_status) {
                case 1:
                    $paginate = mgpayrecord::query($currentMonth)->
                    where('CPID', $order_status)->
                    orderBy('createDate')->
                    paginate($size);
                    return $paginate;
                    break;
                case 10:
                    $paginate = mgpayrecord::query($currentMonth)->
                    orderBy('createDate')->
                    paginate($size);
                    return $paginate;
                    break;
                default:
                    $paginate = mgpayrecord::query($currentMonth)->
                    where('CPID', $verifyStatus0)->
                    orderBy('createDate')->
                    paginate($size);
                    return $paginate;
                    break;
            }
        }

        function query_date_orderStatus($currentMonth, $begin, $end, $order_status, $inputFacility, $size, $request)
        {
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            $end_list = explode('-', $end);
            $begin_list = explode('-', $begin);

            $verifyStatus0 = '0';

            switch ((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) {
                case 2:
                    switch ($order_status) {
                        case 1:
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

                            return processingPaging3($paginate1, $paginate3, $paginate2, $size, $request);
                            break;
                        case 10:
                            $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                            whereBetween('createDate', [$begin, $end])->
                            get();

                            $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                            whereBetween('createDate', [$begin, $end])->
                            get();

                            $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                            get();

                            return processingPaging3($paginate1, $paginate3, $paginate2, $size, $request);
                            break;
                        default:
                            $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                            where('CPID', $verifyStatus0)->
                            whereBetween('createDate', [$begin, $end])->
                            get();

                            $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                            where('CPID', $verifyStatus0)->
                            whereBetween('createDate', [$begin, $end])->
                            get();

                            $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                            where('CPID', $verifyStatus0)->
                            get();
                            return processingPaging3($paginate1, $paginate3, $paginate2, $size, $request);
                            break;
                    }
                    break;
                case 1:
                    switch ($order_status) {
                        case 1:
                            $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                            where('CPID', $order_status)->
                            whereBetween('createDate', [$begin, $end])->
                            get();

                            $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                            where('CPID', $order_status)->
                            whereBetween('createDate', [$begin, $end])->
                            get();

                            return processingPaging2($paginate1, $paginate2, $size, $request);
                            break;
                        case 10:
                            $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                            whereBetween('createDate', [$begin, $end])->
                            get();

                            $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                            whereBetween('createDate', [$begin, $end])->
                            get();

                            return processingPaging2($paginate1, $paginate2, $size, $request);
                            break;
                        default:
                            $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                            where('CPID', $verifyStatus0)->
                            whereBetween('createDate', [$begin, $end])->
                            get();

                            $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                            where('CPID', $verifyStatus0)->
                            whereBetween('createDate', [$begin, $end])->
                            get();

                            return processingPaging2($paginate1, $paginate2, $size, $request);

                            break;
                    }
                    break;
                default:
                    switch ($order_status) {
                        case 1:
                            $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                            where('CPID', $order_status)->
                            whereBetween('createDate', [$begin, $end])->
                            orderBy('createDate')->
                            paginate($size);
                            return $paginate;
                            break;
                        case 10:
                            $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                            whereBetween('createDate', [$begin, $end])->
                            orderBy('createDate')->
                            paginate($size);
                            return $paginate;
                            break;
                        default:
                            $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                            where('CPID', $verifyStatus0)->
                            whereBetween('createDate', [$begin, $end])->
                            orderBy('createDate')->
                            paginate($size);
                            return $paginate;
                            break;
                    }

                    break;
            }
        }

        function query_amount_orderStatus($currentMonth, $begin, $end, $amount_list, $order_status, $inputFacility, $size)
        {

            $amount_arr = explode(',', $amount_list);

            $amountNumber3 = '3';//金额长度
            $amountNumber2 = '2';
            $amountNumber1 = '1';

            $verifyStatus0 = '0';

            switch ($order_status) {
                case 1:
                    switch (count($amount_arr)) {
                        case $amountNumber3:
                            $paginate = mgpayrecord::query($currentMonth)->
                            whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                            where('CPID', $order_status)->
                            orderBy('createDate')->
                            paginate($size);
                            return $paginate;
                            break;
                        case  $amountNumber2:
                            $paginate = mgpayrecord::query($currentMonth)->
                            whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                            where('CPID', $order_status)->
                            orderBy('createDate')->
                            paginate($size);
                            return $paginate;
                            break;
                        case  $amountNumber1:
                            $paginate = mgpayrecord::query($currentMonth)->
                            whereIn('price', [$amount_arr[0]])->
                            where('CPID', $order_status)->
                            orderBy('createDate')->
                            paginate($size);
                            return $paginate;
                            break;
                        default:
                            break;
                    }
                    break;
                case 10:
                    switch (count($amount_arr)) {
                        case $amountNumber3:
                            $paginate = mgpayrecord::query($currentMonth)->
                            whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                            orderBy('createDate')->
                            paginate($size);
                            return $paginate;
                            break;
                        case  $amountNumber2:
                            $paginate = mgpayrecord::query($currentMonth)->
                            whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                            orderBy('createDate')->
                            paginate($size);
                            return $paginate;
                            break;
                        case  $amountNumber1:
                            $paginate = mgpayrecord::query($currentMonth)->
                            whereIn('price', [$amount_arr[0]])->
                            orderBy('createDate')->
                            paginate($size);
                            return $paginate;
                            break;
                        default:
                            break;
                    }
                    break;
                default:
                    switch (count($amount_arr)) {
                        case $amountNumber3:
                            $paginate = mgpayrecord::query($currentMonth)->
                            whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                            where('CPID', $verifyStatus0)->
                            orderBy('createDate')->
                            paginate($size);
                            return $paginate;
                            break;
                        case  $amountNumber2:
                            $paginate = mgpayrecord::query($currentMonth)->
                            whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                            where('CPID', $verifyStatus0)->
                            orderBy('createDate')->
                            paginate($size);
                            return $paginate;
                            break;
                        case  $amountNumber1:
                            $paginate = mgpayrecord::query($currentMonth)->
                            whereIn('price', [$amount_arr[0]])->
                            where('CPID', $verifyStatus0)->
                            orderBy('createDate')->
                            paginate($size);
                            return $paginate;
                            break;
                        default:
                            break;
                    }
                    break;
            }
        }

        function query_date_amount_orderStatus($currentMonth, $begin, $end, $amount_list, $order_status, $inputFacility, $size, $request)
        {
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            $end_list = explode('-', $end);
            $begin_list = explode('-', $begin);

            $amount_arr = explode(',', $amount_list);

            $amountNumber3 = '3';//金额长度
            $amountNumber2 = '2';
            $amountNumber1 = '1';

            $verifyStatus0 = '0';

            switch ((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) {
                case 2:
                    switch ($order_status) {
                        case 1:
                            switch (count($amount_arr)) {
                                case $amountNumber3:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    where('CPID', $order_status)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    where('CPID', $order_status)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    where('CPID', $order_status)->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging3($paginate1, $paginate3, $paginate2, $size, $request);

                                    break;
                                case  $amountNumber2:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    where('CPID', $order_status)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    where('CPID', $order_status)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    where('CPID', $order_status)->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging3($paginate1, $paginate3, $paginate2, $size, $request);

                                    break;
                                case  $amountNumber1:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0]])->
                                    where('CPID', $order_status)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0]])->
                                    where('CPID', $order_status)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                                    whereIn('price', [$amount_arr[0]])->
                                    where('CPID', $order_status)->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging3($paginate1, $paginate3, $paginate2, $size, $request);
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 10:
                            switch (count($amount_arr)) {
                                case $amountNumber3:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging3($paginate1, $paginate3, $paginate2, $size, $request);
                                    break;
                                case  $amountNumber2:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging3($paginate1, $paginate3, $paginate2, $size, $request);
                                    break;
                                case  $amountNumber1:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0]])->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0]])->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                                    whereIn('price', [$amount_arr[0]])->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging3($paginate1, $paginate3, $paginate2, $size, $request);
                                    break;
                                default:
                                    break;
                            }
                            break;
                        default:
                            switch (count($amount_arr)) {
                                case $amountNumber3:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    where('CPID', $verifyStatus0)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    where('CPID', $verifyStatus0)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    where('CPID', $verifyStatus0)->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging3($paginate1, $paginate3, $paginate2, $size, $request);
                                    break;
                                case  $amountNumber2:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    where('CPID', $verifyStatus0)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    where('CPID', $verifyStatus0)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    where('CPID', $verifyStatus0)->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging3($paginate1, $paginate3, $paginate2, $size, $request);
                                    break;
                                case  $amountNumber1:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0]])->
                                    where('CPID', $verifyStatus0)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0]])->
                                    where('CPID', $verifyStatus0)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate3 = mgpayrecord::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                                    whereIn('price', [$amount_arr[0]])->
                                    where('CPID', $verifyStatus0)->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging3($paginate1, $paginate3, $paginate2, $size, $request);
                                    break;
                                default:
                                    break;
                            }
                            break;
                    }
                    break;
                case 1:
                    switch ($order_status) {
                        case 1:
                            switch (count($amount_arr)) {
                                case $amountNumber3:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    where('CPID', $order_status)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    where('CPID', $order_status)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging2($paginate1, $paginate2, $size, $request);

                                    break;
                                case  $amountNumber2:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    where('CPID', $order_status)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    where('CPID', $order_status)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging2($paginate1, $paginate2, $size, $request);

                                    break;
                                case  $amountNumber1:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0]])->
                                    where('CPID', $order_status)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0]])->
                                    where('CPID', $order_status)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging2($paginate1, $paginate2, $size, $request);
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 10:
                            switch (count($amount_arr)) {
                                case $amountNumber3:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging2($paginate1, $paginate2, $size, $request);
                                    break;
                                case  $amountNumber2:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging2($paginate1, $paginate2, $size, $request);
                                    break;
                                case  $amountNumber1:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0]])->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0]])->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging2($paginate1, $paginate2, $size, $request);
                                    break;
                                default:
                                    break;
                            }
                            break;
                        default:
                            switch (count($amount_arr)) {
                                case $amountNumber3:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    where('CPID', $verifyStatus0)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    where('CPID', $verifyStatus0)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging2($paginate1, $paginate2, $size, $request);
                                    break;
                                case  $amountNumber2:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    where('CPID', $verifyStatus0)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    where('CPID', $verifyStatus0)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging2($paginate1, $paginate2, $size, $request);
                                    break;
                                case  $amountNumber1:
                                    $paginate1 = mgpayrecord::query($begin_list[0] . $begin_list[1])->
                                    whereIn('price', [$amount_arr[0]])->
                                    where('CPID', $verifyStatus0)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    $paginate2 = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0]])->
                                    where('CPID', $verifyStatus0)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    get();

                                    return processingPaging2($paginate1, $paginate2, $size, $request);
                                    break;
                                default:
                                    break;
                            }
                            break;
                    }
                    break;
                default:
                    switch ($order_status) {
                        case 1:
                            switch (count($amount_arr)) {
                                case $amountNumber3:
                                    $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    where('CPID', $order_status)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                case  $amountNumber2:
                                    $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    where('CPID', $order_status)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                case  $amountNumber1:
                                    $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0]])->
                                    where('CPID', $order_status)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 10:
                            switch (count($amount_arr)) {
                                case $amountNumber3:
                                    $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                case  $amountNumber2:
                                    $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                case  $amountNumber1:
                                    $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0]])->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                default:
                                    break;
                            }
                            break;
                        default:
                            switch (count($amount_arr)) {
                                case $amountNumber3:
                                    $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1], $amount_arr[2]])->
                                    where('CPID', $verifyStatus0)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                case  $amountNumber2:
                                    $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0], $amount_arr[1]])->
                                    where('CPID', $verifyStatus0)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                case  $amountNumber1:
                                    $paginate = mgpayrecord::query($end_list[0] . $end_list[1])->
                                    whereIn('price', [$amount_arr[0]])->
                                    where('CPID', $verifyStatus0)->
                                    whereBetween('createDate', [$begin, $end])->
                                    orderBy('createDate')->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                default:
                                    break;
                            }
                            break;
                    }
                    break;
            }
        }

        if ($begin && $end && $amount_list && $order_status) {
            return query_date_amount_orderStatus($currentMonth, $begin, $end, $amount_list, $order_status, $inputFacility, $size, $request);
        } elseif ($begin && $end && $order_status) {
            return query_date_orderStatus($currentMonth, $begin, $end, $order_status, $inputFacility, $size, $request);
        } elseif ($begin && $end && $inputFacility) {
            return query_date_inputFacility($currentMonth, $begin, $end, $inputFacility, $size, $request);
        } elseif ($amount_list && $order_status) {
            return query_amount_orderStatus($currentMonth, $begin, $end, $amount_list, $order_status, $inputFacility, $size);
        } elseif ($begin && $end) {
            return query_date($currentMonth, $begin, $end, $inputFacility, $size, $request);
        } elseif ($inputFacility) {
            return query_inputFacility($currentMonth, $begin, $end, $inputFacility, $size);
        } elseif ($order_status) {
            return query_order_status($currentMonth, $begin, $end, $order_status, $inputFacility, $size);
        }

        return query($currentMonth, $begin, $end, $inputFacility, $size);

    }
}
