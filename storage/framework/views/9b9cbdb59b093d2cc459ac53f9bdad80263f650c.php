<?php $__env->startSection('content'); ?>
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <div class="blog-page blog-content-1">


            <div class="row">
                <div class="col-lg-12">
                    <div class="blog-post-lg bordered blog-container">
                            <!-- <small>dashboard & statistics</small> -->
                            <div class="portlet-body">
                                <div class="col-md-12 col-sm-12">
                                    <!-- BEGIN PORTLET-->
                                    <div class="portlet light bordered">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="icon-bubble font-hide hide"></i>
                                                <span class="caption-subject font-hide bold uppercase">Chats</span>
                                            </div>
                                            <div class="actions">
                                                <div class="portlet-input input-inline">
                                                    <div class="input-icon right">
                                                        <a href="<?php echo e(url('admin/users')); ?>" type="button" class="btn btn-primary">Back</a> </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body" id="chats">
                                            <div class="scroller" style="height: 525px;" data-always-visible="1" data-rail-visible1="1">
                                                <ul class="chats">
                                                    
                                                    <?php $__currentLoopData = $threadMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                        <?php if($message->cthread_to_user_id == $userId): ?>
                                                        <li class="out">
                                                            <img class="avatar" alt="" src="<?php echo e($message->to_user->full_image); ?>" />
                                                            <div class="message">
                                                                <a title="Delete Answer" onclick="deleteMessage('<?php echo e($message->_id); ?>')" href="javascript:void(0)" class="pull-left btn-delete btn btn-sm btn-circle btn-default btn-editable">
                                                                    <i class="fa fa-trash"></i> Delete</a>
                                                                <span class="arrow"> </span>
                                                                
                                                                <p><b><?php echo e($message->to_user->full_name); ?></b></p>
                                                                <span class="datetime">  </span>
                                                                <?php if($message->type == 'message'): ?>
                                                                <span class="body">  <?php echo e($message->cthread_message); ?> </span>
                                                                    
                                                                <?php elseif($message->type == 'image'): ?>
                                                                    <img style="width: 39%; height: 137px;"alt="" src="<?php echo e($message->to_user->full_image); ?>" />
                                                                <?php endif; ?>
                                                            </div>
                                                        </li>
                                                        <?php else: ?>
                                                        <li class="in">

                                                            <img class="avatar" alt="" src="<?php echo e($message->to_user->full_image); ?>" />
                                                            <div class="message">

                                                                <a title="Delete Answer" onclick="deleteMessage('<?php echo e($message->_id); ?>')" href="javascript:void(0)" class="pull-right btn-delete btn btn-sm btn-circle btn-default btn-editable">
                                                                    <i class="fa fa-trash"></i> Delete</a>
                                                                <span class="arrow"> </span>
                                                                <p><b><?php echo e($message->to_user->full_name); ?></b></p>
                                                                <?php if($message->type == 'message'): ?>
                                                                    <span class="body">  <?php echo e($message->cthread_message); ?> </span>
                                                                    
                                                                <?php elseif($message->type == 'image'): ?>
                                                                    <img style="width: 39%; height: 137px;"alt="" src="<?php echo e($message->to_user->full_image); ?>" />
                                                                <?php endif; ?>
                                                            </div>
                                                        </li>
                                                        <?php endif; ?>
                                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                                </ul>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- END PORTLET-->
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
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    function deleteMessage(messageId) {

        $.confirm({
            title: 'Delete Messsage',
            content: 'Are you sure you want to delete!',
            draggable: false,
            type: 'red',
            typeAnimated: true,
            closeIcon: true,
            buttons: {
                confirm: function () {
                    $.LoadingOverlay("show");
                    $.ajax({
                        url: baseUrl +'/admin/chat/message/delete',
                        type: 'post',
                        data: { messageId: messageId},
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
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>