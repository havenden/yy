<div class="card-body">
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label"><span class="text-red">*</span>权限</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{empty($errors->first('name'))?'':'is-invalid'}}" id="name" name="name" placeholder="{{empty($errors->first('name'))?'标识 eg: users_create':$errors->first('name')}}" value="{{isset($permission)?$permission->name:old('name')}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="displayName" class="col-sm-2 col-form-label"><span class="text-red"></span>权限名</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{empty($errors->first('display_name'))?'':'is-invalid'}}" id="displayName" name="display_name" placeholder="{{empty($errors->first('display_name'))?'标识 eg: 新增用户':$errors->first('display_name')}}" value="{{isset($permission)?$permission->display_name:old('display_name')}}">
        </div>
    </div>
</div>