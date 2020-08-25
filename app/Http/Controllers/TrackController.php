<?php

namespace App\Http\Controllers;

use App\Aiden;
use App\Condition;
use App\Member;
use App\Track;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->id==1||Auth::user()->hasPermissionTo('members_read')){
            return view('track.index',
                [
                    'hospitals'=>Aiden::getAllHospitalsArray(),
                    'consults'=>Aiden::getAllConsultsArray(),
                    'channels'=>Aiden::getAllChannelsArray(),
                    'conditions'=>Condition::select('id','display_name')->get(),
                    'conditionsArray'=>Aiden::getAllConditionsArray(),
                    'users'=>Aiden::getAllUsersArray(),
                    'activeUsers'=>Aiden::getAllActiveUsersArray(),
                    'diseases'=>Aiden::getAllDiseasesArray(),
                    'doctors'=>Aiden::getAllDoctorsArray(),
                    'members' => Member::select('id','name', 'age','wechat','yy_num','hid','condition','tell','uid','tell_num','description','consult','created_at','updated_at','pubdate','okdate')
                        ->where('tell_num','<',config('yyxt.tell_num'))
                        ->whereIn('condition',[2,3,4,5])
                        ->where([
                            ['pubdate','>=',Carbon::now()->startOfDay()],
                            ['pubdate','<=',Carbon::now()->endOfDay()],
                        ])
                        ->orderBy('id','desc')->paginate(18),
                ]
            );
        }
        return abort(401,'权限不足');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('tracks_create')){
            $referer=$request->header('referer');
            if (Track::createTrack($request)){
                return redirect($referer);
            }
        }
        abort(401);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('members_read')) {
            $key = $request->input('key');
            $model = $request->input('model');
            if (!empty($key)) {
                return view('track.index', [
                    'key' => $key,
                    'hospitals'=>Aiden::getAllHospitalsArray(),
                    'consults'=>Aiden::getAllConsultsArray(),
                    'channels'=>Aiden::getAllChannelsArray(),
                    'conditions'=>Condition::select('id','display_name')->get(),
                    'conditionsArray'=>Aiden::getAllConditionsArray(),
                    'users'=>Aiden::getAllUsersArray(),
                    'activeUsers'=>Aiden::getAllActiveUsersArray(),
                    'diseases'=>Aiden::getAllDiseasesArray(),
                    'doctors'=>Aiden::getAllDoctorsArray(),
                    'members' => Member::select('id','name', 'age','wechat','yy_num','hid','condition','tell','uid','description','consult','created_at','pubdate','okdate')
                        ->where('tell_num','<',config('yyxt.tell_num'))
                        ->whereIn('condition',[2,3,4,5])
                        ->where([
                            ['pubdate','>=',Carbon::now()->startOfDay()],
                            ['pubdate','<=',Carbon::now()->endOfDay()],
                        ])
                        ->where('name', 'like', '%' . $key . '%')
                        ->orWhere('yy_num', 'like', '%' . $key . '%')
                        ->orWhere('tell', 'like', '%' . $key . '%')
                        ->orWhere('wechat', 'like', '%' . $key . '%')
                        ->orWhere('area', 'like', '%' . $key . '%')
                        ->orWhere('sex', '==',  $key )
                        ->latest()
                        ->paginate(12)
                ]);
            }elseif ($model=='modal'){
                $options=[];
                if (!empty($request->input('condition'))){array_push($options,['condition','=',$request->input('condition')]);}
                if (!empty($request->input('channel'))){array_push($options,['channel','=',$request->input('channel')]);}
                if (!empty($request->input('doctor'))){array_push($options,['doctor','=',$request->input('doctor')]);}
                if (!empty($request->input('consult'))){array_push($options,['consult','=',$request->input('consult')]);}
                if (!empty($request->input('user'))){array_push($options,['uid','=',$request->input('user')]);}
                if (!empty($request->input('cfz'))){array_push($options,['cfz','=',$request->input('cfz')]);}
                if (!empty($request->input('grade'))){array_push($options,['grade','=',$request->input('grade')]);}
                if (!empty($request->input('created_at_start'))){array_push($options,['created_at','>=',Carbon::createFromFormat('Y-m-d',$request->input('created_at_start'))->startOfDay()]);}
                if (!empty($request->input('created_at_end'))){array_push($options,['created_at','<=',Carbon::createFromFormat('Y-m-d',$request->input('created_at_end'))->endOfDay()]);}
                if (!empty($request->input('pubdate_start'))){array_push($options,['pubdate','>=',Carbon::createFromFormat('Y-m-d',$request->input('pubdate_start'))->startOfDay()]);}
                if (!empty($request->input('pubdate_end'))){array_push($options,['pubdate','<=',Carbon::createFromFormat('Y-m-d',$request->input('pubdate_end'))->endOfDay()]);}
                if (!empty($request->input('okdate_start'))){array_push($options,['okdate','>=',Carbon::createFromFormat('Y-m-d',$request->input('okdate_start'))->startOfDay()]);}
                if (!empty($request->input('okdate_end'))){array_push($options,['okdate','<=',Carbon::createFromFormat('Y-m-d',$request->input('okdate_end'))->endOfDay()]);}
                $members= Member::select('id','name', 'age','wechat','yy_num','hid','condition','tell','uid','description','consult','created_at','pubdate','okdate')
                    ->where('tell_num','<',config('yyxt.tell_num'))
                    ->whereIn('condition',[2,3,4,5])
                    ->where([
                        ['pubdate','>=',Carbon::now()->startOfDay()],
                        ['pubdate','<=',Carbon::now()->endOfDay()],
                    ])
                    ->where($options);
                if (!empty($request->input('disease'))){
                    $members=$members->whereRaw("FIND_IN_SET(".$request->input('disease').",disease)");
                }
                return view('track.index', [
                    'hospitals'=>Aiden::getAllHospitalsArray(),
                    'consults'=>Aiden::getAllConsultsArray(),
                    'channels'=>Aiden::getAllChannelsArray(),
                    'conditions'=>Condition::select('id','display_name')->get(),
                    'conditionsArray'=>Aiden::getAllConditionsArray(),
                    'users'=>Aiden::getAllUsersArray(),
                    'activeUsers'=>Aiden::getAllActiveUsersArray(),
                    'diseases'=>Aiden::getAllDiseasesArray(),
                    'doctors'=>Aiden::getAllDoctorsArray(),
                    'members' => $members->orderBy('id','desc')->paginate(12)
                ]);
            }elseif ($model=='quick'){
                $type=$request->input('type');
                if ($type=='today-add'){
                    $members= Member::select('id','name', 'age','wechat','yy_num','hid','condition','tell','uid','description','consult','created_at','pubdate','okdate')
                        ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
                }elseif ($type=='today-arrive'){
                    $members= Member::select('id','name', 'age','wechat','yy_num','hid','condition','tell','uid','description','consult','created_at','pubdate','okdate')
                        ->whereBetween('pubdate', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
                }elseif ($type=='today-arrived'){
                    $members= Member::select('id','name', 'age','wechat','yy_num','hid','condition','tell','uid','description','consult','created_at','pubdate','okdate')
                        ->whereBetween('okdate', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
                }elseif ($type=='tomorrow-arrive'){
                    $members= Member::select('id','name', 'age','wechat','yy_num','hid','condition','tell','uid','description','consult','created_at','pubdate','okdate')
                        ->whereBetween('pubdate', [Carbon::tomorrow()->startOfDay(), Carbon::tomorrow()->endOfDay()]);
                }else{
                    abort('403');
                }
                return view('track.index', [
                    'hospitals'=>Aiden::getAllHospitalsArray(),
                    'consults'=>Aiden::getAllConsultsArray(),
                    'channels'=>Aiden::getAllChannelsArray(),
                    'conditions'=>Condition::select('id','display_name')->get(),
                    'conditionsArray'=>Aiden::getAllConditionsArray(),
                    'users'=>Aiden::getAllUsersArray(),
                    'activeUsers'=>Aiden::getAllActiveUsersArray(),
                    'diseases'=>Aiden::getAllDiseasesArray(),
                    'doctors'=>Aiden::getAllDoctorsArray(),
                    'members' => $members->whereIn('condition',[2,3,4,5])
                        ->where('tell_num','<',config('yyxt.tell_num'))
                        ->where([
                            ['pubdate','>=',Carbon::now()->startOfDay()],
                            ['pubdate','<=',Carbon::now()->endOfDay()],
                        ])->orderBy('id','desc')->paginate(12)
                ]);
            } else {
                return redirect()->route('track.index');
            }
        }
        abort(403);
    }

    public function getTrackNumber()
    {
        $number=Member::whereIn('condition',[2,3,4,5])
            ->where('tell_num','<',config('yyxt.tell_num'))
            ->where([
                ['pubdate','>=',Carbon::now()->startOfDay()],
                ['pubdate','<=',Carbon::now()->endOfDay()],
            ])->count();
        $status=0;
        if ($number){$status=1;}
        $data=['status'=>$status,'number'=>$number];
        return response()->json($data);
    }
}
