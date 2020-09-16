@section('css')
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<style>select.form-control.is-invalid~span{border:solid 1px red ;border-radius: 0.3rem;}</style>
@endsection
<div class="card-body">
    <div class="row">
        <input type="hidden" name="referer" value="{{ isset($referer)?$referer:'' }}">
        <div class="col-md-4">
            <div class="card card-info bg-light">
                <div class="card-header">
                    <h3 class="card-title">基本信息</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" >姓名<small class="text-danger font-weight-lighter">*（必填）</small> </label>
                        <input type="text" class="form-control {{empty($errors->first('name'))?'':'is-invalid'}}" id="name" name="name" value="{{ old('name')?old('name'):(isset($member)?$member->name:'') }}" placeholder="{{empty($errors->first('name'))?'姓名':$errors->first('name')}}">
                    </div>
                    <div class="form-group mb-0">
                        <label for="tell">电话</label>
                    </div>
                    <div class="input-group form-group">
                        <input type="text" class="form-control" name="tell" id="tell" placeholder="电话" value="{{ old('tell')?old('tell'):(isset($member)?$member->tell:'') }}">
                        <span class="input-group-append">
                    <button type="button" class="btn btn-info btn-flat" onclick="javascript:input_date('tell', '无')">无</button>
                  </span>
                    </div>
                    <div class="form-group">
                        <label for="pubdate">预约时间</label>
                        <input type="text" class="form-control item-date" id="pubdate" name="pubdate" value="{{ old('pubdate')?old('pubdate'):(isset($member)&&!empty($member->pubdate)?\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$member->pubdate)->isoFormat('YYYY-MM-DD HH:mm'):'') }}">
                    </div>
                    <div class="form-group">
                        <div class="formControls col-sm-12">
                            日期:
                            <a class="small" href="javascript:input_date('pubdate', '{{ \Carbon\Carbon::now()->startOfDay()->format('Y-m-d H:i') }}')">[今]</a>
                            <a class="small" href="javascript:input_date('pubdate', '{{ \Carbon\Carbon::now()->add('1 day')->startOfDay()->format('Y-m-d H:i') }}')">[明]</a>
                            <a class="small" href="javascript:input_date('pubdate', '{{ \Carbon\Carbon::now()->add('2 days')->startOfDay()->format('Y-m-d H:i') }}')">[后]</a>
                            <a class="small" href="javascript:input_date('pubdate', '{{ \Carbon\Carbon::now()->add('3 days')->startOfDay()->format('Y-m-d H:i') }}')">[大后天]</a>
                            <a class="small" href="javascript:input_date('pubdate', '{{ (new \Carbon\Carbon('next saturday'))->startOfDay()->format('Y-m-d H:i') }}')">[周六]</a>
                            <a class="small" href="javascript:input_date('pubdate', '{{ (new \Carbon\Carbon('next sunday'))->startOfDay()->format('Y-m-d H:i') }}')">[周日]</a>
                            <a class="small" href="javascript:input_date('pubdate', '{{ (new \Carbon\Carbon('next monday'))->startOfDay()->format('Y-m-d H:i') }}')">[周一]</a>
                            <a class="small" href="javascript:input_date('pubdate', '{{ \Carbon\Carbon::now()->add('7 days')->startOfDay()->format('Y-m-d H:i') }}')">[一周后]</a>
                            <a class="small" href="javascript:input_date('pubdate', '{{ \Carbon\Carbon::now()->add('15 days')->startOfDay()->format('Y-m-d H:i') }}')">[半月后]</a>
                            <br>时间:
                            <a class="small" href="javascript:input_time('pubdate','00:00')">[时间不限]</a>
                            <a class="small" href="javascript:input_time('pubdate','09:00')">[9点左右]</a>
                            <a class="small" href="javascript:input_time('pubdate','11:00')">[11点左右]</a>
                            <a class="small" href="javascript:input_time('pubdate','13:00')">[1点左右]</a>
                            <a class="small" href="javascript:input_time('pubdate','15:00')">[3点左右]</a>
                            <a class="small" href="javascript:input_time('pubdate','17:00')">[5点左右]</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hospital">预约医院<small class="text-danger font-weight-lighter">*（必填）</small></label>
                        <select name="hospital" id="hospital" class="form-control select2 {{empty($errors->first('hospital'))?'':'is-invalid'}}">
                            @if(isset($hospitals)&&!empty($hospitals))
                                @foreach($hospitals as $hospital)
                                <option value="{{ $hospital->id }}" {{ old('hospital')?(($hospital->id==old('hospital'))?'selected':''):(isset($member)&&$member->hospital==$hospital->id?'selected':'') }} >{{ $hospital->display_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="channel">渠道来源</label>
                        <select name="channel" id="channel" class="form-control select2">
                            @if(isset($channels)&&!empty($channels))
                                @foreach($channels as $channel)
                                    <option value="{{ $channel->id }}" {{ old('channel')?(($channel->id==old('channel'))?'selected':''):(isset($member)&&$member->channel==$channel->id?'selected':'') }}>{{ $channel->display_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="consult">咨询方式<small class="text-danger font-weight-lighter">*（必填）</small></label>
                        <select name="consult" id="consult" class="form-control select2 {{empty($errors->first('consult'))?'':'is-invalid'}}">
                            @if(isset($consults)&&!empty($consults))
                                @foreach($consults as $consult)
                                    <option value="{{ $consult->id }}" {{ old('consult')?(($consult->id==old('consult'))?'selected':''):(isset($member)&&$member->consult==$consult->id?'selected':'') }}>{{ $consult->display_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="disease">预约病种<small class="text-danger font-weight-lighter">*（必填）</small></label>
                        <select name="disease[]" id="disease" class="form-control select2 custom-select {{empty($errors->first('disease'))?'':'is-invalid'}}" multiple>
                            @if(isset($diseases)&&!empty($diseases))
                                @foreach($diseases as $disease)
                                    <option value="{{ $disease->id }}" {{ old('disease')?(in_array($disease->id,old('disease'))?'selected':''):(isset($member)&&in_array($disease->id,explode(',',$member->disease))?'selected':'') }}>{{ $disease->display_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="doctor">预约医生</label>
                        <select name="doctor" id="doctor" class="form-control select2">
                            <option value="">选择医生</option>
                            @if(isset($doctors)&&!empty($doctors))
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor')?(($doctor->id==old('doctor'))?'selected':''):(isset($member)&&$member->doctor==$doctor->id?'selected':'') }}>{{ $doctor->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="yyNum">专家号</label>
                        <input type="text" class="form-control" id="yyNum" name="yy_num" placeholder="专家号" value="{{ old('yy_num')?old('yy_num'):(isset($member)?$member->yy_num:'') }}">
                    </div>
                    <div class="form-group">
                        <label for="description">客户预约备注</label>
                        <textarea type="text" class="form-control" id="description" name="description" placeholder="客户预约备注">{{ old('description')?old('description'):(isset($member)?$member->description:'') }}</textarea>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header" style="background-color: #1c84c6;">
                            <h3 class="card-title text-light">客户详细情况</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="age" class="col-md-3 col-form-label">年龄</label>
                                <input type="text" class="form-control col-md-9" id="age" name="age" placeholder="年龄" value="{{ old('age')?old('age'):(isset($member)?$member->age:'') }}">
                            </div>
                            <div class="form-group row">
                                <label for="sex" class="col-md-3 col-form-label">性别</label>
                                <div class="form-group col-md-9 form-control border-0 mb-0">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" name="sex" id="sex1" value="男" {{ old('sex')?(old('sex')=='男'?'checked':''):(isset($member)&&($member->sex=='男')?'checked':(isset($member)?'':'checked')) }}>
                                        <label class="custom-control-label" for="sex1">男</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" name="sex" id="sex2" value="女" {{ old('sex')?(old('sex')=='女'?'checked':''):(isset($member)&&($member->sex=='女')?'checked':'') }}>
                                        <label class="custom-control-label" for="sex2">女</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cfz" class="col-md-3 col-form-label">初复诊</label>
                                <div class="form-group col-md-9 form-control border-0 mb-0">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="cfz" id="cfz1" value="1" {{ old('cfz')?(old('cfz')=='1'?'checked':''):(isset($member)&&($member->cfz=='1')?'checked':(isset($member)?'':'checked')) }}>
                                        <label class="form-check-label" for="cfz1">初诊</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="cfz" id="cfz2" value="2" {{ old('cfz')?(old('cfz')=='2'?'checked':''):(isset($member)&&($member->cfz=='2')?'checked':'') }}>
                                        <label class="form-check-label" for="cfz2">复诊</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="cfz" id="cfz3" value="3" {{ old('cfz')?(old('cfz')=='3'?'checked':''):(isset($member)&&($member->cfz=='3')?'checked':'') }}>
                                        <label class="form-check-label" for="cfz3">来过</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="wechat" class="col-form-label col-sm-3">微信</label>
                                <div class="col-sm-9 mb-0">
                                    <input type="text" class="form-control" id="wechat" name="wechat" placeholder="微信" value="{{ old('wechat')?old('wechat'):(isset($member)?$member->wechat:'') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="area" class="col-form-label col-sm-3">地址</label>
                                <div class="col-sm-9 mb-0">
                                    <input type="text" class="form-control" id="area" name="area" placeholder="地址" value="{{ old('area')?old('area'):(isset($member)?$member->area:'佛山') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="condition" class="col-form-label col-sm-3">客户状态</label>
                                <div class="col-sm-9 mb-0">
                                <select name="condition" id="condition" name="condition" class="form-control select2" data-placeholder="Select a State">
                                    <option value="6">选择一个状态</option>
                                    @if(isset($conditions)&&!empty($conditions))
                                        @foreach($conditions as $condition)
                                            <option value="{{ $condition->id }}" {{ old('condition')?(($condition->id==old('condition'))?'selected':''):(isset($member)&&$member->condition==$condition->id?'selected':'') }}>{{ $condition->display_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header" style="background-color: #23c6c8;">
                            <h3 class="card-title text-light">客户来源信息</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="url">入口页面</label>
                                <input type="text" class="form-control" id="url" name="url" value="{{ old('url')?old('url'):(isset($member)?$member->url:'') }}">
                            </div>
                            <div class="form-group">
                                <label for="okdate">到诊时间</label>
                                <input type="text" class="form-control item-date" id="okdate" name="okdate" value="{{ old('okdate')?old('okdate'):(isset($member)&&!empty($member->okdate)?\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$member->okdate)->isoFormat('YYYY-MM-DD HH:mm'):'') }}">
                            </div>
                            <div class="form-group">
                                <label for="keywords">搜索关键词</label>
                                <input type="text" class="form-control" id="keywords" name="keywords" placeholder="搜索关键词" value="{{ old('keywords')?old('keywords'):(isset($member)?$member->keywords:'') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header" style="background-color: #1ab394;">
                            <h3 class="card-title text-light">附加信息</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-group  form-control border-0 mb-0">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" name="grade" id="grade1" value="1" {{ old('grade')?(old('grade')=='1'?'checked':''):(isset($member)&&($member->grade=='1')?'checked':'') }}>
                                        <label class="custom-control-label" for="grade1">白班</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" name="grade" id="grade2" value="2" {{ old('grade')?(old('grade')=='2'?'checked':''):(isset($member)&&($member->grade=='2')?'checked':'') }}>
                                        <label class="custom-control-label" for="grade2">晚班</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" name="grade" id="grade3" value="3" {{ old('grade')?(old('grade')=='3'?'checked':''):(isset($member)&&($member->grade=='3')?'checked':'') }}>
                                        <label class="custom-control-label" for="grade3">大夜班</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" name="grade" id="grade4" value="4" {{ old('grade')?(old('grade')=='4'?'checked':''):(isset($member)&&($member->grade=='4')?'checked':(isset($member)?'':'checked')) }}>
                                        <label class="custom-control-label" for="grade4">其它</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <textarea class="form-control" id="body" name="body" placeholder="聊天记录">{{ old('body')?old('body'):(isset($member)?$member->addon->body:'') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.card-body -->
<div class="card-footer">
    <button type="submit" class="btn btn-info float-right">保存</button>
</div>
<!-- /.card-footer -->
<!-- /.card-body -->
@section('javascripts')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/laydate/laydate.js') }}"></script>
    <script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        $('.item-date').each(function(){
            laydate.render({
                elem: this,
                trigger: 'click',
                type:'datetime',
                format: 'yyyy-MM-dd HH:mm',
            });
        });
        $('.select2').select2();
        function input_date(id, value)
        {
            var cv = document.getElementById(id).value;
            var time = cv.split(" ")[1];

            if (document.getElementById(id).disabled != true) {
                document.getElementById(id).value = value;
            }
        };

        function input_time(id, time)
        {
            var s = document.getElementById(id).value;
            if (s == '') {
                layer.alert("请先填写日期，再填写时间！");
                return;
            }
            var date = s.split(" ")[0];
            var datetime = date+" "+time;

            if (document.getElementById(id).disabled != true) {
                document.getElementById(id).value = datetime;
            }
        };
        tinymce.init({
            selector: '#body',
            language: 'zh_CN',
            // menubar: false,//去除文件栏
            branding: false,//去除右下角的'由tinymce驱动'
            elementpath: false,//左下角的当前标签路径
            // setup: function(editor) {//设置自定义功能的按钮
            //     editor.addButton("uploadimg", {//单个按钮，此处的uploading是该按钮的名称
            //         icon: "image",    //显示的图像
            //         tooltip: "上传图片",//鼠标放上去后现在是内容
            //         onclick: function () {
            //         }
            //     });
            // },
            // 编辑器宽高
            // width: 600,
            height: 500,
            // 用到的插件
            // plugins: [
            //     'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
            //     'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
            //     'save table contextmenu directionality template paste textcolor imagetools'
            // ],
            plugins: [
                'image lists table fullscreen save link',
                'save',
            ],
            // 编辑区域内容样式
            // content_css: 'css/content.css',
            // 工具栏的配置项
            toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons'
        });
    </script>
@endsection
