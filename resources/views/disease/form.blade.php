<div class="card-body">
    <div class="form-group row">
        <label for="hid" class="col-sm-2 col-form-label"><span class="text-red">*</span>所属医院/项目</label>
        <div class="col-sm-10">
            <input type="hidden" class="form-control" name="hid" value="{{ isset($hospital)?$hospital->id:'' }}" >
            <input type="text" class="form-control" id="hid"  value="{{ isset($hospital)?$hospital->display_name:'' }}" disabled="disabled">
        </div>
    </div>
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label"><span class="text-red">*</span>Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{empty($errors->first('name'))?'':'is-invalid'}}" id="name" name="name" placeholder="{{empty($errors->first('name'))?'标识 eg: jibing':$errors->first('name')}}" {{ isset($disease)?'disabled':'' }} value="{{isset($disease)?$disease->name:old('name')}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="displayName" class="col-sm-2 col-form-label"><span class="text-red">*</span>名称</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{empty($errors->first('display_name'))?'':'is-invalid'}}" id="displayName" name="display_name" placeholder="{{empty($errors->first('display_name'))?'名称':$errors->first('display_name')}}" value="{{isset($disease)?$disease->display_name:old('display_name')}}">
        </div>
    </div>
</div>