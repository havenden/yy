<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            ['id' => '1','name' => 'admin', 'display_name' => '管理员',  'is_active' => '1','tell' => '','hid'=>12,'password' => Hash::make('adming')],
          ]);
        $users=\App\User::where('id','>',1)->get();
        foreach ($users as $user){
            $user->hospitals()->attach(1);
        }
//        DB::table('channels')->insert([
////            ['id' => '1','name' => 'sg',   'display_name' => '搜狗',       'status' => '1'],
////            ['id' => '2','name' => 'sm',   'display_name' => '神马',       'status' => '1'],
////            ['id' => '3','name' => 'bd',   'display_name' => '百度',       'status' => '1'],
////            ['id' => '4','name' => '360',  'display_name' => '360',        'status' => '1'],
////            ['id' => '5','name' => 'wgj',  'display_name' => '无轨迹',      'status' => '1'],
////            ['id' => '6','name' => 'zjjwx','display_name' => '直接加微信',  'status' => '2'],
////            ['id' => '7','name' => 'xwtzw','display_name' => '商务通转微',  'status' => '2'],
////            ['id' => '8','name' => 'xwtzd','display_name' => '商务通转电',  'status' => '2'],
////            ['id' => '9','name' => ' jxdy','display_name' => '进线电话',    'status' => '2'],
////            ['id' => '12','name' => 'jc',  'display_name' => '市场',        'status' => '1'],
////            ['id' => '11','name' => 'gzsj','display_name' => '工作电话进线','status' => '1'],
////            ['id' => '13','name' => 'ymzjjw','display_name' => '口臭转微','status' => '1']
////        ]);
        //医院
        DB::table('hospitals')->insert([
            ['id' => '12', 'name' => 'whsznyy',   'display_name' => '武汉送子鸟医院'],
        ]);
        //为医院/项目 添加表
        \App\TableHandle::copyTableStructure('members','members_12');
        \App\TableHandle::copyTableStructure('addons','addons_12');
        \App\TableHandle::copyTableStructure('ghs','ghs_12');
        \App\TableHandle::copyTableStructure('tracks','tracks_12');
        \App\TableHandle::copyTableStructure('swts','swts_12');
