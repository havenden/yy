<?php

namespace App\Http\Controllers;

use App\Aiden;
use App\Imports\SwtsImport;
use App\Jobs\ExcelImport;
use App\Swt;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SwtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->id==1||Auth::user()->hasPermissionTo('swts_read')){
            return view('swt.index',
                [
                    'swts' => Swt::orderBy('start_time','desc')->paginate(18),
                ]
            );
        }
        return abort(401,'权限不足');
    }
    public function list()
    {
        return view('swt.list');
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
        //
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

    public function import(Request $request)
    {
        if ($request->hasFile('swt_excel')){
            $file=$request->file('swt_excel');
            $fileextension=$file->getClientOriginalExtension();
            if (in_array($fileextension,['xls','xlsx'])){
                $filename=date('Y').'_'.date('m').'_'.date('d').'_'.date('H').'_'.date('i').'_'.date('s').'.'.$fileextension;
                $path = $file->storeAs('uploadfiles',$filename);
                ExcelImport::dispatch($path,Aiden::getActiveHospitalId(Auth::user()))->onConnection('database');
                return redirect()->back()->with('success','文件上传成功，已进入任务队列，请稍后刷新！');
            }else{
                return redirect()->back()->with('error','文件格式错误');
            }
        }else{
            return redirect()->back()->with('error','请按规定上传文件');
        }
    }

    public function search(Request $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('swts_read')) {
            $key = $request->input('key');
            $model = $request->input('model');
            if (!empty($key)) {
                return view('swt.index', [
                    'swts' => Swt::where('sid', 'like', '%' . $key . '%')
                        ->orWhere('swt_id', 'like', '%' . $key . '%')
                        ->orWhere('author', 'like', '%' . $key . '%')
                        ->orWhere('authors', 'like', '%' . $key . '%')
                        ->orWhere('member_type', 'like', '%' . $key . '%')
                        ->orWhere('msg_type', 'like', '%' . $key . '%')
                        ->orWhere('chat_type', 'like', '%' . $key . '%')
                        ->orWhere('url', 'like', '%' . $key . '%')
                        ->orWhere('keyword', 'like', '%' . $key . '%')
                        ->orWhere('title', 'like', '%' . $key . '%')
                        ->orWhere('area', 'like', '%' . $key . '%')
                        ->orWhere('account', 'like', '%' . $key . '%')
                        ->orderBy('start_time','desc')
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
            }else {
                return redirect()->route('swt.index');
            }
        }
        abort(403);

    }
    public function getInfos(Request $request)
    {
        $mid = $request->input('mid');
        $member = Swt::find($mid);
        $data = [
            'status' => 0,
        ];
        if ($member) {
            $data['swt']['id'] = $member->id;
            $data['swt']['sid'] = $member->sid;
            $data['swt']['swt_id'] = $member->swt_id;
            $data['swt']['start_time'] = !empty($member->start_time)?$member->start_time:'';
            $data['swt']['author'] = $member->author;
            $data['swt']['authors'] = $member->authors;
            $data['swt']['msg_num'] = $member->msg_num;
            $data['swt']['member_type'] = $member->member_type;
            $data['swt']['msg_type'] = $member->msg_type;
            $data['swt']['chat_type'] = $member->chat_type;
            $data['swt']['url'] = $member->url;
            $data['swt']['keyword'] = $member->keyword;
            $data['swt']['area'] = $member->area;
            $data['swt']['title'] = $member->title;
            $data['swt']['account'] = $member->account;
            $data['swt']['is_effective'] = $member->is_effective==1?'有效对话':'无效对话';
            $data['swt']['is_contact'] = $member->is_contact==1?'留联':'';
            $data['status'] = 1;
        }
        return response()->json($data);
    }
}
