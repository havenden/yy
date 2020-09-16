<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSwtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('swts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sid')->nullable()->comment('编号');
            $table->string('swt_id')->nullable()->comment('商务通永久id');
            $table->timestamp('start_time')->nullable()->comment('开始访问时间');
            $table->string('author')->nullable()->comment('初始客服');
            $table->string('authors')->nullable()->comment('参与客服');
            $table->integer('msg_num')->nullable()->comment('客人讯息数');
            $table->string('member_type')->nullable()->comment('客人类别');
            $table->string('msg_type')->nullable()->comment('对话类型');
            $table->string('chat_type')->nullable()->comment('对话类别：转qq');
            $table->text('url')->nullable()->comment('对话来源');
            $table->text('addr')->nullable()->comment('初次访问');
            $table->string('keyword')->nullable()->comment('关键词');
            $table->string('area')->nullable()->comment('地域');
            $table->string('title')->nullable()->comment('名称');
            $table->string('account')->nullable()->comment('账户后缀');
            $table->integer('is_contact')->default(0)->comment('是否留联');
            $table->integer('is_effective')->default(0)->comment('是否有效');
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
        Schema::dropIfExists('swts');
    }
}
