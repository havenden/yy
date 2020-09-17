<?php

namespace App\Http\Controllers;

use App\Aiden;
use App\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function getYySwts(Request $request)
    {
        $status=0;
        $body=[];
        $start=$request->input('btime')?Carbon::parse($request->input('btime'))->startOfDay()->toDateTimeString():Carbon::now()->startOfMonth()->toDateTimeString();
        $end=$request->input('etime')?Carbon::parse($request->input('etime'))->endOfDay()->toDateTimeString():Carbon::now()->toDateTimeString();
        $hid=intval($request->input('hid'));
        if ($hid>0){
            $swtCount=DB::table('swts_'.$hid)->where([['start_time','>=',$start], ['start_time','<=',$end]])->count();
            $swtEffectiveCount=DB::table('swts_'.$hid)->where([
                ['start_time','>=',$start], ['start_time','<=',$end],['is_effective',1]
            ])->count();
            $swtsArray=DB::table('swts_'.$hid)->select(DB::raw('count(author) as c, author'))->where([
                ['start_time','>=',$start], ['start_time','<=',$end]
            ])->groupBy('author')->pluck('c','author')->toArray();
//             dd($swtCount);
//             dd($swtsArray);
            $body['all']=$swtCount;
            $body['effective']=$swtEffectiveCount;
             foreach ($swtsArray as $me=>$num){
                 //数量,占总量 转化率
                 $me_all=$num;
                 $me_effective=DB::table('swts_'.$hid)->where([['author','=',$me],['start_time','>=',$start], ['start_time','<=',$end],['is_effective',1]])->count();
                 $body['authors'][$me]['all']=$me_all;//数量
                 $body['authors'][$me]['effective']=$me_effective;
                 $body['authors'][$me]['percent']=$swtCount>0?sprintf('%.2f',($me_all/$swtCount)*100).'%':0;
                 $body['authors'][$me]['effective_percent']=$swtEffectiveCount>0?sprintf('%.2f',($me_effective/$swtEffectiveCount)*100).'%':0;
             }
        }
        $data=[
            'status'=>$status,
            'body'=>$body,
        ];
        return json_encode($data);

    }
}
