<?php

namespace App\Http\Controllers\Toodo;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\LT\IncomeOrder;

class ToodoGDController extends Controller
{
    public function order(Request $request)
    {
        ini_set('memory_limit', '1024M');
        DB::connection()->disableQueryLog();

        $size = $request->input('size', 15, 10, 20);
        $begin = $request->input('begin');//开始
        $end = $request->input('end');//结束
        $amount_list = $request->input('amount_list');//金额order_status
        $state_order = $request->input('state_order');
        $currentMonth = date("Ym");
        $table = "orderdata";
        $end_list = explode('-', $end);
        $begin_list = explode('-', $begin);


        function processingPaging1($paginate, $size, $request)
        {

            $collection = collect([$paginate]);
            $collapsed = $collection->collapse();
            $collapsed->all();

            if ($request->has('page')) {
                $current_page = $request->input('page');
                $current_page = $current_page <= 0 ? 1 : $current_page;
            } else {
                $current_page = 1;
            }
            $item = array_slice(json_decode(json_encode($paginate), true), ($current_page - 1) * $size, $size); //注释1
            $total = count($paginate);

            $paginator = new LengthAwarePaginator($item, $total, $size, $current_page, [
                'path' => Paginator::resolveCurrentPath(),  //注释2
                'pageName' => 'page',
            ]);
            return $paginator;

        }


        function query($table, $begin, $end, $amount_list, $state_order, $size, $request)
        {
//            $paginate = IncomeOrder::query($table)->
//            select(['OrderNo','ShopId','UserId','Signature','Amount','PayStatus','CreateDate','DeviceId'])->
//            get();
//
//            return processingPaging1($paginate, $size, $request);


            $paginate = IncomeOrder::query($table)->
            orderBy('CreateDate', 'DESC')->
            paginate($size);
            return $paginate;
        }

        function query_date($table, $begin, $end, $amount_list, $state_order, $size)
        {
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            $paginate = IncomeOrder::query($table)->
            whereBetween('CreateDate', [$begin, $end])->
            orderBy('CreateDate', 'DESC')->
            paginate($size);
            return $paginate;
        }

        function query_state_order($table, $begin, $end, $amount_list, $state_order, $size)
        {
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));
            $order_5 = '5';//支付状态 【5 已成功】【1 未成功】
            $order_1 = '1';

