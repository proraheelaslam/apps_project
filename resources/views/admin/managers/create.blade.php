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
                            <span class="caption-subject font-dark sbold uppercase">Add User</span>
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

                        {!! Form::open(['url'=>'admin/managers','method' => 'post','class'=>
                                    'form-horizontal','data-toggle'=>'validator'
                            ,'role'=>'form']) !!}
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name</label>
                                <div class="col-md-9">
                                    {!! Form::text('name','',['class'=>'form-control','placeholder'=>' Name','required'=>'required']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Email</label>
                                <div class="col-md-9">
                                    {!! Form::text('email','',['class'=>'form-control','placeholder'=>' Email','required'=>'required']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Country</label>
                                <div class="col-md-9">
                                    <select name="country" class='form-control' id="country" onchange="getListing('getStates')">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $k => $country)
                                            <option  value="{{$country->country_id}}"> {{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">State</label>
                                <div class="col-md-9">
                                    <select name="state" class='form-control' id="state" onchange="getListing('getCities')">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">City</label>
                                <div class="col-md-9">
                                    <select name="city" class='form-control' id="city">
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Roles</label>
                                <div class="col-md-9">
                                    <select name="role" class='form-control'>
                                        @foreach($roles as $k=>$role)
                                            <option  value="{{$k}}_{{$role}}"> {{$role}}</option>
                                        @endforeach
                                    </select>
                                    {{--{!! Form::select('role',@$roles,[],['class'=>'form-control','required'=>'required']) !!}--}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Neighborhood</label>
                                <div class="col-md-9">
                                    <select name="neighborhoods[]" class='form-control select2-multiple' multiple>
                                        @foreach($neighborhoods as $k => $neighborhoods)
                                            <option  value="{{$neighborhoods->_id}}"> {{$neighborhoods->neighborhood_name}}</option>
                                        @endforeach
                                    </select>
                                    {{--{!! Form::select('role',@$roles,[],['class'=>'form-control','required'=>'required']) !!}--}}
                                </div>
                            </div>
                            <div class="form-actions right">
                                <div class="row">
                                    <div class="col-md-offset-4 col-md-8">
                                        <button type="button"  onclick="window.location='{{url('admin/roles')}}'" class="btn default">Cancel</button>
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

    <script type="text/javascript">
        var APP_URL = {!! json_encode(url('/')) !!};
        // console.log(APP_URL);
        $(document).ready(function(){

            $(".select2-multiple").select2({
                placeholder: "Select Neighborhood",
                width: null
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

        function getListing(type){
            let urlPart = "";
            switch(type){
                case "getStates":
                    urlPart = $("#country :selected").val();
                    break;
                case "getCities":
                    urlPart = $("#state :selected").val();
                    break;
            }
            /*$.ajax({
                type: "GET",
                cache: false,
                url: APP_URL+urlPart,
                dataType: "html",
                success: function(res)
                {
                    switch(type){
                        case "getStates":
                            let data = res.data;
                            let options = "<option value=''>Select State</option>";
                            $.each(data, function (index, value) {
                                    options += "<option value='"+value.state_id+"'>"+value.name+"</option>";
                                }
                            );
                            $("#state").html(options);
                        break;
                        case "getCities":
                            urlPart = $("#state :selected").val();
                        break;
                    }
                },
                error: function(xhr, ajaxOptions, thrownError)
                {

                },
                async: false
            })*/
        }
    </script>
@endsection