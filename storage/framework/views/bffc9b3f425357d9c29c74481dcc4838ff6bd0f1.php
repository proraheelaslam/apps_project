<?php $__env->startSection('content'); ?>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12 ">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Edit Ads</span>
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
                            <?php echo e(Form::model($ad, array('method' => 'PUT', 'url' => url('admin/ads',$ad->_id), 'class' => 'form-horizontal', 'files' => true))); ?>

                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name</label>
                                    <div class="col-md-9">
                                        <?php echo Form::text('name',$ad->name,['class'=>'form-control','placeholder'=>'Name','required'=>'required']); ?>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Unit id</label>
                                    <div class="col-md-9">
                                        <?php echo Form::text('unit_id',$ad->unit_id,['class'=>'form-control','Unit id'=>'height','required'=>'required']); ?>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">App id</label>
                                    <div class="col-md-9">
                                        <?php echo Form::text('app_id',$ad->app_id,['class'=>'form-control','Unit id'=>'App id','required'=>'required']); ?>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Height</label>
                                    <div class="col-md-9">
                                        <?php echo Form::text('height',$ad->height,['class'=>'form-control','placeholder'=>'height','required'=>'required']); ?>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Width</label>
                                    <div class="col-md-9">
                                        <?php echo Form::text('width',$ad->width,['class'=>'form-control','placeholder'=>'width','required'=>'required']); ?>

                                    </div>
                                </div>
                                <div class="form-actions right">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <button type="button"  onclick="window.location='<?php echo e(url('admin/ads')); ?>'" class="btn default">Cancel</button>
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>