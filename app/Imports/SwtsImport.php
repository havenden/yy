<?php

namespace App\Imports;

use App\Swt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Illuminate\Support\Arr;
HeadingRowFormatter::default('none');
class SwtsImport implements ToModel,WithHeadingRow
{
    protected $hid;
    public function __construct($hid)
    {
        $this->hid = $hid;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (isset($row['客人讯息数'])&&$row['客人讯息数']>0&&$row['名称']!='测试'){
            if (is_numeric($row['编号'])){
                $swtCount=DB::table('swts_'.$this->getSwtId())->where('sid', $row['编号'])->count();
                if ($swtCount==0||empty($swtCount)){
                    $swt= new Swt($this->getSwt());
                    $swt->sid = $row['编号'] ?? '';
                    $swt->swt_id = trim($row['永久身份'],"'") ?? '';
                    $swt->start_time = ($row['开始访问时间']?Carbon::parse($row['开始访问时间'])->toDateTimeString():'') ?? ($row['开始对话时间']?Carbon::parse($row['开始对话时间'])->toDateTimeString():'')?? '';
                    $swt->author = (!empty($row['初始接待客服'])?$row['初始接待客服']:'无') ?? '';
                    $swt->authors = $row['参与接待客服'] ?? '';
                    $swt->msg_num = $row['客人讯息数'] ?? '';
                    $swt->member_type = $row['客人类别'] ?? '';
                    $swt->msg_type = $row['对话类型'] ?? '';
                    $swt->chat_type = $row['对话类别'] ?? '';
                    $swt->engine = (string)$row['访问来源'] ??  '';
                    $swt->engine_from = $this->getEngine($row['访问来源']);
                    $swt->addr = (string)$row['初次访问网址'] ??  '';
                    $swt->url = (string)$row['对话来源'] ??  '';
                    $swt->keyword = $this->clearText($row['关键词']);
                    $swt->area = $this->getArea($row['IP定位']) ?? '';
                    $swt->title = $row['名称'] ?? '';
                    $swt->os = $row['操作系统'] ?? '';
                    $swt->device = $this->getDevice($row['操作系统']) ?? '';
                    $swt->account = $this->getAccount($row['初次访问网址'],$row['对话来源']);
                    $swt->is_contact = $this->getContact(explode(',',$row['对话类别']));
                    $swt->is_effective = $this->getEffective($row['客人类别'],$row['对话类型'],explode(',',$row['对话类别']));
                    $swt->save();
                }
            }
        }
    }
    public function headingRow(): int
    {
        return 3;
    }
    public function getSwt(){
        return ['hid'=>$this->hid];
    }

    public function getDevice($device)
    {
        $agents = ["Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"];
        if (in_array($device,$agents)){
            return 'mobile';
        }else{
            return 'pc';
        }
    }
    /**
     * @param $engineStr
     * @return mixed|string
     */
    private function getEngine($engineStr)
    {
        $engines=['baidu'=>'百度','baibm'=>'劫持','sogou'=>'搜狗','sm'=>'神马','so'=>'360','xywy'=>'寻医问药','39'=>'39健康','120ask'=>'120ask快速问医生'];
        $preg= '/^http[s]{0,1}:\/\/([-\w\.]{1,})\.(baidu|baibm|sm|so|sogou|xywy|39|120ask)\.(com|cn|net)\//';
        preg_match($preg, $engineStr,$matches);
        if(!empty($matches)&&isset($engines[$matches[2]])){
            return $engines[$matches[2]];
        }else{
            return '未知';
        }

    }
    /**
     * 账户后缀
     * @param $account
     * @return mixed|string
     */
    public function getAccount($addr,$url)
    {
//        $preg= '/\&(baidu|sogou|shenma|360)[0-9_]{1}[0-9]{1,3}\&/';
//        $preg= '/\&(baidu|sogou|shenma|360)[-\w]{0,}\&/';
        $preg= '/[\#\&]{1}(baidu|sogou|shenma|360)[-_0-9]{1}[\d]{0,2}/';
        preg_match($preg, $addr,$matches1);
        preg_match($preg, $url,$matches2);
        if (!empty($matches1)){
            $ac=$matches1[0];
            $ac=trim($ac,"&");
            $ac=trim($ac,"#");
            return $ac;
        }elseif (!empty($matches2)){
            $ac=$matches2[0];
            $ac=trim($ac,"&");
            $ac=trim($ac,"#");
            return $ac;
        }else{
            $preg2= '/(xywyfc)/';
            preg_match($preg2, $addr,$matches2);
            if (!empty($matches2)){
                $ac2=$matches2[0];
                return $ac2;
            }else{
                return '';
            }
        }
    }

