<div class="card-body">
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label"><span class="text-red">*</span>Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{empty($errors->first('name'))?'':'is-invalid'}}" id="name" name="name" placeholder="{{empty($errors->first('name'))?'英文或拼音 eg:3':$errors->first('name')}}" value="{{isset($condition)?$condition->name:old('name')}}" {{isset($condition)?'disabled':''}}>
        </div>
    </div>
    <div class="form-group row">
        <label for="displayName" class="col-sm-2 col-form-label"><span class="text-red">*</span>名称</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{empty($errors->first('display_name'))?'':'is-invalid'}}" id="displayName" name="display_name" placeholder="{{empty($errors->first('display_name'))?'名称':$errors->first('display_name')}}" value="{{isset($condition)?$condition->display_name:old('display_name')}}">
        </div>
    </div>
</div>