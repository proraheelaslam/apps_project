@extends("admin.layout.app")
<style type="text/css">
    /* .table-checkable tr>td:first-child, tr>th:first-child{
        padding-left: 18px !important;
    } */
    /* .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
        padding: 3px !important;
    } */
</style>

@section("content")

    <div class="page-content">
        <div class="row ">
            <div class="col-md-12">
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Manage Appication Notification</span>
                        </div>
                    </div>
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Filter Results</span>
                        </div>
                       {{-- <div class="actions">
                            <div class="btn-group">
                                <a class="btn btn-circle btn-primary" href="{{url('admin/app_notification/create')}}"> Add New</a>
                            </div>
                        </div>--}}
                        <table class="table table-light">
                            <tr role="row" class="filter">
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control input-inline input-medium" name="app_notification_name" placeholder="Name" value="{{session('app_notification_name_filter')}}">
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
                                        <th width="30%"> Name </th>
                                        <th width="70%"> Message </th>
                                        <th width="30%"> Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section("script")
	<script type="text/javascript">
		let url = '{{ url('admin/app_notification/get') }}';
		let columns = [
				{data: 'name', name: 'name'},
				{data: 'message', name: 'message'},
				{data: 'action', name: 'action'},
			];

		createDatable(url, columns, [0], "app_notification");


		// ----------------------------------------------------------
	</script>
@endsection
