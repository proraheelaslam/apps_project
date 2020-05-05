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
                            <span class="caption-subject font-dark sbold uppercase">Add Ads</span>
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
                        {!! Form::open(['url'=>'admin/ads','method' => 'post','class'=>
                                    'form-horizontal','data-toggle'=>'validator'
                            ,'role'=>'form']) !!}
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name</label>
                                <div class="col-md-9">
                                    {!! Form::text('name','',['class'=>'form-control','placeholder'=>'Ads Name','required'=>'required']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Unit id</label>
                                <div class="col-md-9">
                                    {!! Form::text('unit_id','',['class'=>'form-control','placeholder'=>'Unit Id','required'=>'required']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">App id</label>
                                <div class="col-md-9">
                                    {!! Form::text('app_id','',['class'=>'form-control','placeholder'=>'App id','required'=>'required']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Height</label>
                                <div class="col-md-9">
                                    {!! Form::number('height','',['class'=>'form-control','placeholder'=>'Ads Height','required'=>'required']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Width</label>
                                <div class="col-md-9">
                                    {!! Form::number('width','',['class'=>'form-control','placeholder'=>'Ads Width ','required'=>'required']) !!}
                                </div>
                            </div>
                            <div class="form-actions right">
                                <div class="row">
                                    <div class="col-md-offset-4 col-md-8">
                                        <button type="button"  onclick="window.location='{{url('admin/ads')}}'" class="btn default">Cancel</button>
                                        <button type="submit" class="btn blue">Save</button>
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