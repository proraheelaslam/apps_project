<?php $__env->startSection('content'); ?>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Role Permission</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="portlet-body">
                                <?php echo Form::model($role, ['method' => 'PUT', 'url' => 'admin/roles/update-permission/'.encodeId($role->id), 'class' => 'm-b']); ?>

                                <div class="box box-solid">
                                    <div class="box-body">
                                        <h4 style="background-color:#f7f7f7; font-size: 18px; padding: 7px 10px; margin-top: 0;">
                                            <?php echo e(ucfirst($role->name)); ?>

                                        </h4>
                                        <div class="col-sm-12 toggle-heading" style="padding-bottom: 15px;">
                                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                <div class="col-sm-3" style="padding-top: 15px;">
                                                    <h7><b><?php echo e(ucwords($permission->name)); ?></b></h7>
                                                    <div class="m-bot20">
                                                        <?php echo Form::checkbox("permissions[]", $permission->name, $role->hasPermissionTo($permission->name),
                                                        ["data-on" => "success",
                                                         'data-on-color'=>'success',
                                                         'class'=>'make-switch',
                                                         "data-off" => "danger",
                                                         'data-off-color'=>'warning'
                                                         ]); ?>

                                                    </div>
                                                </div>


                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions right">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <button type="button"  onclick="window.location='<?php echo e(url('admin/roles')); ?>'" class="btn default">Cancel</button>
                                            <button type="submit" class="btn blue">Save</button>
                                        </div>
                                    </div>
                                </div>


                                <?php echo Form::close(); ?>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>