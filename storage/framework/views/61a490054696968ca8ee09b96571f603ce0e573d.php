<?php $__env->startSection('content'); ?>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12 ">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Edit Manager</span>
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
                            <?php echo e(Form::model($manager, array('method' => 'PUT', 'url' => url('admin/managers',$manager->_id), 'class' => 'form-horizontal', 'files' => true))); ?>

                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name</label>
                                    <div class="col-md-9">
                                        <?php echo Form::text('name',$manager->name,['class'=>'form-control','placeholder'=>' name','required'=>'required']); ?>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Email</label>
                                    <div class="col-md-9">
                                        <?php echo Form::text('email',$manager->email,['class'=>'form-control','placeholder'=>' email','required'=>'required']); ?>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Country</label>
                                    <div class="col-md-9">
                                        <select name="country" class='form-control' id="country">
                                            <option value="">Select Country</option>
                                            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option  value="<?php echo e($country->country_id); ?>" <?php echo e(($manager->country_id == $country->country_id) ? "selected":""); ?>> <?php echo e($country->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">State</label>
                                    <div class="col-md-9">
                                        <select name="state" class='form-control' id="state">
                                            <option value="">Select State</option>
                                            <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option  value="<?php echo e($state->state_id); ?>" <?php echo e(($manager->state_id == $state->state_id) ? "selected":""); ?>> <?php echo e($state->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">City</label>
                                    <div class="col-md-9">
                                        <select name="city" class='form-control' id="city">
                                            <option value="">Select City</option>
                                            <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option  value="<?php echo e($city->city_id); ?>" <?php echo e(($manager->city_id == $city->city_id) ? "selected":""); ?>> <?php echo e($city->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Roles</label>
                                    <div class="col-md-9">
                                        <select name="role" class='form-control' required>
                                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php echo e(($role == $selectedRole) ? 'selected' : ''); ?>  value="<?php echo e($k); ?>_<?php echo e($role); ?>"> <?php echo e($role); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Neighborhood</label>
                                    <div class="col-md-9">
                                        <select name="neighborhoods[]" class='form-control select2-multiple' multiple>
                                            <?php $__currentLoopData = $neighborhoods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $neighborhood): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option  value="<?php echo e($neighborhood->_id); ?>" > <?php echo e($neighborhood->neighborhood_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="form-actions right">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <button type="button"  onclick="window.location='<?php echo e(url('admin/managers')); ?>'" class="btn default">Cancel</button>
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
    var APP_URL = <?php echo json_encode(url('/')); ?>;
    var selectedNeighborhoods = <?php echo json_encode($manager->neighborhoods); ?>;
    $(document).ready(function(){
        $(".select2-multiple").select2({
            placeholder: "Select Neighborhood",
            width: null
        });
        $('.select2-multiple').val(selectedNeighborhoods).trigger("change");


        $("#country").on("change", function(e){
            e.preventDefault();
            let urlPart = "/admin/states/get/"+parseInt($("#country :selected").val());
            $.LoadingOverlay("show");
            $.ajax({
                type: "GET",
                cache: false,
                url: APP_URL+urlPart,
                // dataType: "json",
                success: function(res) 
                {   $.LoadingOverlay("hide");
                    // console.log(res.data);
                    let states = res.data;
                    // console.log(data);
                    let options = "<option value=''>Select State</option>";
                    $.each(states, function (index, state) {
                            options += "<option value='"+state.state_id+"'>"+state.name+"</option>";
                        }
                    ); 
                    $("#state").empty().html(options);
                },
                error: function(xhr, ajaxOptions, thrownError)
                {
                    $.LoadingOverlay("hide");
                },
                async: false
            });
        });

        $("#state").on("change", function(e){
            e.preventDefault();
            let urlPart = "/admin/cities/get/"+parseInt($("#state :selected").val());
            $.LoadingOverlay("show");
            $.ajax({
                type: "GET",
                cache: false,
                url: APP_URL+urlPart,
                success: function(res) 
                {   
                    $.LoadingOverlay("hide");
                    let cities = res.data;
                    let options = "<option value=''>Select City</option>";
                    $.each(cities, function (index, city) {
                            options += "<option value='"+city.city_id+"'>"+city.name+"</option>";
                        }
                    ); 
                    $("#city").empty().html(options);
                },
                error: function(xhr, ajaxOptions, thrownError)
                {
                    $.LoadingOverlay("hide");
                },
                async: false
            });
        });
    });
    
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>