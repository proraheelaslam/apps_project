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
                            <span class="caption-subject font-dark sbold uppercase">Edit Ads</span>
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
                            {{ Form::model($ad, array('method' => 'PUT', 'url' => url('admin/ads',$ad->_id), 'class' => 'form-horizontal', 'files' => true)) }}
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name</label>
                                    <div class="col-md-9">
                                        {!! Form::text('name',$ad->name,['class'=>'form-control','placeholder'=>'Name','required'=>'required']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Unit id</label>
                                    <div class="col-md-9">
                                        {!! Form::text('unit_id',$ad->unit_id,['class'=>'form-control','Unit id'=>'height','required'=>'required']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">App id</label>
                                    <div class="col-md-9">
                                        {!! Form::text('app_id',$ad->app_id,['class'=>'form-control','Unit id'=>'App id','required'=>'required']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Height</label>
                                    <div class="col-md-9">
                                        {!! Form::text('height',$ad->height,['class'=>'form-control','placeholder'=>'height','required'=>'required']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Width</label>
                                    <div class="col-md-9">
                                        {!! Form::text('width',$ad->width,['class'=>'form-control','placeholder'=>'width','required'=>'required']) !!}
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