<?php $__env->startSection('content'); ?>


    <div class="page-content">
        <div class="row ">
            <div class="col-md-12">
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Manage Ads</span>
                        </div>
                    </div>
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Filter Results</span>
                        </div>
                        <div class="actions">
                            <div class="btn-group">
                                <a class="btn btn-circle btn-primary" href="<?php echo e(url('admin/ads/create')); ?>"> Add New </a>
                            </div>
                        </div>
                        <table class="table table-light">
                            <tr role="row" class="filter">
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control input-inline input-medium" name="name" placeholder="Name" value="<?php echo e(session('name_filter')); ?>">
                                        <input type="hidden" class="form-control form-filter input-sm" name="set_session" placeholder="set_session" value="1">
                                    </div>

                                </td>
                                <td class="width_100">
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
                                        <th width="10%"> Name </th>
                                        <th width="10%"> Unit id </th>
                                        <th width="10%"> App id </th>
                                        <th width="10%"> Height </th>
                                        <th width="10%"> Width </th>
                                        <th width="45%"> Actions </th>
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
        let url = '<?php echo e(url('admin/ads/get')); ?>';
        let columns = [
            {data: 'name', name: 'name', "width": "20%"},
            {data: 'unit_id', name: 'unit_id', "width": "20%"},
            {data: 'app_id', name: 'app_id', "width": "20%"},
            {data: 'height', name: 'height', "width": "10%"},
            {data: 'width', name: 'width', "width": "10%"},
            {data: 'action', name: 'action'}
        ];
        createDatable(url, columns, [0], "ads");

    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>