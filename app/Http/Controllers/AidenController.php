<?php

namespace App\Http\Controllers;

use App\Addon;
use App\Aiden;
use App\Gh;
use App\Member;
use App\Track;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AidenController extends Controller
{
    //同步患者信息
    public function updateMember()
    {

        ini_set('memory_limit',-1);
        set_time_limit(0);
        //清除数据
        Member::truncate();
        Addon::truncate();
        //
        $odb=DB::connection('mysql_source');
        $archives=$odb->table('dl_archives')->where('arcrank','>','-2')->get();
        foreach ($archives as $archive){

            $addonArchive=$odb->table('dl_addonarticle')->where('aid',$archive->id)->first();
            if (Member::where('id', $archive->id)->count() > 0){
                //已存在的数据
            }else{
                $member=new Member();
                $member->id=$archive->id;
                $member->name=$archive->name;
                $member->uid=$archive->mid;
                $member->tell=$archive->tell;
                $member->tell_num=$archive->tell_num?$archive->tell_num:0;
                if (!empty($archive->pubdate)){
                    $member->pubdate=Carbon::create(str_replace('.','-',$archive->pubdate))->toDateTimeString();
                }
                $member->hid=$archive->typeid?$archive->typeid:1;
                $member->grade=$archive->typeid2;
                if (!empty($archive->flag)){
                    $member->channel=Aiden::getChannelIdFromName($archive->flag);
                }
                $member->consult=$archive->zxfs?$archive->zxfs:6;
                $cKeys=[1,-1,2,3,4,5,6,9,10];
                $cArray=[
                    '1'=>1,//已到
                    '-1'=>2,//未到
                    '2'=>3,//回访
                    '3'=>4,//预约
                    '4'=>5,//
                    '5'=>5,
                    '6'=>6,
                    '9'=>7,
                    '10'=>8,
                ];
                if (in_array($archive->scores,$cKeys)){
                    $member->condition=$cArray[$archive->scores];
                }else{
                    $member->condition=9;
                }

                $member->disease=$archive->disease;
                if (!empty($archive->doctor)){
                    $member->doctor=intval($archive->doctor);
                }
                $member->yy_num=$archive->yy_num;
                $member->description=$archive->content;
                $member->age=intval($archive->age);
                $member->sex=$archive->sex;
                $member->cfz=$archive->cfz;
                $member->wechat=$archive->wechat;
                $member->area=$archive->area;
                $member->url=$archive->url;
                $member->keywords=$archive->keywords;
                $member->created_at=$archive->senddate;
                $member->edit_log=$archive->edit_log;
                $member->change_log=$archive->chang_log;
                if (!empty($archive->okdata)){
                    $member->okdate=Carbon::create(str_replace('.','-',$archive->okdata))->toDateTimeString();
                }

                $member->save();
                $addon=Addon::create([
                    'mid'=>$addonArchive->aid,
                    'body'=>$addonArchive->body?$addonArchive->body:'',
                ]);
                $member->addon()->save($addon);
            }

        }
        return redirect()->route('member.index')->with('success','同步客户信息成功');
    }
    //同步患者回访信息
    public function updateTrack()
    {
        ini_set('memory_limit',-1);
        set_time_limit(0);
        Track::truncate();
        $odb=DB::connection('mysql_source');
        $dl_tacks=$odb->table('dl_tack')->get();
        foreach ($dl_tacks as $dl_tack) {
            $track=new Track();
            $track->uid=$dl_tack->mid;
            $track->mid=$dl_tack->typeid;
            $track->content=$dl_tack->arcrank;
            $track->track_type=$dl_tack->typeid2;
            $track->created_at=$dl_tack->senddate?Carbon::create(str_replace('.','-',$dl_tack->senddate))->toDateTimeString():'';
            $track->save();
        }
        return redirect()->route('member.index')->with('success','同步回访信息成功');
    }
    //同步在线挂号
    public function updateGh()
    {
        ini_set('memory_limit',-1);
        set_time_limit(0);
        Gh::truncate();
        $odb=DB::connection('mysql_source');
        $dl_ghs=$odb->table('dl_gh')->get();
        foreach ($dl_ghs as $dl_gh) {
            $gh=new Gh();
            $gh->gh_name=$dl_gh->gh_name;
            $gh->gh_age=$dl_gh->gh_age;
            $gh->gh_sex=$dl_gh->gh_sex;
            $gh->gh_tel=$dl_gh->gh_tel;
            $gh->gh_ref=$dl_gh->gh_ref;
            $gh->gh_office=$dl_gh->gh_office;
            $gh->gh_disease=$dl_gh->gh_disease;
            $gh->gh_description=$dl_gh->gh_description;
            $gh->status=$dl_gh->customer_condition;
            $gh->addons=$dl_gh->addons;
            $gh->gh_date=(new Carbon($dl_gh->gh_date))->toDateTimeString();
            $gh->created_at=(new Carbon($dl_gh->created_at))->toDateTimeString();
            $gh->updated_at=(new Carbon($dl_gh->updated_at))->toDateTimeString();
            $gh->save();
        }
        return redirect()->route('gh.index')->with('success','同步回访信息成功');
    }
}
