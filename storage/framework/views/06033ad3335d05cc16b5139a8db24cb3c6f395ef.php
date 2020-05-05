<?php $__env->startSection('content'); ?>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12 ">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Update Profile</span>
                        </div>
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
                        <?php echo Form::open(['url'=>'admin/profile/update','method' => 'post','class'=>
                                    'form-horizontal','data-toggle'=>'validator'
                            ,'role'=>'form','enctype'=>'multipart/form-data']); ?>

                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name</label>
                                <div class="col-md-9">
                                    <?php echo Form::text('name',$user->name,['class'=>'form-control','placeholder'=>'Name','required'=>'required']); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Profile</label>
                                <div class="col-md-9">
                                    <div class="dragDrop_box">
                                        <small>
                                            <?php if(isset($user->full_image)): ?>
                                                <img name="image" style="width: 200px; height: 200px;" onclick="BrowseFile()" src="<?php echo e($user->full_image); ?>" alt="#" id="profile_image">
                                            <?php endif; ?>
                                        </small>
                                        <div class="dragDrop_brows">
                                            <input type="file"  onchange="readImageURL(this);"  name="profile_image" id="image" accept="image/*">
                                            BROWSE</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions right">
                                <div class="row">
                                    <div class="col-md-offset-4 col-md-8">
                                        <button type="button"  onclick="window.location='<?php echo e(url('admin/home')); ?>'" class="btn default">Cancel</button>
                                        <button type="submit" class="btn blue">Save</button>
                                    </div>
                                </div>
                            </div>
                            <?php echo Form::close(); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <!-- END DASHBOARD STATS 1-->
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    function BrowseFile() {
        $("input[id='image']").click();
    }
    function readImageURL(input)
    {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#profile_image')
                    .attr('src', e.target.result)
                    .width(200)
                    .height(200);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>