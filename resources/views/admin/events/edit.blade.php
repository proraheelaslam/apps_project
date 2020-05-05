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
                            <span class="caption-subject font-dark sbold uppercase">Edit Event</span>
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

                        {{-- {{ dd($event)}} --}}
                        {{-- {{ dd($categories)}} --}}
                            <form method="POST" action="{{ route('admin.events.update', ['event_id' => $event->_id]) }}" class="form-horizontal">
                                {{ method_field("PUT") }}
                                {{ csrf_field() }}
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Title</label>
                                    <div class="col-md-7">
                                        <textarea class="form-control" maxlength="500" rows="3" name="title" placeholder="Title">{{ (old('title'))? old('title') : $event->title }}</textarea>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Category</label>
                                    <div class="col-md-7">
                                        <select name="category" class='form-control' required required>
                                            @foreach($categories as $k => $category)
                                                @if(old('category'))
                                                    <option {{ (old('category') == $category->_id) ? 'selected' : ''}} value="{{$category->_id}}" selected=""> {{$category->name}}</option>    
                                                @else
                                                    <option {{  ($category->_id == $event->ecategory_id) ? 'selected' : ''}}  value="{{$category->_id}}"> {{$category->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Date</label>
                                    <div class="col-md-7">
                                        <div class="input-group date form_meridian_datetime" data-date="@php date('Y-m-d').'T10:02:00Z' @endphp">
                                            <input type="text" name="event_date_time" value="{{ (old('event_date_time') ? old('event_date_time') : date('Y-M-d h:i A' , strtotime($event->event_date))) }}"  size="16" class="form-control" placeholder="Event Date Time" maxlength="50">
                                            <span class="input-group-btn">
                                                <button class="btn default date-reset" type="button">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <button class="btn default date-set" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Location</label>
                                    <div class="col-md-7">
                                        <input class="form-control" placeholder="Location" required="required" name="location" type="text" value="{{(old('location'))? old('location') : $event->event_locations }}" maxlength="150">
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Description</label>
                                    <div class="col-md-7">
                                        <textarea class="form-control" rows="3" name="description" placeholder="Event Description">{{ (old('description'))? old('description') : $event->event_description }}</textarea>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-actions right">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <button type="button"  onclick="window.location='{{url('admin/events')}}'" class="btn default">Cancel</button>
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
    var selectedNeighborhoods = '';
    $(document).ready(function(){
        $(".select2-multiple").select2({
            placeholder: "Select Roles",
            width: null
        });
        $('.select2-multiple').val(selectedNeighborhoods).trigger("change");

        $(".form_meridian_datetime").datetimepicker({
            isRTL: App.isRTL(),
            // format: "dd MM yyyy - HH:ii P",
            format: "yyyy-M-dd  HH:ii P",
            showMeridian: true,
            autoclose: true,
            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
            todayBtn: true
        });
        $("#country").on("change", function(e){
            e.preventDefault();
            let urlPart = "/admin/states/get/"+parseInt($("#country :selected").val());
            $.LoadingOverlay("show");
            $.ajax({
                type: "GET",
                cache: false,
                url: APP_URL+urlPart,
                // dataType: "json",
                success: function(res) 
                {   $.LoadingOverlay("hide");
                    // console.log(res.data);
                    let states = res.data;
                    // console.log(data);
                    let options = "<option value=''>Select State</option>";
                    $.each(states, function (index, state) {
                            options += "<option value='"+state.state_id+"'>"+state.name+"</option>";
                        }
                    ); 
                    $("#state").empty().html(options);
                },
                error: function(xhr, ajaxOptions, thrownError)
                {
                    $.LoadingOverlay("hide");
                },
                async: false
            });
        });

        $("#state").on("change", function(e){
            e.preventDefault();
            let urlPart = "/admin/cities/get/"+parseInt($("#state :selected").val());
            $.LoadingOverlay("show");
            $.ajax({
                type: "GET",
                cache: false,
                url: APP_URL+urlPart,
                success: function(res) 
                {   
                    $.LoadingOverlay("hide");
                    let cities = res.data;
                    let options = "<option value=''>Select City</option>";
                    $.each(cities, function (index, city) {
                            options += "<option value='"+city.city_id+"'>"+city.name+"</option>";
                        }
                    ); 
                    $("#city").empty().html(options);
                },
                error: function(xhr, ajaxOptions, thrownError)
                {
                    $.LoadingOverlay("hide");
                },
                async: false
            });
        });
    });
    
</script>
@endsection