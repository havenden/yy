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
            ['id' => '1','name' => 'admin',       'display_name' => '管理员',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('adming')],
            ['id' => '2','name' => 'zhangyisheng','display_name' => '张医生',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '10','name' => 'hgr',        'display_name' => '华国荣',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '11','name' => 'hwx',        'display_name' => '黄万兴',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '12','name' => 'xsf',        'display_name' => '谢绍凡',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '16','name' => 'hsj',        'display_name' => '韩诗杰',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '15','name' => 'smh',        'display_name' => '苏明杭',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '17','name' => 'wckyz',      'display_name' => '胃肠科医助','short' => 'wck2019','status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '18','name' => 'xfs',        'display_name' => '秦芳苏',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '19','name' => 'qrs',        'display_name' => '戚日帅',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '28','name' => 'lb',         'display_name' => '李博',      'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '36','name' => '22',        'display_name' => '22',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '37','name' => 'JCJ',        'display_name' => '将朝军',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '40','name' => 'lxy',        'display_name' => '李心怡',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '41','name' => 'HSJ1',        'display_name' => '黄少杰',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '42','name' => 'mjh',        'display_name' => '蒙建海',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '43','name' => 'zyw',        'display_name' => '郑渊文',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '44','name' => 'ljm',        'display_name' => '李静敏',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '45','name' => 'lds',        'display_name' => '李鼎山',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '46','name' => 'zxb',        'display_name' => '咨询部',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '47','name' => 'gy',        'display_name' => '郭逸',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '48','name' => 'cyy',        'display_name' => '陈钰莹',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
            ['id' => '49','name' => 'ch',        'display_name' => '陈辉',    'short' => '',       'status' => '1','tell' => '','hid'=>1,'password' => Hash::make('123456')],
        ]);
        $users=\App\User::where('id','>',1)->get();
        foreach ($users as $user){
            $user->hospitals()->attach(1);
        }
        DB::table('channels')->insert([
            ['id' => '1','name' => 'sg',   'display_name' => '搜狗',       'status' => '1'],
            ['id' => '2','name' => 'sm',   'display_name' => '神马',       'status' => '1'],
            ['id' => '3','name' => 'bd',   'display_name' => '百度',       'status' => '1'],
            ['id' => '4','name' => '360',  'display_name' => '360',        'status' => '1'],
            ['id' => '5','name' => 'wgj',  'display_name' => '无轨迹',      'status' => '1'],
            ['id' => '6','name' => 'zjjwx','display_name' => '直接加微信',  'status' => '2'],
            ['id' => '7','name' => 'xwtzw','display_name' => '商务通转微',  'status' => '2'],
            ['id' => '8','name' => 'xwtzd','display_name' => '商务通转电',  'status' => '2'],
            ['id' => '9','name' => ' jxdy','display_name' => '进线电话',    'status' => '2'],
            ['id' => '12','name' => 'jc',  'display_name' => '市场',        'status' => '1'],
            ['id' => '11','name' => 'gzsj','display_name' => '工作电话进线','status' => '1'],
            ['id' => '13','name' => 'ymzjjw','display_name' => '口臭转微','status' => '1']
        ]);
        //医院
        DB::table('hospitals')->insert([
            ['id' => '1', 'name' => 'fstyt',   'display_name' => '太乙堂中医院'],
            ['id' => '2','name' => 'test','display_name' => '测试']
        ]);
        //为医院/项目 添加表
        \App\TableHandle::copyTableStructure('members','members_1');
        \App\TableHandle::copyTableStructure('members','members_2');
        \App\TableHandle::copyTableStructure('addons','addons_1');
        \App\TableHandle::copyTableStructure('addons','addons_2');
        \App\TableHandle::copyTableStructure('ghs','ghs_1');
        \App\TableHandle::copyTableStructure('ghs','ghs_2');
        \App\TableHandle::copyTableStructure('ghs','ghs_2');
        \App\TableHandle::copyTableStructure('tracks','tracks_1');
        \App\TableHandle::copyTableStructure('tracks','tracks_2');
        DB::table('consults')->insert([
            ['id' => '1','name'=>'swt','display_name' => '商务通'],
            ['id' => '2','name'=>'swt-wx','display_name' => '商务通转微'],
            ['id' => '3','name'=>'swt-tel','display_name' => '商务通转电'],
            ['id' => '4','name'=>'tel120','display_name' => '120电话',],
            ['id' => '5','name'=>'wx','display_name' => '直接加微信'],
            ['id' => '6','name'=>'other','display_name' => '其他'],
            ['id' => '7','name'=>'market120','display_name' => '市场120'],
            ['id' => '8','name'=>'marketwx','display_name' => '市场微信'],
            ['id' => '9','name'=>'market120-wx','display_name' => '市场120转微信']
        ]);
        DB::table('doctors')->insert([
            ['id' => '1','hid'=>'1','name'=>'李玉根'],
            ['id' => '5','hid'=>'1','name'=>'张志刚']
        ]);
        DB::table('conditions')->insert([
            ['id' => '1','name'=>'yd','display_name'=>'已到'],
            ['id' => '2','name'=>'wd','display_name'=>'未到'],
            ['id' => '3','name'=>'hf','display_name'=>'回访'],
            ['id' => '4','name'=>'yy','display_name'=>'预约'],
            ['id' => '5','name'=>'zx','display_name'=>'咨询'],
            ['id' => '6','name'=>'gq','display_name'=>'过期'],
            ['id' => '7','name'=>'gy','display_name'=>'改约'],
            ['id' => '8','name'=>'ls','display_name'=>'流失'],
            ['id' => '9','name'=>'qt','display_name'=>'其它'],
        ]);
        //权限
        $role=\Spatie\Permission\Models\Role::create([
            'name'=>'admin','display_name'=>'管理员'
        ]);
        $superAdmin=\App\User::findOrFail(1);
        $superAdmin->hospitals()->attach([1,2]);
        $superAdmin->assignRole($role);
        $slugs=[
            'users'=>'用户',
            'roles'=>'角色',
            'permissions'=>'权限',
            'channels'=>'来源渠道',
            'conditions'=>'客户状态',
            'consults'=>'咨询方式',
            'diseases'=>'病种',
            'doctors'=>'医生',
            'ghs'=>'挂号',
            'hospitals'=>'医院',
            'logs'=>'日志',
            'members'=>'客户信息',
            'tracks'=>'回访',
            'trans'=>'转化'
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
            ['id' => '2','name' => 'zi_xun','display_name'=>'咨询组'],
            ['id' => '3', 'name' => 'ke_fu','display_name'=>'客服组'],
            ['id' => '4', 'name' => 'cai_wu','display_name'=>'财务组'],
            ['id' => '5', 'name' => 'zu_zhang','display_name'=>'组长组'],
            ['id' => '6', 'name' => 'dao_yi','display_name'=>'导医/接待组'],
            ['id' => '7', 'name' => 'yi_sheng','display_name'=>'医生分组']
        ];
        foreach ($roles as $role){
            Role::create($role);
        }
        //病种
        DB::table('diseases')->insert([
            ['id' => '1','hid'=>'1','name'=>'wt','display_name' => '胃痛'],
            ['id' => '2','hid'=>'1','name'=>'wz','display_name' => '胃胀'],
            ['id' => '3','hid'=>'1','name'=>'kg','display_name' => '打嗝、口干口苦'],
            ['id' => '4','hid'=>'1','name'=>'kc','display_name' => '口臭'],
            ['id' => '5','hid'=>'1','name'=>'ot','display_name' => '恶心、呕吐'],
            ['id' => '6','hid'=>'1','name'=>'ws','display_name' => '胃酸'],
            ['id' => '7','hid'=>'1','name'=>'wj','display_name' => '胃镜'],
            ['id' => '8','hid'=>'1','name'=>'cj','display_name' => '肠镜'],
            ['id' => '9','hid'=>'1','name'=>'hp','display_name' => 'HP'],
            ['id' => '10','hid'=>'1','name'=>'sx','display_name' => '烧心'],
            ['id' => '11','hid'=>'1','name'=>'fx','display_name' => '腹泻'],
            ['id' => '12','hid'=>'1','name'=>'bx','display_name' => '便血'],
            ['id' => '13','hid'=>'1','name'=>'bm','display_name' => '便秘'],
            ['id' => '14','hid'=>'1','name'=>'ft','display_name' => '腹痛'],
            ['id' => '15','hid'=>'1','name'=>'fz','display_name' => '腹胀'],
            ['id' => '16','hid'=>'1','name'=>'hb','display_name' => '黑便'],
            ['id' => '17','hid'=>'1','name'=>'sm','display_name' => '扫描'],
            ['id' => '18','hid'=>'1','name'=>'sdy','display_name' => '食道炎'],
            ['id' => '19','hid'=>'1','name'=>'fsxwy','display_name' => '胆汁反流性胃炎'],
            ['id' => '20','hid'=>'1','name'=>'qbxwy','display_name' => '浅表性胃炎'],
            ['id' => '21','hid'=>'1','name'=>'wsxwy','display_name' => '萎缩性胃炎'],
            ['id' => '22','hid'=>'1','name'=>'flxwy','display_name' => '糜烂性胃炎'],
            ['id' => '23','hid'=>'1','name'=>'wqy','display_name' => '胃溃疡'],
            ['id' => '24','hid'=>'1','name'=>'zcy','display_name' => '直肠炎'],
            ['id' => '25','hid'=>'1','name'=>'jcy','display_name' => '结肠炎'],
            ['id' => '26','hid'=>'1','name'=>'dbbcx','display_name' => '大便次数多、大便不成型'],
            ['id' => '27','hid'=>'1','name'=>'ywg','display_name' => '吞咽困难、食道异物感'],
            ['id' => '28','hid'=>'1','name'=>'szzc','display_name' => '十二指肠']
        ]);


    }
}
