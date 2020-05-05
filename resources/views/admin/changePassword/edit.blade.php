@extends('admin.layout.app')
@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-12 ">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Change Password</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                @foreach($errors->all() as $message)
                                    <div>{{$message}}</div>
                                @endforeach
                            </div>
                        @endif
                        {!! Form::open(['url'=>'admin/password/change','method' => 'post','class'=>
                                    'form-horizontal','id'=>'changePassword-form','data-toggle'=>'validator'
                            ,'role'=>'form']) !!}
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Current Password</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::password('current_password',['class'=>'form-control','placeholder'=>'Current Password','required'=>'required']) !!}
                                        <span class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">New password</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::password('new_password',['class'=>'form-control','placeholder'=>'New Password','required'=>'required']) !!}
                                        <span class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Confim New Password</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::password('confirm_password',['class'=>'form-control','placeholder'=>'Confirm Password','required'=>'required']) !!}
                                        <span class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions right">
                                <div class="row">
                                    <div class="col-md-offset-4 col-md-8">
                                        <button type="button"  onclick="window.location='{{url('admin/home')}}'" class="btn default">Cancel</button>
                                        <button type="submit" class="btn blue">Submit</button>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <!-- END DASHBOARD STATS 1-->
        </div>
    </div>
@endsection
@section('script')
@endsection