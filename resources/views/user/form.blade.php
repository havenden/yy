@section('css')
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
<div class="card-body">
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label"><span class="text-red">*</span>ID</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{empty($errors->first('name'))?'':'is-invalid'}}" id="name" name="name" placeholder="{{empty($errors->first('name'))?'登录id: eg: xiao_ming (用于登录)':$errors->first('name')}}" value="{{isset($user)?$user->name:old('name')}}" {{ isset($user)?'disabled':'' }}>
        </div>
    </div>
    <div class="form-group row">
        <label for="displayName" class="col-sm-2 col-form-label"><span class="text-red">*</span>姓名</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{empty($errors->first('display_name'))?'':'is-invalid'}}" id="displayName" name="display_name" placeholder="{{empty($errors->first('display_name'))?'标识 eg: xiao_ming':$errors->first('display_name')}}" value="{{isset($user)?$user->display_name:old('display_name')}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="tell" class="col-sm-2 col-form-label">电话</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="tell" name="tell" placeholder="电话" value="{{ isset($user)?$user->tell:'' }}">
        </div>
    </div>
    <div class="form-group row">
        <label for="hospitals" class="col-sm-2 col-form-label"><span class="text-red">*</span>项目/医院</label>
        <div class="col-sm-10 select2-purple">
            <select class="select2 form-control" name="hospitals[]" multiple="multiple" data-placeholder="选择项目" data-dropdown-css-class="select2-purple" style="width: 100%;">
                @if(isset($hospitals))
                    @foreach($hospitals as $id => $displayName)
                        <option value="{{ $id }}" {{ isset($user)&&$user->userHasHospital($user,$id)?'selected':'' }}>{{ $displayName }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="roles" class="col-sm-2 col-form-label"><span class="text-red">*</span>用户组</label>
        <div class="col-sm-10 select2-purple">
            <select class="select2 form-control" name="roles[]" multiple="multiple" data-placeholder="选择用户组" data-dropdown-css-class="select2-purple" style="width: 100%;">
                @if(isset($roles))
                @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ isset($user)&&$user->hasRole($role->name)?'selected':'' }}>{{ $role->display_name }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="password" class="col-sm-2 col-form-label">密码</label>
        <div class="col-sm-10">
            <input type="password" class="form-control {{empty($errors->first('password'))?'':'is-invalid'}}" id="password" name="password" placeholder="密码">
        </div>
    </div>
    <div class="form-group row">
        <label for="status" class="col-sm-2 col-form-label"><span class="text-red">*</span>状态</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_active" id="status" {{isset($user)&&$user->is_active==1?'checked':''}} {{isset($user)?'':'checked'}} value="1">
                <label class="form-check-label" for="status">正常</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_active" id="status2" {{isset($user)&&$user->is_active==0?'checked':''}} value="0">
                <label class="form-check-label" for="status2">关闭</label>
            </div>
        </div>
    </div>
</div>
<!-- /.card-body -->
@section('javascripts')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2();
    </script>
@endsection
