<div class="card-body">
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label"><span class="text-red">*</span>Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{empty($errors->first('name'))?'':'is-invalid'}}" id="name" name="name" placeholder="{{empty($errors->first('name'))?'英文或拼音 eg:slug':$errors->first('name')}}" value="{{isset($hospital)?$hospital->name:old('name')}}" {{isset($hospital)?'disabled':''}}>
        </div>
    </div>
    <div class="form-group row">
        <label for="displayName" class="col-sm-2 col-form-label"><span class="text-red">*</span>名称</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{empty($errors->first('display_name'))?'':'is-invalid'}}" id="displayName" name="display_name" placeholder="{{empty($errors->first('display_name'))?'名称':$errors->first('display_name')}}" value="{{isset($hospital)?$hospital->display_name:old('display_name')}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="status" class="col-sm-2 col-form-label"><span class="text-red">*</span>状态</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="status" {{isset($hospital)&&$hospital->status==1?'checked':''}} {{isset($hospital)?'':'checked'}} value="1">
                <label class="form-check-label" for="status">正常</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="status2" {{isset($hospital)&&$hospital->status==0?'checked':''}} value="0">
                <label class="form-check-label" for="status2">关闭</label>
            </div>
        </div>
    </div>
</div>