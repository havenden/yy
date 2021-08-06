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
    /**
     * 商务通有效对话分析
     * @param Request $request
     * @return false|string
     */
    public function getYySwts(Request $request)
    {
        $status=0;
        $body=[];
        $start=$request->input('btime')?Carbon::parse($request->input('btime'))->startOfDay()->toDateTimeString():Carbon::now()->startOfMonth()->toDateTimeString();
        $end=$request->input('etime')?Carbon::parse($request->input('etime'))->endOfDay()->toDateTimeString():Carbon::now()->toDateTimeString();
        $hid=intval($request->input('hid'));
        if (isset($hid)&&$hid>0&&Aiden::isActiveDomain($request)){
            $table='swts_'.$hid;
            if (Schema::hasTable($table)){
                $swtCount=DB::table($table)->where([['start_time','>=',$start], ['start_time','<=',$end]])->count();
                $swtAddNoCount=DB::table($table)->where([['start_time','>=',$start], ['start_time','<=',$end],['member_type','广告否词']])->count();//广告否词
                $swtEffectiveCount=DB::table($table)->where([
                    ['start_time','>=',$start], ['start_time','<=',$end],['is_effective',1]
                ])->count();
                $swtsArray=DB::table($table)->select(DB::raw('count(author) as c, author'))->where([
                    ['start_time','>=',$start], ['start_time','<=',$end]
                ])->groupBy('author')->pluck('c','author')->toArray();
                $body['all']=$swtCount;
                $body['effective']=$swtEffectiveCount;
                $body['addNo']=$swtAddNoCount;
                foreach ($swtsArray as $me=>$num){
                    $me_all=$num;
                    $me_effective=DB::table($table)->where([['author','=',$me],['start_time','>=',$start], ['start_time','<=',$end],['is_effective',1]])->count();
                    $me_add_no=DB::table($table)->where([['author','=',$me],['start_time','>=',$start], ['start_time','<=',$end],['member_type','广告否词']])->count();
                    $body['authors'][$me]['all']=$me_all;//数量
                    $body['authors'][$me]['effective']=$me_effective;
                    $body['authors'][$me]['add_no']=$me_add_no;
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

    /**
     * 有效对话媒体来源
     * @param Request $request
     * @return false|string
     */
    public function getMediaSwts(Request $request)
    {
        $status=0;
        $body=[];
        $start=$request->input('btime')?Carbon::parse($request->input('btime'))->startOfDay()->toDateTimeString():Carbon::now()->startOfMonth()->toDateTimeString();
        $end=$request->input('etime')?Carbon::parse($request->input('etime'))->endOfDay()->toDateTimeString():Carbon::now()->toDateTimeString();
        $hid=intval($request->input('hid'));
        if (isset($hid)&&$hid>0&&Aiden::isActiveDomain($request)){
            $table='swts_'.$hid;
            if (Schema::hasTable($table)){
//                $swtCount=DB::table($table)->where([['start_time','>=',$start], ['start_time','<=',$end],['is_effective',1]])->count();
//                $swtDevice=DB::table($table)->select(DB::raw('count(device) as c, device'))->where([['start_time','>=',$start], ['start_time','<=',$end],['is_effective',1]])->groupBy('device')->pluck('c','device')->toArray();
                $swtMobile=DB::table($table)->select(DB::raw('count(engine_from) as e, engine_from'))->where([['start_time','>=',$start], ['start_time','<=',$end],['is_effective',1]])->whereIn('device', ['mobile', '手机'])->groupBy('engine_from')->pluck('e','engine_from')->toArray();
                $swtPc=DB::table($table)->select(DB::raw('count(engine_from) as e, engine_from'))->where([['start_time','>=',$start], ['start_time','<=',$end],['is_effective',1]])->whereIn('device', ['pc', '电脑'])->groupBy('engine_from')->pluck('e','engine_from')->toArray();
                $body['count']=0;
                $body['device']=[
                    'mobile'=>['count'=>0,'media'=>[]],
                    'pc'=>['count'=>0,'media'=>[]],
                ];
                foreach ($swtMobile as $m=>$v){
                    $body['count'] += intval($v);
                    $body['device']['mobile']['count'] += intval($v);
                    $body['device']['mobile']['media'][$m]=$v;
                }
                foreach ($swtPc as $p=>$va){
                    $body['count'] += intval($va);
                    $body['device']['pc']['count'] += intval($va);
                    $body['device']['pc']['media'][$p]=$va;
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

    /**
     * 有效对话地域分布
     * @param Request $request
     */
    public function getAreaSwts(Request $request)
    {
        $status=0;
        $body=[];
        $start=$request->input('btime')?Carbon::parse($request->input('btime'))->startOfDay()->toDateTimeString():Carbon::now()->startOfMonth()->toDateTimeString();
        $end=$request->input('etime')?Carbon::parse($request->input('etime'))->endOfDay()->toDateTimeString():Carbon::now()->toDateTimeString();
        $hid=intval($request->input('hid'));
        if (isset($hid)&&$hid>0&&Aiden::isActiveDomain($request)){
            $table='swts_'.$hid;
            if (Schema::hasTable($table)){
                $body=DB::table($table)->select(DB::raw('count(area) as a, area'))->where([['start_time','>=',$start], ['start_time','<=',$end],['is_effective',1]])->groupBy('area')->orderBy('a','desc')->pluck('a','area')->toArray();
                $status=1;
            }
        }
        $data=[
            'status'=>$status,
            'body'=>$body,
        ];
        return json_encode($data);
    }

    /**
     * 商务通有效对话分析-咨询转化
     */
    public function zxTrans(Request $request)
    {
        $status=0;
        $body=[];
        $start=$request->input('btime')?Carbon::parse($request->input('btime'))->startOfDay()->toDateTimeString():Carbon::now()->startOfMonth()->toDateTimeString();
        $end=$request->input('etime')?Carbon::parse($request->input('etime'))->endOfDay()->toDateTimeString():Carbon::now()->toDateTimeString();
        $hid=intval($request->input('hid'));
        $authorInput = $request->input('author');
        if (isset($hid)&&$hid>0&&Aiden::isActiveDomain($request)){
            $table='swts_'.$hid;
            if (Schema::hasTable($table)){
                //对话数量
                $swtCount=DB::table($table)->where([['start_time','>=',$start], ['start_time','<=',$end]])->count();
                //有效对话
                $swtEffectiveCount=DB::table($table)->where([
                    ['start_time','>=',$start], ['start_time','<=',$end]
                ])->whereIn('msg_type',['较好对话','极佳对话','一般对话'])->count();
                //一句话
                $swtOneCount=DB::table($table)->where([
                    ['start_time','>=',$start], ['start_time','<=',$end]
                ])->whereIn('msg_type',['其他有效对话'])->count();

                $swtsArray=DB::table($table)->select(DB::raw('count(author) as c, author'))->where([
                    ['start_time','>=',$start], ['start_time','<=',$end]
                ])->groupBy('author')->pluck('c','author')->toArray();
                $body['all']=$swtCount;
                $body['effective']=$swtEffectiveCount;
                $body['one']=$swtOneCount;
                //有效对话率
                $body['effective_percent']=$swtCount>0?sprintf('%.2f',($swtEffectiveCount/$swtCount)*100).'%':0;
                //一句话占比
                $body['one_percent']=$swtCount>0?sprintf('%.2f',($swtOneCount/$swtCount)*100).'%':0;
                //总套联
                $body['contact']=DB::table($table)->where([
                    ['start_time','>=',$start], ['start_time','<=',$end],['is_contact',1]
                ])->count();
                //套联率
                $body['contact_percent']=$swtCount>0?sprintf('%.2f',($body['contact']/$swtCount)*100).'%':0;
                foreach ($swtsArray as $me=>$num){
                    $me_all=$num;
                    //有效对话
                    $me_effective=DB::table($table)->where([['author','=',$me],['start_time','>=',$start], ['start_time','<=',$end]])->whereIn('msg_type',['较好对话','极佳对话'])->count();
                    //一句话
                    $me_one=DB::table($table)->where([['author','=',$me],['start_time','>=',$start], ['start_time','<=',$end]])->whereIn('msg_type',['一般对话','其他有效对话'])->count();
                    //套联
                    $me_contact = DB::table($table)->where([['author','=',$me],['start_time','>=',$start], ['start_time','<=',$end],['is_contact',1]])->count();
                    $body['authors'][$me]['all']=$me_all;//数量
                    $body['authors'][$me]['effective']=$me_effective;
                    $body['authors'][$me]['one']=$me_one;
                    $body['authors'][$me]['contact']=$me_contact;
                    //有效对话率
                    $body['authors'][$me]['effective_percent']=$me_all>0?sprintf('%.2f',($me_effective/$me_all)*100).'%':0;
                    //一句话占比
                    $body['authors'][$me]['one_percent']=$me_all>0?sprintf('%.2f',($me_one/$me_all)*100).'%':0;
                    //套联率
                    $body['authors'][$me]['contact_percent']=$me_all>0?sprintf('%.2f',($me_contact/$me_all)*100).'%':0;
                }
                $status=1;
            }
        }
        if (isset($authorInput)&&!empty($authorInput)){
            $data=[
                'status'=>$status,
                'body'=>$body['authors'][$authorInput],
             ];
        }else{
            $data=[
                'status'=>$status,
                'body'=>$body,
             ];
        }
        return json_encode($data);
    }

}
