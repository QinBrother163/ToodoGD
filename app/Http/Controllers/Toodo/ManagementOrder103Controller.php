<?php

namespace App\Http\Controllers\Toodo;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Order103;
use App\ManagementOrder;
use App\GdOrder;

class ManagementOrder103Controller extends Controller
{
    public function order(Request $request)
    {
        ini_set('memory_limit', '1024M');
        DB::connection()->disableQueryLog();

        $size = $request->input('size', 15, 10, 20);

        $begin = $request->input('begin');
        $end = $request->input('end');
        $indexDataRow = $request->input('indexDataRow');
        $indexDataAddRow = $request->input('indexDataAddRow');
        $indexDataRow_arr_list = explode(',', $indexDataRow);
        $indexDataAddRow_arr_list = explode(',', $indexDataAddRow);
        $currentMonth = date("Ym");
        $end_list = explode('-', $end);
        $begin_list = explode('-', $begin);

        $table = 'order_'.$currentMonth;

        if (count($indexDataRow_arr_list) > '3') {
            $count = count($indexDataRow_arr_list);
            $arr = array();
            for($y = 0; $y < $count/14; $y++){
                for($x = 0; $x < 14; $x++){
                    $arr[$y][$x] = $indexDataRow_arr_list[$y*14+$x];
                }
            }

            foreach ($arr as $value){
                $val0 = $value['0'];
                $val1 = $value['1'];
                $val2 = $value['2'];
                $val3 = $value['3'];
                $val4 = $value['4'];
                $val5 = $value['5'];
                $val6 = $value['6'];
                $val7 = $value['7'];
                $val8 = $value['8'];
                $val9 = $value['9'];
                $val10 = $value['10'];
                $val11 = $value['11'];
                $val12 = $value['12'];
                $val13 = $value['13'];
                DB::insert("insert into order_201711(order_id,user_id,app_type,app_id,currency_type,fee,date,state,event,title,real_name,phone,address,update_time) values ('$val0','$val1','$val2','$val3','$val4','$val5','$val6','$val7','$val8','$val9','$val10','$val11','$val12','$val13')");
            }
        }elseif (count($indexDataAddRow_arr_list) > '3'){

            DB::insert("insert into order_201711(order_id,user_id,app_type,app_id,currency_type,fee,date,state,event,title,real_name,phone,address,update_time) values ('$indexDataAddRow_arr_list[0]','$indexDataAddRow_arr_list[1]','$indexDataAddRow_arr_list[2]','$indexDataAddRow_arr_list[3]','$indexDataAddRow_arr_list[4]','$indexDataAddRow_arr_list[5]','$indexDataAddRow_arr_list[6]','$indexDataAddRow_arr_list[7]','$indexDataAddRow_arr_list[8]','$indexDataAddRow_arr_list[9]','$indexDataAddRow_arr_list[10]','$indexDataAddRow_arr_list[11]','$indexDataAddRow_arr_list[12]','$indexDataAddRow_arr_list[13]')");

        }


        if ($begin && $end) {
            $paginate = ManagementOrder::query($end_list[0] . $end_list[1])->
            whereBetween('createDate', [$begin, $end])->
            paginate($size);

            $users = Order103::query($end_list[0] . $end_list[1])->
            whereBetween('date', [$begin, $end])->
            paginate("9999999");

            if (!$paginate->isEmpty()) {
                return $paginate;
            } else {
                return $users;
            }
        }
        //DB::insert("insert into $table(order_id) values ('555')");



        $paginate = ManagementOrder::query($currentMonth)->paginate($size);
        return $paginate;

    }
}
