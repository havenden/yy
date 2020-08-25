<div class="card-body">
    <div class="form-group row">
        <label for="hid" class="col-sm-2 col-form-label"><span class="text-red">*</span>所属医院/项目</label>
        <div class="col-sm-10">
            <input type="hidden" class="form-control" name="hid" value="{{ isset($hospital)?$hospital->id:'' }}" >
            <input type="text" class="form-control" id="hid"  value="{{ isset($hospital)?$hospital->display_name:'' }}" disabled="disabled">
        </div>
    </div>
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label"><span class="text-red">*</span>名称</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{empty($errors->first('name'))?'':'is-invalid'}}" id="name" name="name" placeholder="{{empty($errors->first('name'))?'名称':$errors->first('name')}}" value="{{isset($doctor)?$doctor->name:old('name')}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="num" class="col-sm-2 col-form-label">医生编号</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{empty($errors->first('num'))?'':'is-invalid'}}" id="num" name="num" placeholder="{{empty($errors->first('num'))?'编号':$errors->first('num')}}" value="{{isset($doctor)?$doctor->num:old('num')}}">
        </div>
    </div>
</div>