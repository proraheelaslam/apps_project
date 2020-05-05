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
                            <span class="caption-subject font-dark sbold uppercase">Edit Page</span>
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
                            {{ Form::model($page, array('method' => 'PUT', 'url' => url('admin/pages',$page->_id), 'class' => 'form-horizontal', 'files' => true)) }}
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Title</label>
                                    <div class="col-md-9">
                                        {!! Form::text('title',$page->title,['class'=>'form-control','placeholder'=>'Content Title','required'=>'required']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Description</label>
                                    <div class="col-md-9">
                                        {!! Form::textarea('description',$page->description,['class'=>'form-control description' ,'id'=>'description','placeholder'=>'Content description','required'=>'required']) !!}
                                    </div>
                                </div>
                              {{--  <div class="form-group">
                                    <label class="col-md-3 control-label">Sef Url</label>
                                    <div class="col-md-9">
                                        {!! Form::text('sef_url',$page->sef_url,['class'=>'form-control','placeholder'=>'Content description','required'=>'required']) !!}
                                    </div>
                                </div>--}}
                              {{--  <div class="form-group">
                                    <label class="col-md-3 control-label">Page Key</label>
                                    <div class="col-md-9">
                                        {!! Form::text('page_key',$page->page_key,['class'=>'form-control','placeholder'=>'Page key','required'=>'required']) !!}
                                    </div>
                                </div>--}}
                                <div class="form-actions right">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <button type="button"  onclick="window.location='{{url('admin/pages')}}'" class="btn default">Cancel</button>
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