    /**
     * 对话类别
     * @param array $contact
     * @return int
     */
    public function getContact($contact=array())
    {
        $contacts=['预约','转QQ','转微信','转电话'];
        // 查找两个数组的交集
        $result = array_intersect($contacts, $contact);
        // 重新排序
        $result = array_values($result);
        if (!empty($result)){
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * 是否有效对话
     * @param $effective
     * @param $contact
     * @return int
     */
    public function getEffective($memberType,$msgType,$chatType)
    {
        $effective=0;
        //对话类型 msg_type
        $effective_msg_types=['极佳对话','较好对话'];
        //对话类别 chat_type '转微信','转电话','预约','转QQ'
        $effective_chat_types=['转微信','转电话','预约','转QQ'];
        //客人类型 member_type
        $uneffective_member_type=['无效-已就诊','无效咨询','掉线','广告否词','问候','重复咨询','生殖辅助','备孕3个月内'];
        if (in_array($memberType,$uneffective_member_type)){
            $effective = 0;
        }else{
            if (!empty(array_values(array_intersect($chatType,$effective_chat_types)))){
                $effective = 1;
            }else if (in_array($msgType,$effective_msg_types)){
                $effective = 1;
            }else{
                $effective = 0;
            }
        }
        return $effective;
    }

    /**
     * @param $area
     * @return mixed|string
     */
    public function getArea($area)
    {
        $local = [0 => "未知", 1 => "武汉", 2 => "黄石", 3 => "襄阳", 4 => "荆州", 5 => "宜昌", 6 => "十堰", 7 => "孝感", 8 => "荆门", 9 => "鄂州",10=>'恩施',11=>'天门', 12 => "黄冈",13=>"咸宁",14=>"随州",15=>"外省",16=>'仙桃',17=>'潜江',18=>'神农架'];
        $filtered = Arr::where($local, function ($value, $key) use ($area) {
            return strpos($area,$value)!==false;
        });
        $rea='';
        if (!empty($filtered)){
            $rea=head($filtered);
        }else{
            if (strpos($area,'襄樊')) {$rea = '襄阳';}
            elseif ($area=='湖北省'){$rea = '武汉';}
            elseif (strpos($area,'亚太地区')!==false){$rea = '武汉';}
            else{$rea = '外省';}
        }
        return $rea;
    }

    /**
     * @param $text
     * @return string|string[]|null
     */
    public function emoji($text)
    {
        //去掉emoji
        $clean_text = "";
        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, '', $text);
        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, '', $clean_text);
        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, '', $clean_text);
        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, '', $clean_text);
        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);
        $regexDingbats = '/[\x{231a}-\x{23ab}\x{23e9}-\x{23ec}\x{23f0}-\x{23f3}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);
        return $clean_text;
    }

    /**
     * @param $text
     * @return string
     */
    public function clearText($text)
    {
        //去掉含有乱码的字符串
        $keywords_j=(string)json_encode( $text);
        if (preg_match_all ("/ufffd/U", $keywords_j, $pat_array)>0){
            return '';
        }else{
            return $text;
        }
    }

    private function getSwtId()
    {
        return $this->hid;
    }


}
