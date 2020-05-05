<?php $__env->startSection('content'); ?>
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <div class="blog-page blog-content-1">


            <div class="row">
                <div class="col-lg-12">
                    <div class="blog-post-lg bordered blog-container">
                        <h3 class="page-title"> Business Detail
                            <div class="pull-right">
                                <a href="<?php echo e(url('admin/businesses')); ?>" type="button" class="btn btn-primary">Back</a>
                            </div>
                            <!-- <small>dashboard & statistics</small> -->
                        </h3>
                        <div class="blog-post-content">
                            <div class="portlet-body">
                                <div class="row static-info margin-bottom-15">
                                    <div class="col-md-5 name"> Name: </div>
                                    <div class="col-md-7 value"> <?php echo e($businessDetail->business_name); ?> </div>
                                </div>
                                <div class="row static-info margin-bottom-15">
                                    <div class="col-md-5 name"> Address: </div>
                                    <div class="col-md-7 value"> <?php echo e($businessDetail->business_address); ?> </div>
                                </div>
                                <div class="row static-info margin-bottom-15">
                                    <div class="col-md-5 name"> Phone: </div>
                                    <div class="col-md-7 value"><?php echo e($businessDetail->business_phone); ?>

                                    </div>
                                </div>
                                <div class="row static-info margin-bottom-15">
                                    <div class="col-md-5 name"> Email: </div>
                                    <div class="col-md-7 value"><?php echo e($businessDetail->business_email); ?></div>
                                </div>
                                <div class="row static-info margin-bottom-15">
                                    <div class="col-md-5 name"> Website: </div>
                                    <div class="col-md-7 value"><?php echo e($businessDetail->business_website); ?>

                                    </div>
                                </div>
                                <div class="row static-info margin-bottom-15">
                                    <div class="col-md-5 name"> Approved: </div>
                                    <div class="col-md-7 value" id="approve_main_div"> 
                                        <?php if($businessDetail->business_is_approved < 1): ?>
                                            <span class="label label-danger btn-circle pointer" id="not_approved">Not Approved</span>
                                        <?php elseif($businessDetail->business_is_approved > 0): ?>
                                            <span class="label label-success btn-circle pointer" id="approved">Approved</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row static-info margin-bottom-15">
                                    <div class="col-md-5 name"> Total Likes: </div>
                                    <div class="col-md-7 value"> 
                                        <span class="btn btn-circle blue"><?php echo e($businessDetail->business_total_likes); ?> </span>
                                    </div>
                                </div>
                                <div class="row static-info margin-bottom-15">
                                    <div class="col-md-5 name"> Total Recommended: </div>
                                    <div class="col-md-7 value"> 
                                        <span class="btn btn-circle green"><?php echo e($businessDetail->business_total_recommended); ?>  </span>
                                    </div>
                                </div>
                                <div class="row static-info margin-bottom-15">
                                    <div class="col-md-5 name"> Detail: </div>
                                    <div class="col-md-7"> <?php echo e($businessDetail->business_details); ?> </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-sm-6">

                        </div>
                        <div class="col-sm-6">

                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin: life time stats -->
                    <div class="portlet light portlet-fit portlet-datatable bordered">

                        <div class="portlet-body">
                            <div class="tabbable-line">
                                <ul class="nav nav-tabs nav-tabs-lg">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab" aria-expanded="true"> Media 
                                            <span class="badge badge-danger"> <?php echo e(count($businessDetail->business_images)); ?></span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_2" data-toggle="tab" aria-expanded="false"> Recommendation
                                            <span class="badge badge-danger"> <?php echo e(count($businessDetail->business_recommended)); ?></span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_3" data-toggle="tab" aria-expanded="false"> Likes
                                            <span class="badge badge-danger"> <?php echo e(count($businessDetail->likes)); ?></span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_5" data-toggle="tab" aria-expanded="false"> Report
                                            <span class="badge badge-danger"> <?php echo e(count($businessDetail->business_reports)); ?></span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_4" data-toggle="tab" aria-expanded="false"> Address
                                        </a>
                                    </li>
                                    
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="table-container" style="">
                                            <?php if(count($businessDetail->business_images) > 0): ?>
                                                <div class="portfolio-content portfolio-1">
                                                    <div id="js-grid-juicy-projects" class="cbp">
                                                        <?php $__currentLoopData = $businessDetail->business_images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $businessImg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($businessImg->type === 'image'): ?>
                                                                <div class="cbp-item graphic "
                                                                     style="width: 342px;left: 0;top: 241px; max-height: 500px">
                                                                    <div class="cbp-caption">
                                                                        <div class="cbp-caption-defaultWrap">
                                                                            <img src="<?php echo e(asset('upload/businesses').'/'.$businessImg->bimg_name); ?>"
                                                                                 alt="" style="height: 300px"></div>
                                                                        <div class="cbp-caption-activeWrap">
                                                                            <div class="cbp-l-caption-alignCenter">
                                                                                <div class="cbp-l-caption-body">
                                                                                    <a href="javascript:void(0)"
                                                                                       class="  btn red uppercase btn red uppercase"
                                                                                       onclick="removeMedia('<?php echo e($businessImg->_id); ?>')">Remove</a>
                                                                                    <a href="<?php echo e(asset('upload/businesses').'/'.$businessImg->bimg_name); ?>"
                                                                                       class="cbp-lightbox cbp-l-caption-buttonRight btn red uppercase btn red uppercase"
                                                                                       data-title="">view
                                                                                        larger</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if($businessImg->type === 'video'): ?>
                                                                <div class="cbp-item graphic "
                                                                     style="width: 342px;left: 0;top: 241px;">
                                                                    <div class="cbp-caption">
                                                                        <div class="cbp-caption-defaultWrap">
                                                                            <img src="<?php echo e(asset('upload/businesses').'/'.$businessImg->bimg_name); ?>"
                                                                                 alt="" style="height: 300px">
                                                                        </div>
                                                                            
                                                                        <div class="cbp-caption-activeWrap">
                                                                            <div class="cbp-l-caption-alignCenter">
                                                                                <div class="cbp-l-caption-body">
                                                                                    <a href="javascript:void(0)"
                                                                                       class="  btn red uppercase btn red uppercase"
                                                                                       onclick="removeMedia('<?php echo e($businessImg->_id); ?>')">Remove</a>
                                                                                    <a href="<?php echo e(asset('upload/businesses').'/'.$businessImg->video_file); ?>"
                                                                                       class="cbp-lightbox cbp-l-caption-buttonRight btn red uppercase btn red uppercase"
                                                                                       data-title="">view
                                                                                        larger</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <p>No Media Found!</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_2">
                                        <div class="table-container" style="">
                                            <div class="portlet grey-cascade box">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-certificate"></i>Recommendations
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th> Image </th>                                  
                                                                <th> Recommended By </th>                                  
                                                                <th> Email </th>                          
                                                                <th> Phone </th>                          
                                                                <th> Actions </th>                                                            
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if(count($businessDetail->business_recommended) > 0): ?>
                                                                <?php $__currentLoopData = $businessDetail->business_recommended; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recommendation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <tr>
                                                                         <td>
                                                                            <div class="profile-userpic">
                                                                                <img style="height: 50px; width: 50px;" src="<?php echo e(asset('upload/users').'/'.$recommendation->users->user_image); ?>" alt="user image" class="img-responsive"> 
                                                                            </div>
                                                                        </td>
                                                                        <td><?php echo e($recommendation->users->full_name); ?></td>
                                                                        <td><?php echo e($recommendation->users->user_email ?? "N-A"); ?></td>
                                                                        <td><?php echo e($recommendation->users->user_phone ?? "N-A"); ?></td>
                                                                        <td>
                                                                            <a title="Delete Recommendation"
                                                                               onclick="deleteRecord('admin/business/recommendation/delete' ,'<?php echo e($recommendation->_id); ?>' ,'<?php echo e($businessDetail->_id); ?>', 'Recommendation')"
                                                                               href="javascript:void(0)"
                                                                               class="btn-delete btn btn-sm btn-circle btn-default btn-editable">
                                                                                <i class="fa fa-trash"></i> Delete
                                                                            </a>
                                                                        </td>

                                                                    </tr>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php else: ?>
                                                                <tr>
                                                                    <td colspan="5"><p class="text-center">No recommendation found</p></td>
                                                                </tr>
                                                            <?php endif; ?>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_3">
                                        <div class="table-container" style="">
                                            <div class="portlet grey-cascade box">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-thumbs-up"></i>Likes
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th width="10%"> Image </th>                                  
                                                                <th width="15%"> Recommended By </th>                                  
                                                                <th width="20%"> Email </th>                          
                                                                <th width="10%"> Phone </th>                          
                                                                <th width="10%"> Actions </th>                                                          
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if(count($businessDetail->likes) > 0): ?>
                                                                <?php $__currentLoopData = $businessDetail->likes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $like): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="profile-userpic">
                                                                                <img style="height: 50px; width: 50px;" src="<?php echo e(asset('upload/users').'/'.$like->users->user_image); ?>" alt="user image" class="img-responsive"> 
                                                                            </div>
                                                                        </td>
                                                                        <td><?php echo e($like->users->full_name); ?></td>
                                                                        <td><?php echo e($like->users->user_email ?? "N-A"); ?></td>
                                                                        <td><?php echo e($like->users->user_phone ?? "N-A"); ?></td>
                                                                        <td>
                                                                            <a title="Delete Like"
                                                                               onclick="deleteRecord('admin/business/like/delete' ,'<?php echo e($like->_id); ?>' ,'<?php echo e($businessDetail->_id); ?>', 'Like')"
                                                                               href="javascript:void(0)"
                                                                               class="btn-delete btn btn-sm btn-circle btn-default btn-editable">
                                                                                <i class="fa fa-trash"></i> Delete
                                                                            </a>
                                                                        </td>

                                                                    </tr>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php else: ?>
                                                                <tr>
                                                                    <td colspan="5"><p class="text-center">No Likes found</p></td>
                                                                </tr>
                                                            <?php endif; ?>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_5">
                                        <div class="table-container" style="">
                                            <div class="portlet grey-cascade box">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-warning"></i>Report
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th width="10%"> User First Name </th>                                  
                                                                <th width="15%"> Reason </th>                          
                                                                <th width="15%"> Comment </th>                        
                                                                <th width="10%"> Actions </th>                                                          
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if(count($businessDetail->business_reports) > 0): ?>
                                                                <?php $__currentLoopData = $businessDetail->business_reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <tr>
                                                                        
                                                                        <td><?php echo e($report->reported_by_user->full_name); ?></td>
                                                                        <td><?php echo e($report->business_report_reasons->brreason_name ?? "N-A"); ?></td>
                                                                        <td><?php echo e($report->breport_comment ?? "N-A"); ?></td>
                                                                        <td>
                                                                            <a title="Delete Like"
                                                                               onclick="deleteRecord('admin/business/report/delete' ,'<?php echo e($report->_id); ?>' ,'<?php echo e($businessDetail->_id); ?>', 'Report')"
                                                                               href="javascript:void(0)"
                                                                               class="btn-delete btn btn-sm btn-circle btn-default btn-editable">
                                                                                <i class="fa fa-trash"></i> Delete
                                                                            </a>
                                                                        </td>

                                                                    </tr>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php else: ?>
                                                                <tr>
                                                                    <td colspan="5"><p class="text-center">No Reports found</p></td>
                                                                </tr>
                                                            <?php endif; ?>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_4">
                                        <?php if(empty($businessDetail->latitude) && empty($businessDetail->longitude)): ?>
                                            <p class="text-center">Business address not found</p>
                                        <?php endif; ?>
                                        <div id="business_address" style="width:100%;height:300px;"></div>
                                    </div>

                                    <!-- tab-2 -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End: life time stats -->
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>


    <?php if(Session::has('media_tab')): ?>
        <script>$('a[href="#tab_1"]').trigger('click');</script> 
        <?php echo e(Session::forget('media_tab')); ?> 
    <?php endif; ?>
    <?php if(Session::has('recommendation_tab')): ?>
        <script>$('a[href="#tab_2"]').trigger('click');</script> 
        <?php echo e(Session::forget('recommendation_tab')); ?> 
    <?php endif; ?>
    <?php if(Session::has('likes_tab')): ?>
        <script>$('a[href="#tab_3"]').trigger('click');</script> 
        <?php echo e(Session::forget('likes_tab')); ?> 
    <?php endif; ?>
    <?php if(Session::has('report_tab')): ?>
        <script>$('a[href="#tab_5"]').trigger('click');</script> 
        <?php echo e(Session::forget('report_tab')); ?> 
    <?php endif; ?>


    <script>
        var business_id = "<?php echo e($businessDetail->_id); ?>";
        function removeMedia(media_id) {
            $.confirm({
                title: 'Delete Media!',
                content: 'Are you sure you want to delete media!',
                draggable: false,
                type: 'red',
                typeAnimated: true,
                closeIcon: true,
                buttons: {
                    confirm: function () {
                        $.LoadingOverlay("show");
                        $.ajax({
                            url: "<?php echo e(url('admin/business/media/delete')); ?>",
                            type: 'post',
                            data: {media_id: media_id},
                            success: function (data) {
                                if (data.status == true) {
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

        function deleteRecord(url, id, business_id, messageWord) {
            /*console.log('url', baseUrl +'/'+ url);
            console.log('id', id);
            console.log('messageWord', messageWord);*/
            $.confirm({
                title: 'Delete '+ messageWord + '!',
                content: 'Are you sure you want to delete '+ messageWord + '!',
                draggable: false,
                type: 'red',
                typeAnimated: true,
                closeIcon: true,
                buttons: {
                    confirm: function () {
                        $.LoadingOverlay("show");
                        $.ajax({
                            url: baseUrl +'/'+ url,
                            type: 'post',
                            data: {id: id, business_id: business_id},
                            success: function (data) {
                                if (data.status == true) {
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

        function showLcoation(){
            let lat = "<?php echo e($businessDetail->latitude); ?>";
            let long = "<?php echo e($businessDetail->longitude); ?>";
            if (lat != 0 && long != 0) {
                let Latlong = new google.maps.LatLng(lat, long);
                let MapOptions = {
                    zoom: 18,
                    center: Latlong,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                var map = new google.maps.Map(document.getElementById("business_address"), MapOptions);
                var infowindow = new google.maps.InfoWindow({
                                  content: "<?php echo e($businessDetail->business_address); ?>"
                                });

                let marker = new google.maps.Marker({
                    position: Latlong,
                    map: map,
                    title: "<?php echo e($businessDetail->business_address); ?>"
                });
                marker.addListener('click', function() {
                  infowindow.open(map, marker);
                });
                    

            }
        }

        $(document).ready(function() {
            $('a[href="#tab_4"]').on('click', function() {
                    showLcoation();
            });

            $(document).on("click", "#approved", function(){
                $.confirm({
                    title: 'Change Status!',
                    content: 'Are you sure you want to change status!',
                    draggable: false,
                    type: 'red',
                    typeAnimated: true,
                    closeIcon: true,
                    buttons: {
                        confirm: function () {
                            $.LoadingOverlay("show");
                            $.ajax({
                                url: baseUrl +'/admin/business/update_approve_status',
                                type: 'POST',
                                data: {business_id: business_id, value: 0},
                                success: function (data) {
                                    if (data.status == true) {
                                        $.LoadingOverlay("hide");
                                        success_message(data.message);
                                        let newHtml = `<span class="label label-danger btn-circle pointer" id="not_approved">Not Approved</span>`;
                                        $("#approve_main_div").empty().html(newHtml);
                                    }
                                },
                            });
                        },
                        cancel: function () {
                        }
                    }
                });

                
            });
            $(document).on("click", "#not_approved", function(){

                $.confirm({
                    title: 'Change Status!',
                    content: 'Are you sure you want to change status!',
                    draggable: false,
                    type: 'red',
                    typeAnimated: true,
                    closeIcon: true,
                    buttons: {
                        confirm: function () {
                            $.LoadingOverlay("show");
                            $.ajax({
                                url: baseUrl +'/admin/business/update_approve_status',
                                type: 'POST',
                                data: {business_id: business_id, value: 1},
                                success: function (data) {
                                    if (data.status == true) {
                                        $.LoadingOverlay("hide");
                                        success_message(data.message);
                                        let newHtml = `<span class="label label-success btn-circle pointer" id="approved">Approved</span>`;
                                        $("#approve_main_div").empty().html(newHtml);
                                    }
                                },
                            });
                        },
                        cancel: function () {
                        }
                    }
                });

                
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>