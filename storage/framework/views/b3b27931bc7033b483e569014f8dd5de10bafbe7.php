<style type="text/css">
    .table-checkable tr>td:first-child, tr>th:first-child{
        padding-left: 18px !important;
    }
</style>

<?php $__env->startSection("content"); ?>

    <div class="page-content">
        <div class="row ">
            <div class="col-md-12">
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Manage Businesses</span>
                        </div>
                    </div>
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Filter Results</span>
                        </div>
                        <table class="table table-light responsive_filterHead">
                            <tr role="row" class="filter">
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="business_name" placeholder="Name" value="<?php echo e(session('business_name_filter')); ?>">
                                        <input type="hidden" class="form-control form-filter input-sm" name="set_session" placeholder="set_session" value="1">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="business_phone" placeholder="Phone" value="<?php echo e(session('business_phone_filter')); ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <select name="business_neighborhood" class="form-control form-filter input-small width_100" id="business-neighborhood">
                                            <option value="" <?php echo e(session("business_neighborhood_filter") === "" ? "selected" : ""); ?>>Select Neighborhood</option>
                                            <?php $__currentLoopData = $neighborhoods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $neighborhood): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($neighborhood->_id); ?>" <?php echo e(session("business_neighborhood_filter") === $neighborhood->_id ? "selected" : ""); ?>><?php echo e($neighborhood->neighborhood_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="business_created_by" placeholder="Created By" value="<?php echo e(session('business_created_by_filter')); ?>">
                                    </div>
                                </td>

                                <td>
                                    <div class="input-group width_100">
                                        <select name="business_approved" class="form-control form-filter input-small width_100" id="business_approved">
                                            <option value="" <?php echo e(session("business_approved_filter") === "" ? "selected" : ""); ?>>Select Approval Staus</option>
                                            <option value="1" <?php echo e(session("business_approved_filter") === "1" ? "selected" : ""); ?>>Approved</option>
                                            <option value="0" <?php echo e(session("business_approved_filter") === "0" ? "selected" : ""); ?>>Not Approved</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <form method="POST" id="search-form" style="margin: 0px" role="form">
                                        <table class="width_100">
                                            <tr>
                                                <td>
                                                    <button type="Submit" class="btn btn-sm green btn-outline margin-bottom height_32">
                                                        <i class="fa fa-search"></i> 
                                                    </button>
                                                </td>
                                                <td class="width_100">
                                                    <button class="btn btn-sm red btn-outline reset-filter height_32">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container table-responsive">
                            <table class="table table-bordered  width_100" id="datatable">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="15%"> Name </th>
                                        
                                        <th width="10%"> Phone </th>
                                        <th width="15%"> Neighborhood </th>
                                        <th width="18%"> Created By </th>
                                        <th width="15%"> Approved </th>
                                        <th width="27%"> Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection("script"); ?>
	<script type="text/javascript">
		let url = '<?php echo e(url('admin/businesses/get')); ?>';
		let columns = [
				{data: 'business_name', name: 'business_name'},
				// {data: 'business_address', name: 'business_address'},
                {data: 'business_phone', name: 'business_phone'},
				{data: 'neighborhood_name', name: 'neighborhood_name'},
				{data: 'created_by', name: 'created_by'},
				{data: 'business_is_approved', name: 'business_is_approved'},
				{data: 'action', name: 'action'},
			];

		createDatable(url, columns, [0], "businesses");


		// ----------------------------------------------------------
		$(document).ready(function(){
			/*$(".form_meridian_datetime").datetimepicker({
	            isRTL: App.isRTL(),
	            // format: "dd MM yyyy - HH:ii P",
	            format: "yyyy-M-dd  HH:ii P",
	            showMeridian: true,
	            autoclose: true,
	            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
	            todayBtn: true
	        });*/

	        $('.date-picker').datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true
            });
		});
	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("admin.layout.app", \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>