<?php

namespace App\Http\Controllers\Toodo;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\ManagementMgpayrecord;
use App\Mgpayrecord104;
use App\mgpayrecord;

class managementMgpayrecordController extends Controller
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

        $table = 'fsdp_mgpayrecord_list_'.$currentMonth;

        if (count($indexDataRow_arr_list) > '3') {
            $count = count($indexDataRow_arr_list);
            $arr = array();
            for($y = 0; $y < $count/7; $y++){
                for($x = 0; $x < 7; $x++){
                    $arr[$y][$x] = $indexDataRow_arr_list[$y*7+$x];
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

                if ($val5 == "68"){
                    $val5 = "30";
                    DB::insert("insert into $table(id,createDate,devNO,CARegionCode,needCnfm,price,CPID) values ('$val0','$val1','$val2','$val3','$val4','$val5','$val6')");
                }else{
                    DB::insert("insert into $table(id,createDate,devNO,CARegionCode,needCnfm,price,CPID) values ('$val0','$val1','$val2','$val3','$val4','$val5','$val6')");
                }
            }
        }elseif (count($indexDataAddRow_arr_list) > '3'){

            if ($indexDataAddRow_arr_list[5] == "68"){
                $indexDataAddRow_arr_list[5] = "30";
                DB::insert("insert into $table(id,createDate,devNO,CARegionCode,needCnfm,price,CPID) values ('$indexDataAddRow_arr_list[0]','$indexDataAddRow_arr_list[1]','$indexDataAddRow_arr_list[2]','$indexDataAddRow_arr_list[3]','$indexDataAddRow_arr_list[4]','$indexDataAddRow_arr_list[5]','$indexDataAddRow_arr_list[6]')");
            }else{
                DB::insert("insert into $table(id,createDate,devNO,CARegionCode,needCnfm,price,CPID) values ('$indexDataAddRow_arr_list[0]','$indexDataAddRow_arr_list[1]','$indexDataAddRow_arr_list[2]','$indexDataAddRow_arr_list[3]','$indexDataAddRow_arr_list[4]','$indexDataAddRow_arr_list[5]','$indexDataAddRow_arr_list[6]')");
            }
        }


        if ($begin && $end) {
            $paginate = ManagementMgpayrecord::query($end_list[0] . $end_list[1])->
            whereBetween('createDate', [$begin, $end])->
            paginate($size);

            $users = Mgpayrecord104::query($end_list[0] . $end_list[1])->
            whereBetween('createDate', [$begin, $end])->
            paginate("9999999");

            if (!$paginate->isEmpty()) {
                return $paginate;
            } else {
                return $users;
            }
        }

        $paginate = ManagementMgpayrecord::query($currentMonth)->paginate($size);
//        $paginate = Mgpayrecord104::query($currentMonth)->paginate($size);
        return $paginate;

    }
}
