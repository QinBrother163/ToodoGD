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
        $amount_list = $request->input('amount_list');
        $order_status = $request->input('order_status');//1-5-10

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

        function query($currentMonth, $begin, $end, $amount_list, $order_status, $size)
        {
            $paginate = GdOrder::query($currentMonth)->
            orderBy('date')->
            paginate($size);
            return $paginate;
        }

        function query_date($currentMonth, $begin, $end, $amount_list, $order_status, $size, $request)
        {
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));
            $end_list = explode('-', $end);
            $begin_list = explode('-', $begin);

            switch ((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) {
                case 2:
                    $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                    whereBetween('date', [$begin, $end])->
                    orderBy('date')->
                    get();

                    $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                    whereBetween('date', [$begin, $end])->
                    orderBy('date')->
                    get();

                    $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                    orderBy('date')->
                    get();
                    return processingPaging3($paginate1, $paginate2, $paginate3, $size, $request);
                    break;
                case 1:
                    $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                    whereBetween('date', [$begin, $end])->
                    orderBy('date')->
                    get();

                    $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                    whereBetween('date', [$begin, $end])->
                    orderBy('date')->
                    get();

                    return processingPaging2($paginate1, $paginate2, $size, $request);
                    break;
                default:
                    $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                    whereBetween('date', [$begin, $end])->
                    orderBy('date')->
                    paginate($size);
                    return $paginate;
                    break;
            }
        }

        function query_orderStatus($currentMonth, $begin, $end, $amount_list, $order_status, $size)
        {
            $verifyStatus0_0 = '0';

            switch ($order_status) {
                case 1:
                    $paginate = GdOrder::query($currentMonth)->
                    where('state', $order_status)->
                    orderBy('date')->
                    paginate($size);
                    return $paginate;
                    break;
                case 10:
                    $paginate = GdOrder::query($currentMonth)->
                    orderBy('date')->
                    paginate($size);
                    return $paginate;
                    break;
                default:
                    $paginate = GdOrder::query($currentMonth)->
                    where('state', $verifyStatus0_0)->
                    orderBy('date')->
                    paginate($size);
                    return $paginate;
                    break;
            }
        }

        function query_Amount_orderStatus($currentMonth, $begin, $end, $amount_list, $order_status, $size)
        {
            $amount_arr = explode(',', $amount_list);
            $amount_than19800 = '19800';
            $verifyStatus0_0 = '0';

            switch ($order_status) {
                case 1:
                    switch (count($amount_arr)) {
                        case 2:
                            $paginate = GdOrder::query($currentMonth)->
                            where('state', $order_status)->
                            orderBy('date')->
                            paginate($size);
                            return $paginate;
                            break;
                        case 1:
                            switch ($amount_list) {
                                case 19800:
                                    $paginate = GdOrder::query($currentMonth)->
                                    whereIn('fee', [$amount_list])->
                                    where('state', $order_status)->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                case 10:
                                    $paginate = GdOrder::query($currentMonth)->
                                    where('fee', '<', $amount_than19800)->
                                    where('state', $order_status)->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                default:
                                    break;
                            }
                            break;
                        default:
                            break;
                    }
                    break;
                case 10:
                    switch (count($amount_arr)) {
                        case 2:
                            $paginate = GdOrder::query($currentMonth)->
                            orderBy('date')->
                            paginate($size);
                            return $paginate;
                            break;
                        case 1:
                            switch ($amount_list) {
                                case 19800:
                                    $paginate = GdOrder::query($currentMonth)->
                                    whereIn('fee', [$amount_list])->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                case 10:
                                    $paginate = GdOrder::query($currentMonth)->
                                    where('fee', '<', $amount_than19800)->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                default:
                                    break;
                            }
                            break;
                        default:
                            break;
                    }
                    break;
                default:
                    switch (count($amount_arr)) {
                        case 2:
                            $paginate = GdOrder::query($currentMonth)->
                            where('state', $verifyStatus0_0)->
                            orderBy('date')->
                            paginate($size);
                            return $paginate;
                            break;
                        case 1:
                            switch ($amount_list) {
                                case 19800:
                                    $paginate = GdOrder::query($currentMonth)->
                                    whereIn('fee', [$amount_list])->
                                    where('state', $verifyStatus0_0)->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                case 10:
                                    $paginate = GdOrder::query($currentMonth)->
                                    where('fee', '<', $amount_than19800)->
                                    where('state', $verifyStatus0_0)->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                default:
                                    break;
                            }
                            break;
                        default:
                            break;
                    }
                    break;
            }
        }

        function query_date_Amount_orderStatus($currentMonth, $begin, $end, $amount_list, $order_status, $size, $request)
        {
            $begin = date('Y-m-d', strtotime($begin));
            $end = date('Y-m-d', strtotime($end));
            $end = date('Y-m-d', strtotime('+1 day', strtotime($end)));
            $end_list = explode('-', $end);
            $begin_list = explode('-', $begin);
            $amount_arr = explode(',', $amount_list);
            $amount_than19800 = '19800';
            $verifyStatus0_0 = '0';

            switch ((int)($end_list[0] . $end_list[1]) - (int)($begin_list[0] . $begin_list[1])) {
                case 2:
                    switch ($order_status) {
                        case 1:
                            switch (count($amount_arr)) {
                                case 2:
                                    $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                                    where('state', $order_status)->
                                    whereBetween('date', [$begin, $end])->
                                    orderBy('date')->
                                    get();

                                    $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                                    where('state', $order_status)->
                                    whereBetween('date', [$begin, $end])->
                                    orderBy('date')->
                                    get();

                                    $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                                    where('state', $order_status)->
                                    orderBy('date')->
                                    get();

                                    return processingPaging3($paginate1, $paginate2, $paginate3, $size, $request);
                                    break;
                                case 1:
                                    switch ($amount_list) {
                                        case 19800:
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

                                            return processingPaging3($paginate1, $paginate2, $paginate3, $size, $request);
                                            break;
                                        case 10:
                                            $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                                            where('fee', '<', $amount_than19800)->
                                            where('state', $order_status)->
                                            whereBetween('date', [$begin, $end])->
                                            get();

                                            $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                                            where('fee', '<', $amount_than19800)->
                                            where('state', $order_status)->
                                            whereBetween('date', [$begin, $end])->
                                            get();

                                            $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                                            where('fee', '<', $amount_than19800)->
                                            where('state', $order_status)->
                                            get();

                                            return processingPaging3($paginate1, $paginate2, $paginate3, $size, $request);
                                            break;
                                        default:
                                            break;
                                    }
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 10:
                            switch (count($amount_arr)) {
                                case 2:
                                    $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                                    whereBetween('date', [$begin, $end])->
                                    orderBy('date')->
                                    get();

                                    $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                                    whereBetween('date', [$begin, $end])->
                                    orderBy('date')->
                                    get();

                                    $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                                    orderBy('date')->
                                    get();

                                    return processingPaging3($paginate1, $paginate2, $paginate3, $size, $request);
                                    break;
                                case 1:
                                    switch ($amount_list) {
                                        case 19800:
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

                                            return processingPaging3($paginate1, $paginate2, $paginate3, $size, $request);
                                            break;
                                        case 10:
                                            $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                                            where('fee', '<', $amount_than19800)->
                                            whereBetween('date', [$begin, $end])->
                                            get();

                                            $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                                            where('fee', '<', $amount_than19800)->
                                            whereBetween('date', [$begin, $end])->
                                            get();

                                            $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                                            where('fee', '<', $amount_than19800)->
                                            get();

                                            return processingPaging3($paginate1, $paginate2, $paginate3, $size, $request);
                                            break;
                                        default:
                                            break;
                                    }
                                    break;
                                default:
                                    break;
                            }
                            break;
                        default:
                            switch (count($amount_arr)) {
                                case 2:
                                    $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                                    where('state', $verifyStatus0_0)->
                                    whereBetween('date', [$begin, $end])->
                                    orderBy('date')->
                                    get();

                                    $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                                    where('state', $verifyStatus0_0)->
                                    whereBetween('date', [$begin, $end])->
                                    orderBy('date')->
                                    get();

                                    $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                                    where('state', $verifyStatus0_0)->
                                    orderBy('date')->
                                    get();

                                    return processingPaging3($paginate1, $paginate2, $paginate3, $size, $request);
                                    break;
                                case 1:
                                    switch ($amount_list) {
                                        case 19800:
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

                                            return processingPaging3($paginate1, $paginate2, $paginate3, $size, $request);
                                            break;
                                        case 10:
                                            $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                                            where('fee', '<', $amount_than19800)->
                                            where('state', $verifyStatus0_0)->
                                            whereBetween('date', [$begin, $end])->
                                            get();

                                            $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                                            where('fee', '<', $amount_than19800)->
                                            where('state', $verifyStatus0_0)->
                                            whereBetween('date', [$begin, $end])->
                                            get();

                                            $paginate3 = GdOrder::query((int)($begin_list[0] . $begin_list[1]) + 1)->
                                            where('fee', '<', $amount_than19800)->
                                            where('state', $verifyStatus0_0)->
                                            whereBetween('date', [$begin, $end])->
                                            get();

                                            return processingPaging3($paginate1, $paginate2, $paginate3, $size, $request);
                                            break;
                                        default:
                                            break;
                                    }
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
                                case 2:
                                    $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                                    where('state', $order_status)->
                                    whereBetween('date', [$begin, $end])->
                                    orderBy('date')->
                                    get();

                                    $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                                    where('state', $order_status)->
                                    whereBetween('date', [$begin, $end])->
                                    orderBy('date')->
                                    get();

                                    return processingPaging2($paginate1, $paginate2, $size, $request);
                                    break;
                                case 1:
                                    switch ($amount_list) {
                                        case 19800:
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

                                            return processingPaging2($paginate1, $paginate2, $size, $request);
                                            break;
                                        case 10:
                                            $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                                            where('fee', '<', $amount_than19800)->
                                            where('state', $order_status)->
                                            whereBetween('date', [$begin, $end])->
                                            get();

                                            $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                                            where('fee', '<', $amount_than19800)->
                                            where('state', $order_status)->
                                            whereBetween('date', [$begin, $end])->
                                            get();

                                            return processingPaging2($paginate1, $paginate2, $size, $request);
                                            break;
                                        default:
                                            break;
                                    }
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 10:
                            switch (count($amount_arr)) {
                                case 2:
                                    $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                                    whereBetween('date', [$begin, $end])->
                                    orderBy('date')->
                                    get();

                                    $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                                    whereBetween('date', [$begin, $end])->
                                    orderBy('date')->
                                    get();

                                    return processingPaging2($paginate1, $paginate2, $size, $request);
                                    break;
                                case 1:
                                    switch ($amount_list) {
                                        case 19800:
                                            $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                                            whereIn('fee', [$amount_list])->
                                            whereBetween('date', [$begin, $end])->
                                            get();

                                            $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                                            whereIn('fee', [$amount_list])->
                                            whereBetween('date', [$begin, $end])->
                                            get();

                                            return processingPaging2($paginate1, $paginate2, $size, $request);
                                            break;
                                        case 10:
                                            $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                                            where('fee', '<', $amount_than19800)->
                                            whereBetween('date', [$begin, $end])->
                                            get();

                                            $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                                            where('fee', '<', $amount_than19800)->
                                            whereBetween('date', [$begin, $end])->
                                            get();

                                            return processingPaging2($paginate1, $paginate2, $size, $request);
                                            break;
                                        default:
                                            break;
                                    }
                                    break;
                                default:
                                    break;
                            }
                            break;
                        default:
                            switch (count($amount_arr)) {
                                case 2:
                                    $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                                    where('state', $verifyStatus0_0)->
                                    whereBetween('date', [$begin, $end])->
                                    orderBy('date')->
                                    get();

                                    $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                                    where('state', $verifyStatus0_0)->
                                    whereBetween('date', [$begin, $end])->
                                    orderBy('date')->
                                    get();

                                    return processingPaging2($paginate1, $paginate2, $size, $request);
                                    break;
                                case 1:
                                    switch ($amount_list) {
                                        case 19800:
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

                                            return processingPaging2($paginate1, $paginate2, $size, $request);
                                            break;
                                        case 10:
                                            $paginate1 = GdOrder::query($begin_list[0] . $begin_list[1])->
                                            where('fee', '<', $amount_than19800)->
                                            where('state', $verifyStatus0_0)->
                                            whereBetween('date', [$begin, $end])->
                                            get();

                                            $paginate2 = GdOrder::query($end_list[0] . $end_list[1])->
                                            where('fee', '<', $amount_than19800)->
                                            where('state', $verifyStatus0_0)->
                                            whereBetween('date', [$begin, $end])->
                                            get();

                                            return processingPaging2($paginate1, $paginate2, $size, $request);
                                            break;
                                        default:
                                            break;
                                    }
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
                                case 2:
                                    $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                                    where('state', $order_status)->
                                    whereBetween('date', [$begin, $end])->
                                    orderBy('date')->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                case 1:
                                    switch ($amount_list) {
                                        case 19800:
                                            $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                                            whereIn('fee', [$amount_list])->
                                            where('state', $order_status)->
                                            whereBetween('date', [$begin, $end])->
                                            paginate($size);
                                            return $paginate;
                                            break;
                                        case 10:
                                            $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                                            where('fee', '<', $amount_than19800)->
                                            where('state', $order_status)->
                                            whereBetween('date', [$begin, $end])->
                                            paginate($size);
                                            return $paginate;
                                            break;
                                        default:
                                            break;
                                    }
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 10:
                            switch (count($amount_arr)) {
                                case 2:
                                    $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                                    whereBetween('date', [$begin, $end])->
                                    orderBy('date')->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                case 1:
                                    switch ($amount_list) {
                                        case 19800:
                                            $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                                            whereIn('fee', [$amount_list])->
                                            whereBetween('date', [$begin, $end])->
                                            paginate($size);
                                            return $paginate;
                                            break;
                                        case 10:
                                            $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                                            where('fee', '<', $amount_than19800)->
                                            whereBetween('date', [$begin, $end])->
                                            paginate($size);
                                            return $paginate;
                                            break;
                                        default:
                                            break;
                                    }
                                    break;
                                default:
                                    break;
                            }
                            break;
                        default:
                            switch (count($amount_arr)) {
                                case 2:
                                    $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                                    where('state', $verifyStatus0_0)->
                                    whereBetween('date', [$begin, $end])->
                                    orderBy('date')->
                                    paginate($size);
                                    return $paginate;
                                    break;
                                case 1:
                                    switch ($amount_list) {
                                        case 19800:
                                            $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                                            whereIn('fee', [$amount_list])->
                                            where('state', $verifyStatus0_0)->
                                            whereBetween('date', [$begin, $end])->
                                            paginate($size);
                                            return $paginate;
                                            break;
                                        case 10:
                                            $paginate = GdOrder::query($end_list[0] . $end_list[1])->
                                            where('fee', '<', $amount_than19800)->
                                            where('state', $verifyStatus0_0)->
                                            whereBetween('date', [$begin, $end])->
                                            paginate($size);
                                            return $paginate;
                                            break;
                                        default:
                                            break;
                                    }
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
            return query_date_Amount_orderStatus($currentMonth, $begin, $end, $amount_list, $order_status, $size, $request);
        } elseif ($amount_list && $order_status) {
            return query_Amount_orderStatus($currentMonth, $begin, $end, $amount_list, $order_status, $size);
        } elseif ($begin && $end) {
            return query_date($currentMonth, $begin, $end, $amount_list, $order_status, $size, $request);
        } elseif ($order_status) {
            return query_orderStatus($currentMonth, $begin, $end, $amount_list, $order_status, $size);
        }
        return query($currentMonth, $begin, $end, $amount_list, $order_status, $size);

    }
}
