<div class="card-body">
    <div class="form-group row">
        <label for="gh_name" class="col-sm-1 col-form-label text-right">姓名</label>
        <div class="col-sm-3">
            <input type="text" class="form-control {{empty($errors->first('gh_name'))?'':'is-invalid'}}" id="gh_name" name="gh_name" placeholder="{{empty($errors->first('gh_name'))?'英文或拼音 eg:slug':$errors->first('gh_name')}}" value="{{isset($gh)?$gh->gh_name:old('gh_name')}}" {{isset($gh)?'disabled':''}}>
        </div>
        <label for="gh_age" class="col-sm-1 col-form-label text-right">年龄</label>
        <div class="col-sm-3">
            <input type="text" class="form-control {{empty($errors->first('gh_age'))?'':'is-invalid'}}" id="gh_name" name="gh_age" placeholder="{{empty($errors->first('gh_age'))?'':$errors->first('gh_age')}}" value="{{isset($gh)?$gh->gh_age:old('gh_age')}}" {{isset($gh)?'disabled':''}}>
        </div>
        <label for="gh_sex" class="col-sm-1 col-form-label text-right">性别</label>
        <div class="col-sm-3">
            <input type="text" class="form-control {{empty($errors->first('gh_sex'))?'':'is-invalid'}}" id="gh_sex" name="gh_sex" placeholder="{{empty($errors->first('gh_sex'))?'':$errors->first('gh_sex')}}" value="{{isset($gh)?$gh->gh_sex:old('gh_sex')}}" {{isset($gh)?'disabled':''}}>
        </div>
    </div>
    <div class="form-group row">
        <label for="gh_tel" class="col-sm-1 col-form-label text-right">电话</label>
        <div class="col-sm-3">
            <input type="text" class="form-control {{empty($errors->first('gh_tel'))?'':'is-invalid'}}" id="gh_tel" name="gh_tel" placeholder="{{empty($errors->first('gh_tel'))?'':$errors->first('gh_tel')}}" value="{{isset($gh)?$gh->gh_tel:old('gh_tel')}}" {{isset($gh)?'disabled':''}}>
        </div>
        <label for="gh_office" class="col-sm-1 col-form-label text-right">医院</label>
        <div class="col-sm-3">
            <input type="text" class="form-control {{empty($errors->first('gh_office'))?'':'is-invalid'}}" id="gh_office" name="gh_office" placeholder="{{empty($errors->first('gh_office'))?'':$errors->first('gh_office')}}" data-off="{{$hospitals}}" value="{{isset($hospitals[$gh->gh_office])?$hospitals[$gh->gh_office]:''}}" {{isset($gh)?'disabled':''}}>
        </div>
        <label for="gh_disease" class="col-sm-1 col-form-label text-right">病种</label>
        <div class="col-sm-3">
            <input type="text" class="form-control {{empty($errors->first('gh_disease'))?'':'is-invalid'}}" id="gh_disease" name="gh_disease" placeholder="{{empty($errors->first('gh_disease'))?'':$errors->first('gh_disease')}}" value="{{isset($diseases[$gh->gh_disease])?$diseases[$gh->gh_disease]:''}}" {{isset($gh)?'disabled':''}}>
        </div>
    </div>
    <div class="form-group row">
        <label for="created_at" class="col-sm-2 col-form-label text-right">添加时间</label>
        <div class="col-sm-4">
            <input type="text" class="form-control {{empty($errors->first('created_at'))?'':'is-invalid'}}" id="created_at" name="created_at" placeholder="{{empty($errors->first('created_at'))?'':$errors->first('created_at')}}" value="{{isset($gh)?$gh->created_at:old('created_at')}}" {{isset($gh)?'disabled':''}}>
        </div>
        <label for="gh_date" class="col-sm-2 col-form-label text-right">预约时间</label>
        <div class="col-sm-4">
            <input type="text" class="form-control {{empty($errors->first('gh_date'))?'':'is-invalid'}}" id="gh_date" name="gh_date" placeholder="{{empty($errors->first('gh_date'))?'':$errors->first('gh_date')}}" value="{{isset($gh)?$gh->gh_date:old('gh_date')}}" {{isset($gh)?'disabled':''}}>
        </div>
    </div>
    <div class="form-group row">
        <label for="gh_ref" class="col-sm-2 col-form-label text-right">页面来源</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{empty($errors->first('gh_ref'))?'':'is-invalid'}}" id="gh_ref" name="gh_ref" placeholder="{{empty($errors->first('gh_ref'))?'':$errors->first('gh_ref')}}" value="{{isset($gh)?$gh->gh_ref:old('gh_ref')}}" {{isset($gh)?'disabled':''}}>
        </div>
    </div>
    <div class="form-group row">
        <label for="gh_description" class="col-sm-2 col-form-label text-right">病情描述</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{empty($errors->first('gh_description'))?'':'is-invalid'}}" id="gh_description" name="gh_description" placeholder="{{empty($errors->first('gh_description'))?'':$errors->first('gh_description')}}" value="{{isset($gh)?$gh->gh_description:old('gh_description')}}" {{isset($gh)?'disabled':''}}>
        </div>
    </div>
    <div class="form-group row">
        <label for="addons" class="col-sm-2 col-form-label text-right">备注</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{empty($errors->first('addons'))?'':'is-invalid'}}" id="addons" name="addons" placeholder="{{empty($errors->first('addons'))?'':$errors->first('addons')}}" value="{{isset($gh)?$gh->addons:old('addons')}}"}}>
        </div>
    </div>
    <div class="form-group row">
        <label for="status" class="col-sm-2 col-form-label text-right">客户状态</label>
        <div class="col-sm-10">
            <input list="statusList" type="text" class="form-control {{empty($errors->first('status'))?'':'is-invalid'}}" id="status" name="status" placeholder="{{empty($errors->first('status'))?'':$errors->first('status')}}" value="{{isset($gh)?$gh->status:old('status')}}"}}>
            <datalist id="statusList">
                <option value="登记">
                <option value="已回访">
                <option value="已预约">
                <option value="无效">
                <option value="其它">
            </datalist>
        </div>
    </div>
</div>