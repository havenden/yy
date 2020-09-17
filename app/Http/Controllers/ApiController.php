<?php

namespace App\Http\Controllers;

use App\Aiden;
use App\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ApiController extends Controller
{
    public function getYySwts(Request $request)
    {
        $status=0;
        $body=[];
        $start=$request->input('btime')?Carbon::parse($request->input('btime'))->startOfDay()->toDateTimeString():Carbon::now()->startOfMonth()->toDateTimeString();
        $end=$request->input('etime')?Carbon::parse($request->input('etime'))->endOfDay()->toDateTimeString():Carbon::now()->toDateTimeString();
        $hid=intval($request->input('hid'));
        if (isset($hid)&&$hid>0){
            $table='swts_'.$hid;
            if (Schema::hasTable($table)){
                $swtCount=DB::table($table)->where([['start_time','>=',$start], ['start_time','<=',$end]])->count();
                $swtEffectiveCount=DB::table($table)->where([
                    ['start_time','>=',$start], ['start_time','<=',$end],['is_effective',1]
                ])->count();
                $swtsArray=DB::table($table)->select(DB::raw('count(author) as c, author'))->where([
                    ['start_time','>=',$start], ['start_time','<=',$end]
                ])->groupBy('author')->pluck('c','author')->toArray();
                $body['all']=$swtCount;
                $body['effective']=$swtEffectiveCount;
                foreach ($swtsArray as $me=>$num){
                    $me_all=$num;
                    $me_effective=DB::table($table)->where([['author','=',$me],['start_time','>=',$start], ['start_time','<=',$end],['is_effective',1]])->count();
                    $body['authors'][$me]['all']=$me_all;//数量
                    $body['authors'][$me]['effective']=$me_effective;
                    $body['authors'][$me]['percent']=$swtCount>0?sprintf('%.2f',($me_all/$swtCount)*100).'%':0;
                    $body['authors'][$me]['effective_percent']=$swtEffectiveCount>0?sprintf('%.2f',($me_effective/$swtEffectiveCount)*100).'%':0;
                }
                $status=1;
            }
        }
        $data=[
            'status'=>$status,
            'body'=>$body,
        ];
        return json_encode($data);

    }
}
