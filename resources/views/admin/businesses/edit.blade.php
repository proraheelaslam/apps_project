@extends('admin.layout.app')
<style>
     #map{
        margin-top: 40px;
        width: 693px;
        height: 400px;
    } 
</style>
@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-12 ">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Edit Business</span>
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

                            <form method="POST" action="{{ url('admin/business/'.$businessDetail->_id) }}" class="form-horizontal">
                                {{ method_field("PUT") }}
                                {{ csrf_field() }}
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name</label>
                                    <div class="col-md-7">
                                        <input type="text" name="business_name" value="{{ (old('business_name'))? old('business_name') : $businessDetail->business_name }}" class="form-control" required="" placeholder="Name">
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Email</label>
                                    <div class="col-md-7">
                                        <input type="email" name="business_email" value="{{ (old('business_email'))? old('business_email') : $businessDetail->business_email }}" class="form-control" required="" placeholder="Email">
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Phone</label>
                                    <div class="col-md-7">
                                        <input type="text" name="business_phone" value="{{ (old('business_phone'))? old('business_phone') : $businessDetail->business_phone }}" class="form-control" required="" maxlength="50" placeholder="Phone">
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Website</label>
                                    <div class="col-md-7">
                                        <input type="text" name="business_website" value="{{ (old('business_website'))? old('business_website') : $businessDetail->business_website }}" class="form-control" required="" maxlength="150" placeholder="Website">
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Detail</label>
                                    <div class="col-md-7">
                                        <textarea class="form-control" maxlength="500" rows="3" name="business_details" placeholder="Detail">{{ (old('business_details'))? old('business_details') : $businessDetail->business_details }}</textarea>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Category</label>
                                    <div class="col-md-7">
                                        <select name="category" class='form-control' required>
                                            <option value=""> Select Category </option>
                                            @foreach($categories as $k => $category)

                                                @if(!empty(old('category')))
                                                    <option {{ (old('category') === $category->_id) ? 'selected' : ''}} value="{{$category->_id}}"> {{$category->name}}</option>    
                                                @else
                                                    <option {{($category->_id === $businessDetail->category_id) ? 'selected' : ''}}  value="{{$category->_id}}"> {{$category->name}}</option>
                                                @endif

                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Address</label>
                                    <div class="col-md-7">
                                        <input type="hidden" name="address_latitude" value=" {{ old('address_latitude')? old('address_latitude') : $businessDetail->latitude }} ">
                                        <input type="hidden" name="address_longitude" value=" {{ old('address_longitude')? old('address_longitude') : $businessDetail->longitude }} ">
                                        <input class="form-control" required="" name="business_address" type="text" id="business_address" value="{{ (old('business_address')) ? old('business_address') : $businessDetail->business_address }}">
                                        @if ($errors->has('business_address'))
                                            <span class="help-block font-red bold">
                                                {{ $errors->first('business_address') }}
                                            </span>
                                        @endif
                                        <div id="map" class="col-md-7"></div>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-actions right">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <button type="button"  onclick="window.location='{{url('admin/businesses')}}'" class="btn default">Cancel</button>
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
{{-- <script type="text/javascript" src="{{ asset('js/gmaps.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('js/jquery.geocomplete.js') }}"></script> --}}
<script>
    var APP_URL = {!! json_encode(url('/')) !!};
    var selectedNeighborhoods = '';
    var address = "{{ $businessDetail->business_address }}";
    var lat = 0;
        var long = 0;
    $(document).ready(function(){
        
        initMap();
        $('#business_address').trigger('keyup');
        $('body').keypress(function(e) 
        {
          if (e.keyCode == '13') {
             e.stopPropagation()

           }
        });
        // $("#business_address").geocomplete();
        /*

        map = new GMaps({
            el: '#map',
            lat: -12.043333,
            lng: -77.028333
        });
        // $('#business_address').keyup();    

        $('#business_address').on('keyup focusout', function(e){
            e.preventDefault();
            GMaps.geocode({
              address: $('#business_address').val().trim(),
              callback: function(results, status){
                if(status=='OK'){
                  var latlng = results[0].geometry.location;
                  // console.log(latlng.lat());
                  lat = latlng.lat();
                  long = latlng.lng();
                  
                  map.setCenter(latlng.lat(), latlng.lng());
                  map.addMarker({
                    lat: latlng.lat(),
                    lng: latlng.lng(),
                    draggable: true,
                    dragend: function(event) {
                        console.log(event);
                        lat = event.latLng.lat();
                        long = event.latLng.lng();
                        map.lat = lat;
                        map.lng = long;
                        // alert('draggable '+lat+" - "+ long);

                    }
                  });
                }
              }
            });
        });

        $(document).on('focusout', '#business_address', function(){
            $('input[name=address_latitude]').val(lat);
            $('input[name=address_longitude]').val(long);
        });
        $('#business_address').trigger('keyup');*/






    });   


    function initMap() {

        /*var lat = $("#lodge_latitude_" + counter).val();
        var long = $("#lodge_longitude_" + counter).val();*/
        var latLng = new google.maps.LatLng($("input[name=address_latitude]").val(), $("input[name=address_longitude]").val());
        var zoom = 17;
        


        var options = {
            
        };

        var map = new google.maps.Map(document.getElementById('map'), {
            center: latLng,
            zoom: zoom
        });

        var id = "business_address";
        var input = document.getElementById(id);

        var autocomplete = new google.maps.places.Autocomplete(input, options);

        autocomplete.bindTo('bounds', map);

        var marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29),
            draggable: true,
        });

        marker.setPosition(latLng);
        marker.setVisible(true);


        autocomplete.addListener('place_changed', function() {

            marker.setVisible(false);
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            $("input[name=address_latitude]").val(place.geometry.location.lat());
            $("input[name=address_longitude]").val(place.geometry.location.lng());


            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
            // return false;

        });

        //this will prevent form submitting
         google.maps.event.addDomListener(input, 'keydown', function(event) { 
            if (event.keyCode === 13) { 
                event.preventDefault(); 
            }
          });
        //this will prevent form submitting

        google.maps.event.addListener(marker, 'dragend', function(evt) {
            $("input[name=address_latitude]").val(evt.latLng.lat());
            $("input[name=address_longitude]").val(evt.latLng.lng());
            geocodePosition(marker.getPosition());
        });

    }

    var geocoder = new google.maps.Geocoder();

    function geocodePosition(pos) {
        geocoder.geocode({
            latLng: pos
        }, function(responses) {
            if (responses && responses.length > 0) {
                updateMarkerAddress(responses[0].formatted_address);
            } else {
                updateMarkerAddress('Cannot determine address at this location.');
            }
        });
    }

    function updateMarkerAddress(str) {
        $("#business_address").val(str);
    }  
    
</script>
@endsection