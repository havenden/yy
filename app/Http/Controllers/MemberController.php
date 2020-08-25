<?php

namespace App\Http\Controllers;

use App\Aiden;
use App\Channel;
use App\Condition;
use App\Consult;
use App\Disease;
use App\Doctor;
use App\Http\Requests\storeMemberRequest;
use App\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        dd(Member::select('id','name', 'age','wechat','yy_num','hid','condition','tell','uid','description','consult','created_at','pubdate','okdate')->orderBy('created_at','desc')->limit(1)->get());
        if(Auth::user()->id==1||Auth::user()->hasPermissionTo('members_read')){
            return view('member.index',
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
                    'members' => Member::select('id','name', 'age','wechat','yy_num','hid','condition','tell','uid','description','consult','created_at','pubdate','okdate')->orderBy('id','desc')->paginate(18),
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('members_create')) {
            $user=Auth::user();
            $hospitalId=Aiden::getActiveHospitalId($user);
            return view('member.create',[
                'hospitals'=>$user->hospitals,
                'channels'=>Channel::select('id','display_name')->get(),
                'consults'=>Consult::select('id','display_name')->get(),
                'conditions'=>Condition::select('id','display_name')->get(),
                'diseases'=>Disease::where('hid',$hospitalId)->select('id','display_name')->get(),
                'doctors'=>Doctor::where('hid',$hospitalId)->select('id','name')->get(),
            ]);
        }
        abort(401,'权限不足');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeMemberRequest $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('members_create')){
            if (Member::createMember($request)) {
                return redirect()->route('member.index')->with('success', '添加成功！');
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
    public function edit(Request $request, $id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('members_update')){
            $user=Auth::user();
            $hospitalId=Aiden::getActiveHospitalId($user);
            return view('member.update', [
                'hospitals'=>$user->hospitals,
                'channels'=>Channel::select('id','display_name')->get(),
                'consults'=>Consult::select('id','display_name')->get(),
                'conditions'=>Condition::select('id','display_name')->get(),
                'conditionsArray'=>Aiden::getAllConditionsArray(),
                'diseases'=>Disease::where('hid',$hospitalId)->select('id','display_name')->get(),
                'doctors'=>Doctor::where('hid',$hospitalId)->select('id','name')->get(),
                'member' => Member::findOrFail($id),
                'referer'=>$request->headers->get('referer')
            ]);
        }
        abort(401);
    }

    /**
     * Update the specified resource in storage.
     * @param storeMemberRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(storeMemberRequest $request, $id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('members_update')){
            if (Member::updateMember($request,$id)) {
                $referer=$request->referer;
                if (empty($referer)){
                    return redirect()->route('member.index')->with('success', '更新成功！');
                }else{
                    return redirect($referer)->with('success', '更新成功！');
                }

            }
        }
        abort(401);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request,$id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('members_delete')){
            $member=Member::findOrFail($id);
            if ($member->addon->delete()&&$member->delete()) {
                return redirect($request->headers->get('referer'))->with('success', '删除成功！');
            }
        }
        abort(401);
    }

    public function condition(Request $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('members_read')){
            $referer=$request->header('referer');
            $memberId=$request->input('mid');
            $condition=$request->input('condition');
            $member=Member::findOrFail($memberId);
            $member->condition=$condition;
            $member->save();
            return redirect($referer);
        }
        abort(401);
    }

    public function search(Request $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('members_read')) {
            $key = $request->input('key');
            $model = $request->input('model');
            if (!empty($key)) {
                return view('member.index', [
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
                if ($request->input('pubdate')=='all'){array_push($options,['pubdate','!=','']);}
                if (!empty($request->input('pubdate_start'))){array_push($options,['pubdate','>=',Carbon::createFromFormat('Y-m-d',$request->input('pubdate_start'))->startOfDay()]);}
                if (!empty($request->input('pubdate_end'))){array_push($options,['pubdate','<=',Carbon::createFromFormat('Y-m-d',$request->input('pubdate_end'))->endOfDay()]);}
                if (!empty($request->input('okdate_start'))){array_push($options,['okdate','>=',Carbon::createFromFormat('Y-m-d',$request->input('okdate_start'))->startOfDay()]);}
                if (!empty($request->input('okdate_end'))){array_push($options,['okdate','<=',Carbon::createFromFormat('Y-m-d',$request->input('okdate_end'))->endOfDay()]);}
                $members= Member::select('id','name', 'age','wechat','yy_num','hid','condition','tell','uid','description','consult','created_at','pubdate','okdate')
                    ->where($options);
                if (!empty($request->input('disease'))){
                    $members=$members->whereRaw("FIND_IN_SET(".$request->input('disease').",disease)");
                }
                return view('member.index', [
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
                return view('member.index', [
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
            } else {
                return redirect()->route('member.index');
            }
        }
        abort(403);
    }

    public function getTracks(Request $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('tracks_read')) {
            $mid = $request->input('mid');
            $member = Member::find($mid);
            $data = [
                'status' => 0,
                'member' => [],
                'tracks' => [],
                'conditions'=>Aiden::getAllConditionsArray()
            ];
            if ($member) {
                $data['member']['name'] = $member->name . '' . (!empty($member->sex) ? ' | ' . $member->sex : '') . '' . (!empty($member->age) ? ' | ' . $member->age : '');
                $data['member']['id'] = $member->id;
                $data['member']['pubdate'] = $member->pubdate;
                $data['member']['tell'] = $member->tell;
                $data['member']['hospital'] = (Aiden::getAllHospitalsArray())[$member->hid];
                $data['member']['disease'] = '';
                $data['member']['condition'] = empty($member->condition)?'':$member->condition;
                $data['member']['doctor'] = empty($member->doctor) ? '' : (Aiden::getAllDoctorsArray())[$member->doctor];
                if ($member->disease) {
                    $diseases = explode(',', $member->disease);
                    foreach ($diseases as $disease) {
                        $data['member']['disease'] .= (Aiden::getAllDiseasesArray())[$disease] . ' ';
                    }
                }
                $data['member']['description'] = empty($member->description) ? '' : $member->description;
                $data['status'] = 1;
            }
            $tracks = $member->tracks;
            if ($member->tracks->count() > 0) {
                foreach ($tracks as $track) {
                    $data['tracks'][] = [
                        'user' => (Aiden::getAllUsersArray())[$track->uid],
                        'track_type' => (Aiden::getAllTracksType())[$track->track_type],
                        'created_at' => ($track->created_at)->toDateTimeString(),
                        'content' => $track->content,
                    ];
                }
            }
            return response()->json($data);
        }
    }

    public function getInfos(Request $request)
    {
        $mid = $request->input('mid');
        $member = Member::find($mid);
        $data = [
            'status' => 0,
        ];
        if ($member) {
            $data['member']['name'] = $member->name . '' . (!empty($member->sex) ? ' | ' . $member->sex : '') . '' . (!empty($member->age) ? ' | ' . $member->age : '');
            $data['member']['id'] = $member->id;
            $data['member']['created_at'] = !empty($member->created_at)?$member->created_at->toDateTimeString():'';
            $data['member']['pubdate'] = !empty($member->pubdate)?$member->pubdate->toDateTimeString():'';
            $data['member']['yy_num'] = $member->yy_num;
            $data['member']['user'] = isset((Aiden::getAllUsersArray())[$member->uid])?(Aiden::getAllUsersArray())[$member->uid]:'不存在';
            $data['member']['tell'] = $member->tell;
            $data['member']['wechat'] = $member->wechat;
            $data['member']['hospital'] = isset((Aiden::getAllHospitalsArray())[$member->hid])?(Aiden::getAllHospitalsArray())[$member->hid]:'不存在';
            $data['member']['disease'] = '';
            $data['member']['condition'] = isset((Aiden::getAllConditionsArray())[$member->condition])?(Aiden::getAllConditionsArray())[$member->condition]:'';
            $data['member']['doctor'] = isset((Aiden::getAllDoctorsArray())[$member->doctor])?(Aiden::getAllDoctorsArray())[$member->doctor]:'不存在';
            $data['member']['url'] = $member->url;
            $data['member']['keywords'] = $member->keywords;
            $data['member']['edit_log'] = $member->edit_log;
            $data['member']['change_log'] = $member->change_log;
            if ($member->disease) {
                $diseases = explode(',', $member->disease);
                foreach ($diseases as $disease) {
                    $data['member']['disease'] .= (Aiden::getAllDiseasesArray())[$disease] . ' ';
                }
            }
            $data['member']['description'] = empty($member->description) ? '' : $member->description;
            $data['member']['body'] = $member->addon->body;
            $data['status'] = 1;
        }
        return response()->json($data);
    }

}
