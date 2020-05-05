@extends('admin.layout.app')
@section('content')
    <div class="page-content">
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title"> Neighbourhood Detail
            <div class="pull-right">
                <a href="{{url('admin/neighborhoods')}}"  type="button" class="btn btn-primary">Back</a>
            </div>
            <!-- <small>dashboard & statistics</small> -->
        </h3>
        <!-- END PAGE TITLE-->
        
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <!-- BEGIN PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-bar-chart font-dark hide"></i>
                            <span class="caption-subject font-dark bold uppercase">Neighbourhood Detail</span>
                        </div>
                        @can('edit neighborhoods')
                            <div class="actions">
                                <a title="Edit"
                                   href="{{ url('admin/neighborhoods/edit'.'/'.encodeId($neighborhoods->_id))}}"
                                   class="btn btn-sm btn-circle btn-default btn-editable">
                                   <i class="fa fa-edit"></i>
                                    Edit
                                </a>
                                @if($neighborhoods->verified_by_admin == 0)
                                    <button onclick="verifyNeighborhood('{{$neighborhoods->_id}}',1)" type="button"
                                            class="btn btn-primary btn-circle">Verify
                                    </button>
                                    <button onclick="verifyNeighborhood('{{$neighborhoods->_id}}',0)" type="button"
                                            class="btn btn-danger btn-circle">Decline
                                    </button>
                                @endif

                            </div>
                        @endcan
                    </div>
                    <div class="portlet-body">
                        <!-- portlet body -->
                        <div class="row static-info">
                            <div class="col-md-5 name"> Name: </div>
                            <div class="col-md-7 value"> {{$neighborhoods->neighborhood_name}}
                                {{-- <span class="label label-info label-sm"> Email confirmation was sent </span> --}}
                            </div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-5 name"> Address: </div>
                            <div class="col-md-7 value"> {{$neighborhoods->neighborhood_address}} </div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-5 name"> Status: </div>
                            <div class="col-md-7 value">
                                @if($neighborhoods->verified_by_admin == 0)
                                   <span class="label label-sm label-default"> Pending </span>
                                 @elseif ($neighborhoods->verified_by_admin == 1)
                                   <span class="label label-sm label-success"> Verified </span>
                                @endif
                                {{-- <span class="label label-success"> Closed </span> --}}
                            </div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-5 name"> Country: </div>
                            <div class="col-md-7 value">
                                <select name="country" class='form-control input-medium' id="country" disabled="">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $k => $country)
                                        <option  value="{{$country->country_id}}" {{ ($neighborhoods->country_id == $country->country_id) ? "selected":"" }}> {{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-5 name"> State: </div>
                            <div class="col-md-7 value">
                                <select name="state" class='form-control input-medium' id="state" disabled="">
                                    <option value="">Select State</option>
                                    @foreach($states as $k => $state)
                                        <option  value="{{$state->state_id}}" {{ ($neighborhoods->state_id == $state->state_id) ? "selected":"" }}> {{$state->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-5 name"> City: </div>
                            <div class="col-md-7 value">
                                <select name="city" class='form-control input-medium' id="city" disabled="">
                                    <option value="">Select City</option>
                                    @foreach($cities as $k => $city)
                                        <option  value="{{$city->city_id}}" {{ ($neighborhoods->city_id == $city->city_id) ? "selected":"" }}> {{$city->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET-->
            </div>
            <div class="col-md-6 col-sm-6">
                <!-- BEGIN PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-share font-red-sunglo hide"></i>
                            <span class="caption-subject font-dark bold uppercase">User Detail</span>
                        </div>
                        @can('edit users')
                            <div class="actions">
                                <a title="User Detail"
                                   href="{{url('admin/users'.'/'.encodeId($neighborhoods->createdBy->_id))}}"
                                   class="btn btn-sm btn-circle btn-default btn-editable">
                                   <i class="fa fa-search"></i>
                                   View
                               </a>
                            </div>
                        @endcan
                    </div>
                    <div class="portlet-body">
                        <!-- portlet body -->
                        <div class="row static-info">
                            <div class="col-md-5 name"> Name: </div>
                            <div class="col-md-7 value"> {{$neighborhoods->createdBy->full_name}} </div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-5 name"> Email: </div>
                            <div class="col-md-7 value"> {{$neighborhoods->createdBy->user_email}} </div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-5 name"> Address: </div>
                            <div class="col-md-7 value"> {{$neighborhoods->createdBy->user_address}} </div>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET-->
            </div>
        </div>


        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-share font-dark hide"></i>
                            {{-- <span class="caption-subject font-dark bold uppercase">MAP</span> --}}
                        </div>
                        <div class="actions">
                            {{-- <button  id="saveNeighborhoodCorrdinates" onclick="saveNeighborhood('{{$neighborhoods->_id}}')" style="display: none"  type="button" class="btn btn-primary  btn-sm pull-right">Save</button> --}}
                            {{-- <button id="deleteNeighborhoodShap" style="margin-right: 14px; margin-bottom: 10px;"  type="button" class="btn btn-circle red btn-sm pull-right">Delete Selected Shape</button> --}}
                        
                        </div>
                    </div>
                    <div class="portlet-body">
                        {{-- <div id="region_statistics_loading">
                            <img src="../assets/global/img/loading.gif" alt="loading" /> 
                        </div> --}}
                        <div>
                            {{-- <div class="btn-toolbar margin-bottom-10">
                                <div class="btn-group btn-group-circle" data-toggle="buttons">
                                    <a href="" class="btn grey-salsa btn-sm active"> Users </a>
                                    <a href="" class="btn grey-salsa btn-sm"> Orders </a>
                                </div>
                                <div class="btn-group pull-right">
                                    <a href="" class="btn btn-circle grey-salsa btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Select Region
                                        <span class="fa fa-angle-down"> </span>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="javascript:;" id="regional_stat_world"> World </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" id="regional_stat_usa"> USA </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" id="regional_stat_europe"> Europe </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" id="regional_stat_russia"> Russia </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" id="regional_stat_germany"> Germany </a>
                                        </li>
                                    </ul>
                                </div>
                            </div> --}}

                            <div id="neighborhoodAreaDraw"style="width:100%;height:500px;"></div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@php( $neighborhoodArea = json_encode($neighborhoods->neighborhood_area['coordinates'],true) )
@endsection

@section('script')
    <script>

        function saveNeighborhood(neighborhood_id) {
            $.confirm({
                title: 'Neighborhood update!',
                content: 'Are you sure you want to update neighborhood!',
                draggable: false,
                type: 'red',
                typeAnimated: true,
                closeIcon: true,
                buttons: {
                    confirm: function () {
                        $.LoadingOverlay("show");
                        $.ajax({
                            url: "{{url('admin/neighborhoods/neighborhood_update')}}",
                            type: 'post',
                            data: {corrdinates:corrdinatesArr,neighborhood_id: neighborhood_id},
                            success: function (data) {
                                if(data.status == true) {
                                    $.LoadingOverlay("hide");
                                    success_message(data.message);
                                    setTimeout(function () {
                                        location.reload();
                                    }, 100)
                                }
                            },
                        });
                    },
                    cancel: function () {
                    }
                }
            });
        }
        // draw tool
        var corrdinatesArr = [];
        let neighborhoodArea = "{{$neighborhoodArea}}", neighborhood =  JSON.parse(neighborhoodArea), neighborhoods = neighborhood[0];
        let map, infoWindow, neighborhood_coordinates = [];
        var drawingManager;
        var selectedShape;
        var colors = ['#1E90FF', '#FF1493', '#32CD32', '#FF8C00', '#4B0082'];
        var selectedColor;
        var colorButtons = {};
        var polyOptions = {
            strokeWeight: 0,
            fillOpacity: 0.45,
            editable: true
        };
        var userLocationLatlng = {lat: parseFloat('{{$neighborhoods->createdBy->user_address_latitude}}'), lng: parseFloat('{{$neighborhoods->createdBy->user_address_longitude}}')};
        for(let i = 0; i < neighborhoods.length; i++){
            neighborhood_coordinates.push({lat: parseFloat(neighborhoods[i][1]), lng: parseFloat(neighborhoods[i][0])});
        }
         map = new google.maps.Map(document.getElementById('neighborhoodAreaDraw'), {
            center: neighborhood_coordinates[0],
            zoom: 18
        });
        var marker = new google.maps.Marker({
            position: userLocationLatlng,
            map: map,
        });
        let polygonShap = new google.maps.Polygon({
            paths: neighborhood_coordinates,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: '#FF0000',
            fillOpacity: 0.35
        });
        function clearSelection () {
            if (selectedShape) {
                selectedShape.setEditable(false);
                selectedShape = null;
            }
            polygonShap.setMap(null);

        }
        function selectColor(color) {
            selectedColor = color;
            var polylineOptions = drawingManager.get('polylineOptions');
            polylineOptions.strokeColor = color;
            drawingManager.set('polylineOptions', polylineOptions);
            var rectangleOptions = drawingManager.get('rectangleOptions');
            rectangleOptions.fillColor = color;
            drawingManager.set('rectangleOptions', rectangleOptions);

            var circleOptions = drawingManager.get('circleOptions');
            circleOptions.fillColor = color;
            drawingManager.set('circleOptions', circleOptions);

            var polygonOptions = drawingManager.get('polygonOptions');
            polygonOptions.fillColor = color;
            drawingManager.set('polygonOptions', polygonOptions);
        }
        function setSelection(shape) {
            clearSelection();
            selectedShape = shape;
            shape.setEditable(true);
            selectColor(shape.get('fillColor') || shape.get('strokeColor'));
        }
        function deleteSelectedShape() {
            if (selectedShape) {
                selectedShape.setMap(null);
                drawingManager.setOptions({
                    drawingControl: true
                });
            }
            polygonShap.setMap(null);
            $("#saveNeighborhoodCorrdinates").hide();
            drawingManager.setMap(map);
        }
        var drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [
                    google.maps.drawing.OverlayType.POLYGON
                ]
            },
            markerOptions: {draggable: true},
            circleOptions: {
                fillColor: '#ffff00',
                fillOpacity: 1,
                strokeWeight: 5,
                clickable: false,
                editable: true,
                zIndex: 1
            },

            polylineOptions: {
                editable: true
            },
            rectangleOptions: polyOptions,
            polygonOptions: polyOptions,
        });
        //drawingManager.setMap(map);
        polygonShap.setMap(map);

        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(polygon) {
            if ((polygon.type == google.maps.drawing.OverlayType.POLYLINE) || (polygon.type == google.maps.drawing.OverlayType.POLYGON)) {
                var locations = polygon.overlay.getPath().getArray();
                let corrdinates = locations.toString();
                var path = polygon.overlay.getPath();
                var coordinates = [];
                for (var i = 0 ; i < path.length ; i++) {
                    coordinates.push( [path.getAt(i).lng(),path.getAt(i).lat()]);
                }
                corrdinatesArr = coordinates;
                $("#saveNeighborhoodCorrdinates").show();
            }
            $("#saveNeighborhoodCorrdinates").show();
        });
        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
            if (e.type != google.maps.drawing.OverlayType.MARKER) {
                // Switch back to non-drawing mode after drawing a shape.
                drawingManager.setDrawingMode(null);
                // To hide:
                drawingManager.setOptions({
                    drawingControl: false
                });
                var newShape = e.overlay;
                newShape.type = e.type;
                google.maps.event.addListener(newShape, 'click', function() {
                    setSelection(newShape);
                });
                setSelection(newShape);
            }
        });

        google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
        google.maps.event.addListener(map, 'click', clearSelection);
        google.maps.event.addDomListener(document.getElementById('deleteNeighborhoodShap'), 'click', deleteSelectedShape);

        //
        function verifyNeighborhood(neighborhood_id,status) {
            $.confirm({
                title: 'Neighborhoods verification!',
                content: 'Are you sure you want to verify neighborhood!',
                draggable: false,
                type: 'red',
                typeAnimated: true,
                closeIcon: true,
                buttons: {
                    confirm: function () {
                        $.LoadingOverlay("show");
                        $.ajax({
                            url: "{{url('admin/neighborhoods/verify')}}",
                            type: 'post',
                            data: {neighborhood_id:neighborhood_id,status:status },
                            success: function (data) {
                                if(data.status == true) {
                                    $.LoadingOverlay("hide");
                                    success_message(data.message);
                                    setTimeout(function () {
                                        location.reload();
                                    }, 100)
                                }
                            },
                        });
                    },
                    cancel: function () {
                    }
                }
            });
        }



    </script>
@endsection