<?php $__env->startSection('content'); ?>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12 ">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Edit Event</span>
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

                        
                        
                            <form method="POST" action="<?php echo e(route('admin.events.update', ['event_id' => $event->_id])); ?>" class="form-horizontal">
                                <?php echo e(method_field("PUT")); ?>

                                <?php echo e(csrf_field()); ?>

                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Title</label>
                                    <div class="col-md-7">
                                        <textarea class="form-control" maxlength="500" rows="3" name="title" placeholder="Title"><?php echo e((old('title'))? old('title') : $event->title); ?></textarea>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Category</label>
                                    <div class="col-md-7">
                                        <select name="category" class='form-control' required required>
                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(old('category')): ?>
                                                    <option <?php echo e((old('category') == $category->_id) ? 'selected' : ''); ?> value="<?php echo e($category->_id); ?>" selected=""> <?php echo e($category->name); ?></option>    
                                                <?php else: ?>
                                                    <option <?php echo e(($category->_id == $event->ecategory_id) ? 'selected' : ''); ?>  value="<?php echo e($category->_id); ?>"> <?php echo e($category->name); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Date</label>
                                    <div class="col-md-7">
                                        <div class="input-group date form_meridian_datetime" data-date="<?php date('Y-m-d').'T10:02:00Z' ?>">
                                            <input type="text" name="event_date_time" value="<?php echo e((old('event_date_time') ? old('event_date_time') : date('Y-M-d h:i A' , strtotime($event->event_date)))); ?>"  size="16" class="form-control" placeholder="Event Date Time" maxlength="50">
                                            <span class="input-group-btn">
                                                <button class="btn default date-reset" type="button">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <button class="btn default date-set" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Location</label>
                                    <div class="col-md-7">
                                        <input class="form-control" placeholder="Location" required="required" name="location" type="text" value="<?php echo e((old('location'))? old('location') : $event->event_locations); ?>" maxlength="150">
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Description</label>
                                    <div class="col-md-7">
                                        <textarea class="form-control" rows="3" name="description" placeholder="Event Description"><?php echo e((old('description'))? old('description') : $event->event_description); ?></textarea>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-actions right">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <button type="button"  onclick="window.location='<?php echo e(url('admin/events')); ?>'" class="btn default">Cancel</button>
                                            <button type="submit" class="btn blue">Save</button>
                                        </div>
                                    </div>
                                </div>
                                </form>
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
    var selectedNeighborhoods = '';
    $(document).ready(function(){
        $(".select2-multiple").select2({
            placeholder: "Select Roles",
            width: null
        });
        $('.select2-multiple').val(selectedNeighborhoods).trigger("change");

        $(".form_meridian_datetime").datetimepicker({
            isRTL: App.isRTL(),
            // format: "dd MM yyyy - HH:ii P",
            format: "yyyy-M-dd  HH:ii P",
            showMeridian: true,
            autoclose: true,
            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
            todayBtn: true
        });
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