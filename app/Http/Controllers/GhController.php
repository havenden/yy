<?php

namespace App\Http\Controllers;

use App\Aiden;
use App\Disease;
use App\Gh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GhController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->id==1||Auth::user()->hasPermissionTo('ghs_read')){
            return view('gh.index',
                [
                    'hospitals'=>Aiden::getAllHospitalsArray(),
//                    'consults'=>Aiden::getAllConsultsArray(),
//                    'channels'=>Aiden::getAllChannelsArray(),
//                    'conditions'=>Condition::select('id','display_name')->get(),
//                    'conditionsArray'=>Aiden::getAllConditionsArray(),
//                    'users'=>Aiden::getAllUsersArray(),
//                    'activeUsers'=>Aiden::getAllActiveUsersArray(),
                    'diseases'=>Aiden::getAllDiseasesArray(),
//                    'doctors'=>Aiden::getAllDoctorsArray(),
                    'ghs' => Gh::select('id','gh_name','gh_age', 'gh_sex','gh_tel','gh_ref','gh_office','gh_disease','gh_date','gh_description','status','addons','created_at')->orderBy('id','desc')->paginate(18),
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
    public function edit(Request $request,$id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('ghs_update')){
            $user=Auth::user();
            $hospitalId=Aiden::getActiveHospitalId($user);
            return view('gh.update', [
                'hospitals'=>Aiden::getAllHospitalsArray(),
//                'channels'=>Channel::select('id','display_name')->get(),
//                'consults'=>Consult::select('id','display_name')->get(),
//                'conditions'=>Condition::select('id','display_name')->get(),
//                'conditionsArray'=>Aiden::getAllConditionsArray(),
                'diseases'=>Disease::where('hid',$hospitalId)->select('id','display_name')->get(),
//                'doctors'=>Doctor::where('hid',$hospitalId)->select('id','name')->get(),
                'gh' => Gh::findOrFail($id),
                'referer'=>$request->headers->get('referer')
            ]);
        }
        abort(401);
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('ghs_update')) {
            if (Gh::updateGh($id,$request)){
                $url=$request->input('referer')?$request->input('referer'):route('gh.index');
                return redirect($url)->with('success','更新成功');
            }
        }
        abort(401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('ghs_delete')) {
            if (Gh::destroy($id)){
                $url=$request->header('referer')?$request->header('referer'):route('gh.index');
                return redirect($url)->with('success','删除成功');
            }
        }
        abort(401);
    }
}
