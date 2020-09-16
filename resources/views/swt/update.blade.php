@extends('layouts.app')

@section('content')
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">更新</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{route('member.update',$member->id)}}" method="post" class="members-form form-horizontal">
            {{csrf_field()}}
            {{method_field('PUT')}}
            @include('member.form')
        </form>
    </div>
@endsection
