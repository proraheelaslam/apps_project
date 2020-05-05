@extends("admin.layout.app")
<style type="text/css">
    .table-checkable tr>td:first-child, tr>th:first-child{
        padding-left: 18px !important;
    }
</style>

@section("content")

    <div class="page-content">
        <div class="row ">
            <div class="col-md-12">
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Manage Database Backup</span>
                        </div>
                    </div>
                    <div class="form-body">
                       <a href="{{url('admin/db/backup/dump')}}"  class="btn btn-sm green btn-outline margin-bottom  height_32">Export Database</a>

                   </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section("script")
	<script type="text/javascript">
		let url = '{{ url('admin/events/get') }}';
		let columns = [
				{data: 'title', name: 'title'},
				{data: 'event_locations', name: 'event_locations'},
				{data: 'neighborhood_name', name: 'neighborhood_name'},
				{data: 'created_by', name: 'created_by'},
				{data: 'event_date', name: 'event_date'},
				{data: 'action', name: 'action'},
			];

		createDatable(url, columns, [0], "events");


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
@endsection
