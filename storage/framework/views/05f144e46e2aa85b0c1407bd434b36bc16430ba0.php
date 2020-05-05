<style type="text/css">
    /* .table-checkable tr>td:first-child, tr>th:first-child{
        padding-left: 18px !important;
    } */
</style>
<?php $__env->startSection('content'); ?>

    <div class="page-content">
        <div class="row ">
            <div class="col-md-12">
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Manage Users</span>
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
                                        <input type="text" class="form-control input-inline input-small width_100" name="full_name" placeholder="Name" value="<?php echo e(session('full_name_filter')); ?>">
                                        <input type="hidden" class="form-control form-filter input-sm" name="set_session" placeholder="set_session" value="1">
                                    </div>

                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="user_email" placeholder="Email" value="<?php echo e(session('user_email_filter')); ?>">
                                    </div>

                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control form-filter input-small width_100" name="user_address" placeholder="Address" value="<?php echo e(session('user_address_filter')); ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control form-filter input-small width_100" name="user_phone" placeholder="Phone" value="<?php echo e(session('user_phone_filter')); ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <select name="user_is_address_verified" class="form-control form-filter input-small width_100" id="address-status">
                                            <option value="" <?php echo e(session("user_is_address_verified_filter") === "" ? "selected" : ""); ?>>Select Status</option>
                                            <option value="0" <?php echo e(session("user_is_address_verified_filter") === "0" ? "selected" : ""); ?>>Pending</option>
                                            <option value="1" <?php echo e(session("user_is_address_verified_filter") === "1" ? "selected" : ""); ?>>Waiting</option>
                                            <option value="2" <?php echo e(session("user_is_address_verified_filter") === "2" ? "selected" : ""); ?>>Verfied</option>
                                        </select>
                                    </div>
                                </td>
                                <td valign="top">
                                    <form method="POST" id="search-form" style="margin: 0px" role="form">
                                        <table class="width_100">
                                            <tr>
                                                <td>
                                                    <button type="Submit" class="btn btn-sm green btn-outline margin-bottom height_32">
                                                        <i class="fa fa-search"></i> 
                                                    </button>
                                                </td>
                                                <td>
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
                        <div class="table-container">
                            <table class="table table-bordered width_100" id="datatable">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="20%"> Name</th>
                                        <th width="20%"> Email</th>
                                        <th width="30%"> Address</th>
                                        <th width="11%"> Phone</th>
                                        <th width="11%"> Status</th>
                                        <th width="8%"> Actions</th>
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
<?php $__env->startSection('script'); ?>
    <script>
        let url = '<?php echo e(url('admin/all_users/list/get')); ?>';
        let columns = [
            {data: 'full_name', name: 'full_name'},
            {data: 'user_email', name: 'user_email'},
            {data: 'user_address', name: 'user_address'},
            {data: 'user_phone', name: 'user_phone'},
            {data: 'user_address_status', name: 'user_address_status'},
            {data: 'action', name: 'action'}
        ];
        createDatable(url, columns, [0], "users");
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>