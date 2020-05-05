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
                            <span class="caption-subject font-dark sbold uppercase">Edit Manager</span>
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
                            {{ Form::model($manager, array('method' => 'PUT', 'url' => url('admin/managers',$manager->_id), 'class' => 'form-horizontal', 'files' => true)) }}
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name</label>
                                    <div class="col-md-9">
                                        {!! Form::text('name',$manager->name,['class'=>'form-control','placeholder'=>' name','required'=>'required']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Email</label>
                                    <div class="col-md-9">
                                        {!! Form::text('email',$manager->email,['class'=>'form-control','placeholder'=>' email','required'=>'required']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Country</label>
                                    <div class="col-md-9">
                                        <select name="country" class='form-control' id="country">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $k => $country)
                                                <option  value="{{$country->country_id}}" {{ ($manager->country_id == $country->country_id) ? "selected":"" }}> {{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">State</label>
                                    <div class="col-md-9">
                                        <select name="state" class='form-control' id="state">
                                            <option value="">Select State</option>
                                            @foreach($states as $k => $state)
                                                <option  value="{{$state->state_id}}" {{ ($manager->state_id == $state->state_id) ? "selected":"" }}> {{$state->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">City</label>
                                    <div class="col-md-9">
                                        <select name="city" class='form-control' id="city">
                                            <option value="">Select City</option>
                                            @foreach($cities as $k => $city)
                                                <option  value="{{$city->city_id}}" {{ ($manager->city_id == $city->city_id) ? "selected":"" }}> {{$city->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Roles</label>
                                    <div class="col-md-9">
                                        <select name="role" class='form-control' required>
                                            @foreach($roles as $k=>$role)
                                                <option {{($role == $selectedRole) ? 'selected' : ''}}  value="{{$k}}_{{$role}}"> {{$role}}</option>
                                            @endforeach
                                        </select>
                                        {{--{!! Form::select('role',@$roles,[],['class'=>'form-control','required'=>'required']) !!}--}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Neighborhood</label>
                                    <div class="col-md-9">
                                        <select name="neighborhoods[]" class='form-control select2-multiple' multiple>
                                            @foreach($neighborhoods as $k => $neighborhood)
                                                <option  value="{{$neighborhood->_id}}" > {{$neighborhood->neighborhood_name}}</option>
                                            @endforeach
                                        </select>
                                        {{--{!! Form::select('role',@$roles,[],['class'=>'form-control','required'=>'required']) !!}--}}
                                    </div>
                                </div>
                                <div class="form-actions right">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <button type="button"  onclick="window.location='{{url('admin/managers')}}'" class="btn default">Cancel</button>
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
<script>
    var APP_URL = {!! json_encode(url('/')) !!};
    var selectedNeighborhoods = {!! json_encode($manager->neighborhoods) !!};
    $(document).ready(function(){
        $(".select2-multiple").select2({
            placeholder: "Select Neighborhood",
            width: null
        });
        $('.select2-multiple').val(selectedNeighborhoods).trigger("change");


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