            switch ($state_order ) {
                case $order_5:
                    $paginate = IncomeOrder::query($table)->
                    where('PayStatus', $state_order)->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
                case $order_1:
                    $paginate = IncomeOrder::query($table)->
                    where('PayStatus', $state_order)->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
                default:
                    $paginate = IncomeOrder::query($table)->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
            }
        }

        function query_date_stateOrder($table, $begin, $end, $amount_list, $state_order, $size)
        {
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));
            $order_5 = '5';//支付状态 【5 已成功】【1 未成功】
            $order_1 = '1';

            switch ($state_order) {
                case $order_5:
                    $paginate = IncomeOrder::query($table)->
                    where('PayStatus', $state_order)->
                    whereBetween('CreateDate', [$begin, $end])->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
                case $order_1:
                    $paginate = IncomeOrder::query($table)->
                    where('PayStatus', $state_order)->
                    whereBetween('CreateDate', [$begin, $end])->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
                default:
                    $paginate = IncomeOrder::query($table)->
                    whereBetween('CreateDate', [$begin, $end])->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
            }
        }

        function query_amount($table, $begin, $end, $amount_list, $state_order, $size)
        {
            $amount_arr_list = explode(' ', $amount_list);

            $amountNumber5 = '5';
            $amountNumber4 = '4';
            $amountNumber3 = '3';
            $amountNumber2 = '2';
            $amountNumber1 = '1';

            switch (count($amount_arr_list)) {
                case $amountNumber5:
                    $paginate = IncomeOrder::query($table)->
                    whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3], $amount_arr_list[4]])->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
                case $amountNumber4:
                    $paginate = IncomeOrder::query($table)->
                    whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3]])->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
                case $amountNumber3:
                    $paginate = IncomeOrder::query($table)->
                    whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2]])->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
                case $amountNumber2:
                    $paginate = IncomeOrder::query($table)->
                    whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1]])->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
                case $amountNumber1:
                    $paginate = IncomeOrder::query($table)->
                    whereIn('Amount', [$amount_arr_list[0]])->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
                default:
                    break;
            }
        }

        function query_date_amount($table, $begin, $end, $amount_list, $state_order, $size)
        {
            $amount_arr_list = explode(' ', $amount_list);
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            $amountNumber5 = '5';
            $amountNumber4 = '4';
            $amountNumber3 = '3';
            $amountNumber2 = '2';
            $amountNumber1 = '1';

            switch (count($amount_arr_list)) {
                case $amountNumber5:
                    $paginate = IncomeOrder::query($table)->
                    whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3], $amount_arr_list[4]])->
                    whereBetween('CreateDate', [$begin, $end])->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
                case $amountNumber4:
                    $paginate = IncomeOrder::query($table)->
                    whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3]])->
                    whereBetween('CreateDate', [$begin, $end])->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
                case $amountNumber3:
                    $paginate = IncomeOrder::query($table)->
                    whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2]])->
                    whereBetween('CreateDate', [$begin, $end])->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
                case $amountNumber2:
                    $paginate = IncomeOrder::query($table)->
                    whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1]])->
                    whereBetween('CreateDate', [$begin, $end])->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
                case $amountNumber1:
                    $paginate = IncomeOrder::query($table)->
                    whereIn('Amount', [$amount_arr_list[0]])->
                    whereBetween('CreateDate', [$begin, $end])->
                    orderBy('CreateDate', 'DESC')->
                    paginate($size);
                    return $paginate;
                    break;
                default:
                    break;
            }
        }

        function query_stateOrder_amount($table, $begin, $end, $amount_list, $state_order, $size)
        {
            $amount_arr_list = explode(' ', $amount_list);

            $amountNumber5 = '5';
            $amountNumber4 = '4';
            $amountNumber3 = '3';
            $amountNumber2 = '2';
            $amountNumber1 = '1';

            $order_5 = '5';//支付状态 【5 已成功】【1 未成功】
            $order_1 = '1';

            switch (count($amount_arr_list)) {
                case $amountNumber5:
                    switch ($state_order) {
                        case $order_5:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3], $amount_arr_list[4]])->
                            where('PayStatus', $state_order)->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        case $order_1:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3], $amount_arr_list[4]])->
                            where('PayStatus', $state_order)->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        default:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3], $amount_arr_list[4]])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                    }
                    break;
                case $amountNumber4:
                    switch ($state_order) {
                        case $order_5:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3]])->
                            where('PayStatus', $state_order)->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        case $order_1:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3]])->
                            where('PayStatus', $state_order)->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        default:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3]])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                    }
                    break;
                case $amountNumber3:
                    switch ($state_order) {
                        case $order_5:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2]])->
                            where('PayStatus', $state_order)->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        case $order_1:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2]])->
                            where('PayStatus', $state_order)->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        default:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2]])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                    }
                    break;
                case $amountNumber2:
                    switch ($state_order) {
                        case $order_5:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1]])->
                            where('PayStatus', $state_order)->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        case $order_1:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1]])->
                            where('PayStatus', $state_order)->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        default:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1]])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                    }
                    break;
                case $amountNumber1:
                    switch ($state_order) {
                        case $order_5:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0]])->
                            where('PayStatus', $state_order)->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        case $order_1:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0]])->
                            where('PayStatus', $state_order)->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        default:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0]])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                    }
                    break;
                default:
                    break;
            }
        }

        function query_date_stateOrder_amount($table, $begin, $end, $amount_list, $state_order, $size)
        {
            $amount_arr_list = explode(' ', $amount_list);
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));

            $amountNumber5 = '5';
            $amountNumber4 = '4';
            $amountNumber3 = '3';
            $amountNumber2 = '2';
            $amountNumber1 = '1';

            $order_5 = '5';//支付状态 【5 已成功】【1 未成功】
            $order_1 = '1';

            switch (count($amount_arr_list)) {
                case $amountNumber5:
                    switch ($state_order) {
                        case $order_5:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3], $amount_arr_list[4]])->
                            where('PayStatus', $state_order)->
                            whereBetween('CreateDate', [$begin, $end])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        case $order_1:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3], $amount_arr_list[4]])->
                            where('PayStatus', $state_order)->
                            whereBetween('CreateDate', [$begin, $end])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        default:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3], $amount_arr_list[4]])->
                            whereBetween('CreateDate', [$begin, $end])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                    }
                    break;
                case $amountNumber4:
                    switch ($state_order) {
                        case $order_5:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3]])->
                            where('PayStatus', $state_order)->
                            whereBetween('CreateDate', [$begin, $end])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        case $order_1:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3]])->
                            where('PayStatus', $state_order)->
                            whereBetween('CreateDate', [$begin, $end])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        default:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2], $amount_arr_list[3]])->
                            whereBetween('CreateDate', [$begin, $end])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                    }
                    break;
                case $amountNumber3:
                    switch ($state_order) {
                        case $order_5:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2]])->
                            where('PayStatus', $state_order)->
                            whereBetween('CreateDate', [$begin, $end])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        case $order_1:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2]])->
                            where('PayStatus', $state_order)->
                            whereBetween('CreateDate', [$begin, $end])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        default:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1], $amount_arr_list[2]])->
                            whereBetween('CreateDate', [$begin, $end])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                    }
                    break;
                case $amountNumber2:
                    switch ($state_order) {
                        case $order_5:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1]])->
                            where('PayStatus', $state_order)->
                            whereBetween('CreateDate', [$begin, $end])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        case $order_1:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1]])->
                            where('PayStatus', $state_order)->
                            whereBetween('CreateDate', [$begin, $end])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        default:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0], $amount_arr_list[1]])->
                            whereBetween('CreateDate', [$begin, $end])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                    }
                    break;
                case $amountNumber1:
                    switch ($state_order) {
                        case $order_5:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0]])->
                            where('PayStatus', $state_order)->
                            whereBetween('CreateDate', [$begin, $end])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        case $order_1:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0]])->
                            where('PayStatus', $state_order)->
                            whereBetween('CreateDate', [$begin, $end])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                        default:
                            $paginate = IncomeOrder::query($table)->
                            whereIn('Amount', [$amount_arr_list[0]])->
                            whereBetween('CreateDate', [$begin, $end])->
                            orderBy('CreateDate', 'DESC')->
                            paginate($size);
                            return $paginate;
                            break;
                    }
                    break;
                default:
                    break;
            }
        }

        if ($begin && $end && $amount_list && $state_order) {
            return query_date_stateOrder_amount($table, $begin, $end, $amount_list, $state_order, $size);
        } else if ($begin && $end && $amount_list) {
            return query_date_amount($table, $begin, $end, $amount_list, $state_order, $size);
        } else if ($begin && $end && $state_order) {
            return query_date_stateOrder($table, $begin, $end, $amount_list, $state_order, $size);
        } else if ($state_order && $amount_list) {
            return query_stateOrder_amount($table, $begin, $end, $amount_list, $state_order, $size);
        } else if ($begin && $end) {
            return query_date($table, $begin, $end, $amount_list, $state_order, $size);
        } else if ($state_order) {
            return query_state_order($table, $begin, $end, $amount_list, $state_order, $size);
        } else if ($amount_list) {
            return query_amount($table, $begin, $end, $amount_list, $state_order, $size);
        }

        return query($table, $begin, $end, $amount_list, $state_order, $size, $request);


//        if (!$paginate = Cache::get('data')){
//            $paginate = IncomeOrder::query($table)->
//            orderBy('CreateDate')->
//            get();
//            Cache::forever('data',$paginate);
//
//
//            return $paginate;
//        }else{
//            return $paginate;
//        }


//        $paginate = Cache::remember('users', 5, function() {
//            $table = "orderdata";
//            $paginate = IncomeOrder::query($table)->
//            orderBy('CreateDate')->
//            get();
//
//            return $paginate;
//        });
//        $collection = collect([$paginate]);
//        $collapsed = $collection->collapse();
//        $collapsed->all();

//        if ($request->has('page')) {
//            $current_page = $request->input('page');
//            $current_page = $current_page <= 0 ? 1 : $current_page;
//        } else {
//            $current_page = 1;
//        }
//        $item = array_slice(json_decode(json_encode($paginate), true), ($current_page - 1) * $size, $size); //注释1
//        $total = count($paginate);
//
//        $paginator = new LengthAwarePaginator($item, $total, $size, $current_page, [
//            'path' => Paginator::resolveCurrentPath(),  //注释2
//            'pageName' => 'page',
//        ]);


    }
}
