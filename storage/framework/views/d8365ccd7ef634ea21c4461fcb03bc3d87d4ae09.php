<style type="text/css">
    .table-checkable tr>td:first-child, tr>th:first-child{
        padding-left: 18px !important;
    }
</style>
<?php $__env->startSection('content'); ?>

    <div class="page-content">
        <div class="row ">
            <div class="col-md-12">
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Manage User Alerts</span>
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
                                        <input type="text" class="form-control input-inline input-small width_100" name="alert_user" placeholder="User" value="<?php echo e(session('alert_user_filter')); ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <select name="alert_neighborhood_name" class="form-control form-filter input-small width_100" id="alert-neighborhood">
                                            <option value="" <?php echo e(session("alert_neighborhood_filter") === "" ? "selected" : ""); ?>>Select Neighborhood</option>
                                            <?php $__currentLoopData = $neighborhoods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $neighborhood): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($neighborhood->_id); ?>" <?php echo e(session("alert_neighborhood_name_filter") === $neighborhood->_id ? "selected" : ""); ?>><?php echo e($neighborhood->neighborhood_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <div class="table-container">
                            <table class="table table-bordered  width_100" id="datatable">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th> User </th>
                                        <th> Neighborhood </th>
                                        <th width="25%"> Actions</th>
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
        let url = '<?php echo e(url('admin/posts/get')); ?>';
        let columns = [
            {data: 'post_user', name: 'post_user'},
            {data: 'neighborhood_name', name: 'neighborhood_name'},
            {data: 'action', name: 'action'}
        ];
        createDatable(url, columns, [0], "alert_posts");

    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>