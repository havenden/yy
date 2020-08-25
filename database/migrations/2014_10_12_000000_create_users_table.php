<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //用户表
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('short')->nullable()->comment('编号');
            $table->integer('status')->default(1);
            $table->string('tell')->nullable();
            $table->integer('hid')->nullable()->comment('默认项目id');
            $table->string('password');
            $table->timestamps();
        });
        //患者表
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('hid')->comment('项目');
            $table->smallInteger('grade')->comment('班次');
            $table->integer('age')->nullable();
            $table->integer('consult')->comment('咨询方式');
            $table->string('sex')->nullable();
            $table->string('channel')->nullable()->comment('来源');
            $table->string('name');
            $table->string('wechat')->nullable();
            $table->string('qq')->nullable();
            $table->string('yy_num')->nullable();
            $table->string('disease')->nullable();
            $table->string('area')->nullable();
            $table->integer('tell_num')->default(0)->comment('回访次数');
            $table->timestamp('pubdate')->nullable()->comment('预约时间');
            $table->timestamp('okdate')->nullable()->comment('到诊时间');
            $table->integer('uid')->comment('客服id');
            $table->string('keywords')->nullable()->comment('关键词');
            $table->smallInteger('condition')->comment('状态');
            $table->integer('doctor')->nullable()->comment('专家');
            $table->string('tell')->nullable()->comment('电话');
            $table->text('description')->nullable()->comment('描述');
            $table->text('url')->nullable()->comment('链接');
            $table->smallInteger('cfz')->nullable()->comment('初复诊');
            $table->text('edit_log')->nullable();
            $table->text('change_log')->nullable();
            $table->timestamps();
        });
        //患者附加表
        Schema::create('addons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('mid')->comment('患者id');
            $table->longText('body')->nullable();
            $table->timestamps();
        });
        //渠道
        Schema::create('channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('display_name');
            $table->integer('status')->default(1);//1显示，2隐藏
            $table->timestamps();
        });
        //项目
        Schema::create('hospitals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('display_name');
            $table->integer('status')->default(1);//1显示，2隐藏
            $table->timestamps();
        });
        Schema::create('user_hospitals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('hospital_id');
            $table->timestamps();
        });
        //病种
        Schema::create('diseases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('hid');
            $table->string('name')->unique();
            $table->string('display_name');
            $table->timestamps();
        });
        //状态
        Schema::create('conditions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('display_name');
            $table->timestamps();
        });
        //回访表
        Schema::create('tracks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uid')->comment('客服id');
            $table->integer('mid')->comment('患者id');
            $table->text('content')->nullable();
            $table->integer('track_type')->comment('回访类型');
            $table->timestamps();
        });
        //转化表
        Schema::create('trans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uid')->comment('客服id');
            $table->string('name')->comment('客服');
            $table->integer('chatnum')->comment('对话量');
            $table->integer('chatnum_active')->comment('有效对话量');
            $table->integer('msg')->comment('信息获取量');
            $table->float('msg_rate')->comment('信息获取率');
            $table->integer('arrive')->comment('总到诊');
            $table->float('backend_trans')->comment('后端转化率');
            $table->float('arrive_rate')->comment('总到诊率');
            $table->float('arrive_rate_active')->comment('有效到诊率');
            $table->timestamp('mdate')->comment('日期');
            $table->timestamps();
        });
        //医生表
        Schema::create('doctors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('hid')->comment('医院id');
            $table->string('name');
            $table->string('num')->nullable()->comment('编号');
            $table->timestamps();
        });
        //咨询方式表
        Schema::create('consults', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('display_name');
            $table->timestamps();
        });
        //咨询方式表
        Schema::create('ghs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('gh_name')->nullable();
            $table->integer('gh_age')->nullable();
            $table->string('gh_sex')->nullable();
            $table->string('gh_tel')->nullable();
            $table->string('gh_ref')->nullable();
            $table->integer('gh_office')->nullable();
            $table->integer('gh_disease')->nullable();
            $table->timestamp('gh_date')->nullable();
            $table->string('gh_description')->nullable();
            $table->string('status')->nullable();
            $table->text('addons')->nullable();
            $table->timestamps();
        });
        //日志表
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uid');
            $table->string('url');
            $table->string('method');
            $table->string('ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('members');
        Schema::dropIfExists('addons');
        Schema::dropIfExists('channels');
        Schema::dropIfExists('hospitals');
        Schema::dropIfExists('user_hospitals');
        Schema::dropIfExists('diseases');
        Schema::dropIfExists('conditions');
        Schema::dropIfExists('tracks');
        Schema::dropIfExists('trans');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('consults');
        Schema::dropIfExists('ghs');
        Schema::dropIfExists('logs');
    }
}