//        DB::table('consults')->insert([
//            ['id' => '1','name'=>'swt','display_name' => '商务通'],
//            ['id' => '2','name'=>'swt-wx','display_name' => '商务通转微'],
//            ['id' => '3','name'=>'swt-tel','display_name' => '商务通转电'],
//            ['id' => '4','name'=>'tel120','display_name' => '120电话',],
//            ['id' => '5','name'=>'wx','display_name' => '直接加微信'],
//            ['id' => '6','name'=>'other','display_name' => '其他'],
//            ['id' => '7','name'=>'market120','display_name' => '市场120'],
//            ['id' => '8','name'=>'marketwx','display_name' => '市场微信'],
//            ['id' => '9','name'=>'market120-wx','display_name' => '市场120转微信']
//        ]);
        DB::table('doctors')->insert([
            ['id' => '1','hid'=>'12','name'=>'罗善芝'],
            ['id' => '2','hid'=>'12','name'=>'孔丹'],
            ['id' => '3','hid'=>'12','name'=>'金慧娟'],
            ['id' => '4','hid'=>'12','name'=>'吕宏炳'],
        ]);
        DB::table('conditions')->insert([
            ['id' => '1','name'=>'yd','display_name'=>'已到并挂号'],
            ['id' => '2','name'=>'fz','display_name'=>'复诊'],
            ['id' => '3','name'=>'wz','display_name'=>'未知'],
        ]);
        //权限
        $role=\Spatie\Permission\Models\Role::create([
            'name'=>'admin','display_name'=>'管理员'
        ]);
        $superAdmin=\App\User::findOrFail(1);
        $superAdmin->hospitals()->attach([1]);
        $superAdmin->assignRole($role);
        $slugs=[
            'users'=>'用户',
            'roles'=>'角色',
            'permissions'=>'权限',
            'channels'=>'媒体来源',
            'conditions'=>'客户状态',
            'consults'=>'搜索引擎',
            'diseases'=>'病种',
            'doctors'=>'医生',
            'ghs'=>'挂号',
            'hospitals'=>'医院',
            'logs'=>'日志',
            'members'=>'客户信息',
            'tracks'=>'回访',
            'swts'=>'商务通',
        ];
        $perms=['read'=>'查看', 'create'=>'新增', 'update'=>'更新', 'delete'=>'删除'];
        foreach ($slugs as $k=>$v){
            foreach ($perms as $m=>$n){
                $permission=Permission::create([
                    'name' => $k.'_'.$m,
                    'display_name' => $n.$v,
                ]);
                $role->givePermissionTo($permission);
            }
        }
        $roles=[
            ['id' => '2','name' => 'ke_fu','display_name'=>'客服组'],
            ['id' => '3', 'name' => 'dao_yi','display_name'=>'导医/接待组'],
            ['id' => '4', 'name' => 'jing_jia','display_name'=>'竞价组'],
            ['id' => '5', 'name' => 'master','display_name'=>'项目主管'],
        ];
        foreach ($roles as $role){
            Role::create($role);
        }
        //病种
        DB::table('diseases')->insert([
            ['id' => '1','hid'=>'12','name'=>'da','display_name' => 'A-输卵管性不孕'],
            ['id' => '2','hid'=>'12','name'=>'db','display_name' => 'B-多囊卵巢'],
            ['id' => '3','hid'=>'12','name'=>'dc','display_name' => 'C-胚胎停育，自然流产'],
            ['id' => '4','hid'=>'12','name'=>'dd','display_name' => 'D-卵巢早衰'],
            ['id' => '5','hid'=>'12','name'=>'de','display_name' => 'E-免疫性不孕'],
            ['id' => '6','hid'=>'12','name'=>'df','display_name' => 'F-备孕三个月内'],
            ['id' => '7','hid'=>'12','name'=>'dg','display_name' => 'G-卵巢性不孕'],
            ['id' => '8','hid'=>'12','name'=>'dh','display_name' => 'H-月经不调，激素异常'],
            ['id' => '9','hid'=>'12','name'=>'di','display_name' => 'I-卵泡发育异常，排卵障碍'],
            ['id' => '10','hid'=>'12','name'=>'dj','display_name' => 'J-子宫性不孕，宫腔黏连，子宫内膜异常，腺肌症'],
            ['id' => '11','hid'=>'12','name'=>'dm','display_name' => 'M-精液异常（未检查）'],
            ['id' => '12','hid'=>'12','name'=>'dn','display_name' => 'N-男性炎症（包皮过长，包茎，前列腺炎，精索静脉曲张）'],
            ['id' => '13','hid'=>'12','name'=>'do','display_name' => 'o-妇科炎症（各种妇科炎症）'],
            ['id' => '14','hid'=>'12','name'=>'dp','display_name' => 'P-不孕不育待查'],
            ['id' => '15','hid'=>'12','name'=>'dq','display_name' => 'Q-无精症'],
            ['id' => '16','hid'=>'12','name'=>'dr','display_name' => 'R-少精，弱精，死精，畸形精子'],
            ['id' => '17','hid'=>'12','name'=>'ds','display_name' => 'S-死精症'],
            ['id' => '18','hid'=>'12','name'=>'dt','display_name' => 'T-其他（人流，保胎等等）'],
            ['id' => '19','hid'=>'12','name'=>'dw','display_name' => 'W-性功能障碍'],
            ['id' => '20','hid'=>'12','name'=>'dx','display_name' => 'X-试管，人授'],
            ['id' => '21','hid'=>'12','name'=>'dy','display_name' => 'Y-吻合（输卵，精管复通）'],
            ['id' => '22','hid'=>'12','name'=>'dz','display_name' => 'Z-医院品牌词'],
        ]);


    }
}
