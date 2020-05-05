<?php $__env->startSection('content'); ?>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12 ">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Add Category</span>
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
                            <?php echo e(Form::model($email, array('method' => 'PUT', 'url' => url('admin/emails',$email->_id), 'class' => 'form-horizontal', 'files' => true))); ?>

                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name</label>
                                    <div class="col-md-9">
                                        <?php echo Form::text('name',$email->name,['class'=>'form-control','placeholder'=>'name','required'=>'required']); ?>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">From</label>
                                    <div class="col-md-9">
                                        <?php echo Form::text('from',$email->from,['class'=>'form-control','placeholder'=>'from','required'=>'required','disabled'=>'disabled']); ?>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Subject</label>
                                    <div class="col-md-9">
                                        <?php echo Form::text('subject',$email->subject,['class'=>'form-control','placeholder'=>'subject','required'=>'required']); ?>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Content</label>
                                    <div class="col-md-9">
                                        <?php echo Form::textarea('email_content',$email->content,['class'=>'form-control description' ,'id'=>'content','placeholder'=>'Content','required'=>'required']); ?>

                                    </div>
                                </div>
                                <div class="form-actions right">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <button type="button"  onclick="window.location='<?php echo e(url('admin/emails')); ?>'" class="btn default">Cancel</button>
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