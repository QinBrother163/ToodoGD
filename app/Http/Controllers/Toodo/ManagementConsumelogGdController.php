<?php

namespace App\Http\Controllers\Toodo;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\ConsumelogGd103;
use App\ManagementConsumelogGd;


class ManagementConsumelogGdController extends Controller
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
        $tableNAV = 'gd';


        if (count($indexDataRow_arr_list) > '3') {
            $count = count($indexDataRow_arr_list);
            $arr = array();
            for($y = 0; $y < $count/9; $y++){
                for($x = 0; $x < 9; $x++){
                    $arr[$y][$x] = $indexDataRow_arr_list[$y*9+$x];
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
                DB::insert("insert into consumelog_gd(id,tj_area,tj_product,userid,goodsname,pay_code,price,num,time) values ('$val0','$val1','$val2','$val3','$val4','$val5','$val6','$val7','$val8')");
            }
        }elseif (count($indexDataAddRow_arr_list) > '3'){

            DB::insert("insert into consumelog_gd(id,tj_area,tj_product,userid,goodsname,pay_code,price,num,time) values ('$indexDataAddRow_arr_list[0]','$indexDataAddRow_arr_list[1]','$indexDataAddRow_arr_list[2]','$indexDataAddRow_arr_list[3]','$indexDataAddRow_arr_list[4]','$indexDataAddRow_arr_list[5]','$indexDataAddRow_arr_list[6]','$indexDataAddRow_arr_list[7]','$indexDataAddRow_arr_list[8]')");

        }


        if ($begin && $end) {
            $paginate = ManagementConsumelogGd::query($tableNAV)->
            whereBetween('createDate', [$begin, $end])->
            paginate($size);

            $users = ConsumelogGd103::query($tableNAV)->
            whereBetween('time', [$begin, $end])->
            paginate("9999999");

            if (!$paginate->isEmpty()) {
                return $paginate;
            } else {
                return $users;
            }
        }

        $paginate = ManagementConsumelogGd::query($tableNAV)->paginate($size);
        return $paginate;

    }
}
