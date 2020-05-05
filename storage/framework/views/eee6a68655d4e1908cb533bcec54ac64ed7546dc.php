<?php $__env->startSection('content'); ?>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Neighborhood Export</span>
                        </div>
                        <a href="<?php echo e(asset('upload/csv/neighborhood_sample.csv')); ?>" class="btn btn-primary pull-right btn-circle">Download Sample CSV</a>
                    </div>
                    <div class="portlet-body form">
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div><?php echo e($message); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                            <?php echo Form::open(['url'=>'admin/neighborhood/export','method' => 'post','class'=>
                                        'form-horizontal','data-toggle'=>'validator'
                                ,'role'=>'form']); ?>

                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Neighborhood Name</label>
                                        <div class="col-md-9">
                                            <?php echo Form::text('neighborhood_name','',['class'=>'form-control','placeholder'=>'Neighborhood Name','required'=>'required']); ?>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Enter Location</label>
                                        <div class="col-md-9">
                                            <?php echo Form::text('neighborhood_address','',['class'=>'form-control','id'=>'pac-input','placeholder'=>'Enter a location','required'=>'required']); ?>

                                        </div>
                                    </div>
                                    <input type="hidden" name="cords[]" value="" id="mapCrodinates">
                                    <button id="deleteNeighborhoodShap" style="margin-right: 14px; margin-bottom: 10px;"
                                            type="button" class="btn btn-circle red btn-sm pull-right">Delete Selected
                                        Shape
                                    </button>
                                    <div id="neighborhoodAreaDraw" style="width:100%;height:300px;"></div>
                                    <div class="form-actions right exportNeighborhood" style="display: none">
                                        <div class="row">
                                            <div class="col-md-offset-4 col-md-8">
                                                <button type="submit" class="btn blue">Export </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php echo Form::close(); ?>

                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php if(Session::has('status')): ?>
      <?php  Session::forget('status'); ?>
        <script>
            window.location = "<?php echo e(url('admin/neighborhood/export/create')); ?>";
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

    <script>
        let map, infoWindow, neighborhood_coordinates = [];
        var drawingManager;
        var selectedShape;
        var colors = ['#1E90FF', '#FF1493', '#32CD32', '#FF8C00', '#4B0082'];
        var selectedColor;
        var polyOptions = {
            strokeWeight: 0,
            fillOpacity: 0.45,
            editable: true
        };

       /* window.onload = function() {
            go();
        };*/
        var corrdinatesArr = [];
         map = new google.maps.Map(document.getElementById('neighborhoodAreaDraw'), {
            center: {lat: -33.8688, lng: 151.2195},
            zoom: 18
        });
        var polygonShap = new google.maps.Polygon({
            paths: neighborhood_coordinates,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: '#FF0000',
            fillOpacity: 0.35
        });
        //
        var googleMapDraw = function () {

            var autoCompleteShowMap = function() {
                var input = document.getElementById('pac-input');
                var autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.bindTo('bounds', map);
                // Set the data fields to return when the user selects a place.
                autocomplete.setFields(['address_components', 'geometry', 'icon', 'name']);
                var marker = new google.maps.Marker({
                    map: map,
                    anchorPoint: new google.maps.Point(0, -29)
                });
                autocomplete.addListener('place_changed', function() {
                    marker.setVisible(false);
                    var place = autocomplete.getPlace();
                    if (!place.geometry) {
                        window.alert("No details available for input: '" + place.name + "'");
                        return;
                    }
                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                        if (selectedShape) {
                            selectedShape.setMap(null);
                            drawingManager.setOptions({
                                drawingControl: true
                            });
                        }
                        polygonShap.setMap(null);
                        drawingManager.setMap(map);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(17);  // Why 17? Because it looks good.
                    }
                    marker.setPosition(place.geometry.location);
                    marker.setVisible(true);
                    var address = '';
                    if (place.address_components) {
                        address = [
                            (place.address_components[0] && place.address_components[0].short_name || ''),
                            (place.address_components[1] && place.address_components[1].short_name || ''),
                            (place.address_components[2] && place.address_components[2].short_name || '')
                        ].join(' ');
                    }
                });

            }
            var polygonShowDraw = function() {
                neighborhood_coordinates = null;
                function clearSelection() {
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
                    drawingManager.setMap(map);
                    $(".exportNeighborhood").hide();
                }
                drawingManager = new google.maps.drawing.DrawingManager({
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
                google.maps.event.addListener(drawingManager, 'overlaycomplete', function (polygon) {
                    if ((polygon.type == google.maps.drawing.OverlayType.POLYLINE) || (polygon.type == google.maps.drawing.OverlayType.POLYGON)) {
                        var locations = polygon.overlay.getPath().getArray();
                        let corrdinates = locations.toString();
                        var path = polygon.overlay.getPath();
                        var coordinates = [];
                        for (var i = 0; i < path.length; i++) {
                            coordinates.push([path.getAt(i).lng(), path.getAt(i).lat()]);
                        }
                        corrdinatesArr = coordinates;
                        $("#mapCrodinates").val(JSON.stringify(corrdinatesArr));
                        $(".exportNeighborhood").show();
                    }
                });
                google.maps.event.addListener(drawingManager, 'overlaycomplete', function (e) {
                    if (e.type != google.maps.drawing.OverlayType.MARKER) {
                        // Switch back to non-drawing mode after drawing a shape.
                        drawingManager.setDrawingMode(null);
                        // To hide:
                        drawingManager.setOptions({ drawingControl: false});
                        var newShape = e.overlay;
                        newShape.type = e.type;
                        google.maps.event.addListener(newShape, 'click', function () {
                            setSelection(newShape);
                        });
                        setSelection(newShape);
                    }
                });
                google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
                google.maps.event.addListener(map, 'click', clearSelection);
                google.maps.event.addDomListener(document.getElementById('deleteNeighborhoodShap'), 'click', deleteSelectedShape);
            }
            return {
                //main function to initiate the module
                init: function () {
                    autoCompleteShowMap();
                    polygonShowDraw();

                }
            };
        }();
            jQuery(document).ready(function() {
                googleMapDraw.init();
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>