<?php $__env->startSection('content'); ?>
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <div class="blog-page blog-content-1">
            <div class="row">
                <div class="col-lg-12">
                    <div class="blog-post-lg bordered blog-container">
                        <h3 class="page-title"> Classified Detail
                            <div class="pull-right">
                                <a href="<?php echo e(url('admin/classifieds')); ?>" type="button" class="btn btn-primary">Back</a>
                            </div>
                            <!-- <small>dashboard & statistics</small> -->
                        </h3>
                        <div class="blog-post-content">
                            <h2 class="blog-title blog-post-title">
                                <a href="javascript:;"><?php echo e($classifiedDetail->classified_title); ?></a>
                            </h2>
                            <p class="blog-post-desc"> <?php echo e($classifiedDetail->classified_description); ?> </p>
                            <div class="blog-post-foot">
                                 <ul class="blog-post-tags">
                                     <li class="uppercase">
                                        <i class="fa fa-money"></i>
                                        <a href="javascript:;"><?php echo e($classifiedDetail->classified_price); ?></a>
                                     </li>
                                 </ul>
                            </div>


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
                                            <span class="badge badge-danger"> <?php echo e(count($classifiedDetail->classified_images)); ?></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab_2" data-toggle="tab" aria-expanded="true"> Product Offers 
                                            <span class="badge badge-danger"> <?php echo e(count($classifiedDetail->offers)); ?> </span>
                                        </a>
                                    </li>
                                    
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="table-container" style="">
                                            <?php if(count($classifiedDetail->classified_images) > 0): ?>
                                                <div class="portfolio-content portfolio-1">
                                                    <div id="js-grid-juicy-projects" class="cbp">
                                                        <?php $__currentLoopData = $classifiedDetail->classified_images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classifiedImg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="cbp-item graphic "
                                                                     style="width: 342px;left: 0;top: 241px;">
                                                                <div class="cbp-caption">
                                                                    <div class="cbp-caption-defaultWrap">
                                                                        <img src="<?php echo e($classifiedImg->full_classified_image); ?>"
                                                                             alt="" style="height: 300px"></div>
                                                                    <div class="cbp-caption-activeWrap">
                                                                        <div class="cbp-l-caption-alignCenter">
                                                                            <div class="cbp-l-caption-body">
                                                                                <a href="javascript:void(0)"
                                                                                   class="  btn red uppercase btn red uppercase"
                                                                                   onclick="removeClassifiedMedia('<?php echo e($classifiedImg->_id); ?>')">Remove</a>
                                                                                <a href="<?php echo e($classifiedImg->full_classified_image); ?>"
                                                                                   class="cbp-lightbox cbp-l-caption-buttonRight btn red uppercase btn red uppercase"
                                                                                   data-title="Dashboard<br>by Paul Flavius Nechita">view
                                                                                    larger</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
                                                        <i class="fa fa-users"></i>Offers
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th width="20%"> User Name</th>
                                                                <th width="10%"> Offer Price</th>
                                                                <th width="55%"> Comment</th>
                                                                <th width="15%"> Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if(count($classifiedDetail->Offers) > 0): ?>
                                                                <?php $__currentLoopData = $classifiedDetail->Offers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <tr>
                                                                        <td><?php echo e($offer->users->user_fname .' '.$offer->users->user_lname); ?></td>
                                                                        <td><?php echo e($offer->coffer_price); ?></td>
                                                                        <td><?php echo e($offer->coffer_comments); ?></td>
                                                                        <td>
                                                                            <a title="Delete Offer"
                                                                               onclick="deleteOffer('<?php echo e($offer->_id); ?>')"
                                                                               href="javascript:void(0)"
                                                                               class="btn-delete btn btn-sm btn-circle btn-default btn-editable">
                                                                                <i class="fa fa-trash"></i> Delete</a>
                                                                        </td>

                                                                    </tr>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php else: ?>
                                                                <tr>
                                                                    <td colspan="3"><p class="text-center">No record
                                                                            found</p></td>
                                                                </tr>
                                                            <?php endif; ?>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

<style>
</style>
<?php $__env->startSection('script'); ?>


    <?php if(Session::has('media_tab')): ?>
        <script>$('a[href="#tab_1"]').trigger('click');</script> <?php echo e(Session::forget('media_tab')); ?> 
    <?php endif; ?>

    <?php if(Session::has('offer_tab')): ?>
        <script>
            $('a[href="#tab_2"').trigger('click');
        </script>
        <?php echo e(Session::forget('offer_tab')); ?>

    <?php endif; ?>

    <script>

        function removeClassifiedMedia(media_id) {
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
                            url: "<?php echo e(url('admin/classified/media/delete')); ?>",
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
        function deleteOffer(offer_id) {

            $.confirm({
                title: 'Delete Offer!',
                content: 'Are you sure you want to delete offer!',
                draggable: false,
                type: 'red',
                typeAnimated: true,
                closeIcon: true,
                buttons: {
                    confirm: function () {
                        $.LoadingOverlay("show");

                        $.ajax({
                            url: "<?php echo e(url('admin/classified/offer/delete')); ?>",
                            type: 'post',
                            data: { offer_id: offer_id },
                            success: function (data) {
                                console.log(data);
                                if (data.status == true) {
                                    $.LoadingOverlay("hide");
                                    success_message(data.message);
                                    setTimeout(function () {
                                        location.reload();
                                    }, 100);
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>