<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('import.index');
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
        $file=$request->file('excel');
        $filename=$file->getClientOriginalName();
        $path = $request->file('excel')->storeAs('file',$filename);
//        Excel::import($path, function ($reader) use (&$res) {
//            $count = $reader->getSheetCount();
//                    $reader = $reader->getSheet(1);
//                    $res = $reader->toArray();
//            for ($i = 0; $i < $count; $i++) {
//                $res[] = $reader->getSheet($i)->toArray();
//
//            }
//
//        });
//        dd($res);
        $import = new UsersImport();
        $dataexcel=Excel::toArray($import, $path);
        $datasource=$dataexcel[0];
//        dd($datasource);
        ini_set('memory_limit',-1);
        set_time_limit(0);
        $odb=DB::connection('guaqyz');
//        武汉市、黄石市、十堰市、宜昌市、襄阳市、鄂州市、荆门市、孝感市、荆州市、黄冈市、咸宁市、随州市
        $local = array(0 => "未知", 1 => "武汉市", 2 => "黄石市", 3 => "襄阳市", 4 => "荆州市", 5 => "宜昌市", 6 => "十堰市", 7 => "孝感市", 8 => "荆门市", 9 => "鄂州市",10=>'恩施土家族苗族自治州', 12 => "黄冈市",13=>"咸宁市",14=>"随州市",15=>"外省市");
//    0 => "到诊时间" ok_date  时间戳
//    1 => "患者姓名" name
//    2 => "性别"     sex
//    3 => "年龄"     age
//    4 => "电话号码"  tel
//    5 => "省"
//    6 => "市"   is_local
//    7 => "县/区"
//    8 => "电话号码2" tel2
//    9 => "疾病类型" disease_id
//    10 => "专家号" 	zhuanjia_num
//    11 => "医生"    	doctor
//    12 => "状态"   status
//    13 => "就诊号"
//    14 => "媒体来源"  网络(老资源)
//    15 => "备注"  memo
//    16 => "客服" author
//    17 => "预约时间" order_date
//    18 => "添加时间"  addtime 时间戳
//    19 => "修改时间"
//    20 => "回访时间"  hf_date 时间戳
//    21 => "咨询内容" content
//    22 => "QQ" qq
//    23 => "到院情况"
//    24 => "关键词" engine_key
//    25 => "URL" from_site
//    26 => "微信" wechat
//    27 => "接待内容" 	jiedai_content
        $diseases=[171=>'A-输卵管性不孕',174=>'B-多囊卵巢',59=>'C-胚胎停育,自然流产',175=>'D-卵巢早衰',178=>'E-免疫性不孕',179=>'F-备孕三个月内',181=>'H-月经不调,激素异常',182=>'I-卵泡发育异常,排卵障碍',183=>'J-子宫性不孕,宫腔黏连,子宫内膜异常,腺肌症',184=>'M-精液异常（未检查）',185=>'N-男性炎症（包皮过长,包茎,前列腺炎,精索静脉曲张）',186=>'O-妇科炎症（各种妇科炎症）', 170=>'P-不孕不育待查', 176=>'Q-无精症',177=>'R-少精,弱精,死精,畸形精子', 187=>'T-其他（人流,保胎等等）', 180=>'G-卵巢性不孕', 188=>'W-性功能障碍', 189=>'X-试管,人授', 172=>'Z-医院品牌词', 169=>'Y-吻合（输卵,精管复通）', 320=>'S-死精症',];
