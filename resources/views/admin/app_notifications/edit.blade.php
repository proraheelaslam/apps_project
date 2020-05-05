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
                            <span class="caption-subject font-dark sbold uppercase">Edit App Notification</span>
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

                        {{-- {{ dd($category)}} --}}
                        {{-- {{ dd($categories)}} --}}
                            <form method="POST" action="{{ route('admin.app_notification.update', ['app_notification_id' => $appNotification->_id]) }}" class="form-horizontal">
                                {{ method_field("PUT") }}
                                {{ csrf_field() }}
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name</label>
                                    <div class="col-md-7">
                                        <input class="form-control" placeholder="Name" required="required" name="name" type="text" value="{{ (old('name'))? old('name') : $appNotification->name }}" maxlength="150">
                                    </div>

                                    <div class="col-md-2"></div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Message</label>
                                    <div class="col-md-7">
                                        <textarea name="message" class="form-control" cols="2" rows="3" value="{{ (old('message'))? old('message') : $appNotification->message }}">{{ (old('message'))? old('message') : $appNotification->message }}</textarea>
                                        {{--<input class="form-control" placeholder="Name" required="required" name="name" type="text" value="{{ (old('name'))? old('name') : $appNotification->name }}" maxlength="150">--}}
                                    </div>

                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-actions right">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <button type="button"  onclick="window.location='{{url('admin/app_notification')}}'" class="btn default">Cancel</button>
                                            <button type="submit" class="btn blue">Save</button>
                                        </div>
                                    </div>
                                </div>
                                </form>
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
<script>
    var APP_URL = {!! json_encode(url('/')) !!};
    
    $(document).ready(function(){
        
    });
    
</script>
@endsection