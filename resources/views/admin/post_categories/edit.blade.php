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
                            <span class="caption-subject font-dark sbold uppercase">Add Category</span>
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
                            {{ Form::model($category, array('method' => 'PUT', 'url' => url('admin/post/categories',$category->_id), 'class' => 'form-horizontal', 'files' => true)) }}


                            <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name</label>
                                <div class="col-md-9">
                                    {!! Form::text('category_name',$category->pcat_name,['class'=>'form-control','placeholder'=>'Category name','required'=>'required']) !!}
                                </div>
                            </div>
                            <div class="form-actions right">
                                <div class="row">
                                    <div class="col-md-offset-4 col-md-8">
                                        <button type="button"  onclick="window.location='{{url('admin/post/categories')}}'" class="btn default">Cancel</button>
                                        <button type="submit" class="btn blue">Save</button>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
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