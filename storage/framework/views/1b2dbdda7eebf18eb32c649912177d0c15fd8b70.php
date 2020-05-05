<?php $__env->startSection('content'); ?>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12 ">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Edit Message Post</span>
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
                            <form method="POST" action="<?php echo e(route('admin.posts.update_message_posts', ['post_id' => $post->_id, 'post_type' => "message"])); ?>" class="form-horizontal">
                                <?php echo e(method_field("PUT")); ?>

                                <?php echo e(csrf_field()); ?>

                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Title</label>
                                    <div class="col-md-7">
                                        <textarea class="form-control" name="title" maxlength="500" placeholder="Title" required=""><?php echo e((old('title'))? old('title') : $post->upost_title); ?></textarea>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Description</label>
                                    <div class="col-md-7">
                                        <textarea class="form-control" name="description" placeholder="Post Description" required=""><?php echo e((old('description'))? old('description') : $post->upost_description); ?></textarea>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-actions right">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <button type="button"  onclick="window.location='<?php echo e(url('admin/message_posts')); ?>'" class="btn default">Cancel</button>
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