//dd(intval((43728.5589351852-25569)*24*60*60));
//        $ttt=gmdate('Y-m-d H:i:s',intval((43728.5589351852-25569)*24*60*60));
//        dd($ttt);
//        dd(array_search('黄石市',$local));
        for ($i=1; $i<count($datasource); $i++)
        {
            $status=0;
            $statusa=$datasource[$i][12];
            if ($statusa=='已到并挂号'||$statusa=='已到'){
                $status=1;
            }
            if ($statusa=='复诊'){
                $status=2;
            }
            $tel=(empty($datasource[$i][4])||$datasource[$i][4]=='-')?'':$datasource[$i][4];
            $tel=preg_replace('/([\x80-\xff]*)/i','',$tel);//去掉中文
            $tel=preg_replace('/\s+/i', '',$tel);//去掉空格
            $tel2=(empty($datasource[$i][8])||$datasource[$i][8]=='-')?'':$datasource[$i][8];
            $tel2=preg_replace('/([\x80-\xff]*)/i','',$tel2);//去掉中文
            $tel2=preg_replace('/\s+/i', '',$tel2);//去掉空格
            $wechat=(empty($datasource[$i][26])||$datasource[$i][26]=='-')?'':$datasource[$i][26];
            $wechat=preg_replace('/([\x80-\xff]*)/i','',$wechat);//去掉中文
            $wechat=preg_replace('/\s+/i', '',$wechat);//去掉空格
            $odb->table('patient_12')->insert([
                'ok_date' => (empty($datasource[$i][0])||$datasource[$i][0]=='-')?0:intval(($datasource[$i][0]-25569)*24*60*60),
//                'name' => (empty($datasource[$i][1])||$datasource[$i][1]=='-')?'无':preg_replace('/\s+/i', '',$datasource[$i][1]),
                'name' => (empty($datasource[$i][1])||$datasource[$i][1]=='-')?'无':preg_replace('/\s+/i', '',$datasource[$i][1]),
                'age' => (empty($datasource[$i][3])||$datasource[$i][3]=='-')?'0':intval($datasource[$i][3]),
                'sex' => (empty($datasource[$i][2])||$datasource[$i][2]=='-')?'男':$datasource[$i][2],
                'tel' => $tel,
                'is_local' => (empty($datasource[$i][6])||$datasource[$i][6]=='-')?'0':(array_search($datasource[$i][6],$local)?array_search($datasource[$i][6],$local):'0'),
                'tel2' => $tel2,
                'disease_id' => (empty($datasource[$i][9])||$datasource[$i][9]=='-')?'':(array_search($datasource[$i][9],$diseases)?array_search($datasource[$i][9],$diseases):''),
                'zhuanjia_num' => (empty($datasource[$i][10])||$datasource[$i][10]=='-')?'':$datasource[$i][10],
                'doctor' => (empty($datasource[$i][11])||$datasource[$i][11]=='-')?'':$datasource[$i][11],
                'status' => $status,
                'jiuzhen_num' => (empty($datasource[$i][13])||$datasource[$i][13]=='-')?'':$datasource[$i][13],
                'media_from' => (empty($datasource[$i][14])||$datasource[$i][14]=='-')?'网络(老资源)':$datasource[$i][14],
                'memo' =>  (empty($datasource[$i][15])||$datasource[$i][15]=='-')?'':$datasource[$i][15],
                'author' => (empty($datasource[$i][16])||$datasource[$i][16]=='-')?'':$datasource[$i][16],
//                'author' => '市场',
                'order_date' => (empty($datasource[$i][17])||$datasource[$i][17]=='-')?0:intval(($datasource[$i][17]-25569)*24*60*60),
                'addtime' => (empty($datasource[$i][18])||$datasource[$i][18]=='-')?0:intval(($datasource[$i][18]-25569)*24*60*60),
                'content' => (empty($datasource[$i][21])||$datasource[$i][21]=='-')?'':$datasource[$i][21],
                'qq' => (empty($datasource[$i][22])||$datasource[$i][22]=='-')?'':$datasource[$i][22],
                'engine_key' => (empty($datasource[$i][24])||$datasource[$i][24]=='-')?'':Str::limit($datasource[$i][24],32,''),
                'from_site' => (empty($datasource[$i][25])||$datasource[$i][25]=='-')?'':Str::limit($datasource[$i][25],255,''),
                'wechat' => $wechat,
                'jiedai_content' => (empty($datasource[$i][27])||$datasource[$i][27]=='-')?'':$datasource[$i][27],
            ]);
        }
        dd('ok');
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
}
