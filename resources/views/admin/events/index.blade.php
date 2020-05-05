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
                            <span class="caption-subject font-dark sbold uppercase">Manage Events</span>
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
                                        <input type="text" class="form-control input-inline input-small width_100" name="event_title" placeholder="Title" value="{{session('event_title_filter')}}">
                                        <input type="hidden" class="form-control form-filter input-sm" name="set_session" placeholder="set_session" value="1">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="event_location" placeholder="Location" value="{{session('event_location_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <select name="event_neighborhood" class="form-control form-filter input-small width_100" id="neighborhood">
                                            <option value="" {{ session("event_neighborhood_filter") === "" ? "selected" : ""}}>Select Neighborhood</option>
                                            @foreach($neighborhoods as $k => $neighborhood)
                                                <option value="{{$neighborhood->_id}}" {{ session("event_neighborhood_filter") === $neighborhood->_id ? "selected" : ""}}>{{$neighborhood->neighborhood_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="event_created_by" placeholder="Created By" value="{{session('event_created_by_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                                        <input type="text" name="event_date_time" value="{{session('event_date_time_filter')}}"  size="16" class="form-control" placeholder="Event Date">
                                        <span class="input-group-btn">
                                            <button class="btn default height_34" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
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
                                        <th width="13%"> Title </th>
                                        <th width="15%"> Location </th>
                                        <th width="15%"> Neighborhood </th>
                                        <th width="12%"> Created By </th>
                                        <th width="20%"> Event Date </th>
                                        <th width="23%"> Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


	{{-- <div class="page-content">

		<div class="row">
            <div class="col-md-12">
                <!-- Begin: life time stats -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Events</span>
                        </div>
                    </div>
                    <div class="portlet-title filtersBar">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Filter Results</span>
                        </div>
                        <table class="table table-light responsive_filterHead">
                            <tr role="row" class="filter">
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="event_title" placeholder="Title" value="{{session('event_title_filter')}}">
                                        <input type="hidden" class="form-control form-filter input-sm" name="set_session" placeholder="set_session" value="1">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="event_location" placeholder="Location" value="{{session('event_location_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                    	<select name="event_neighborhood" class="form-control form-filter input-small width_100" id="neighborhood">
                                            <option value="" {{ session("event_neighborhood_filter") === "" ? "selected" : ""}}>Select Neighborhood</option>
                                            @foreach($neighborhoods as $k => $neighborhood)
                                            	<option value="{{$neighborhood->neighborhood_name}}" {{ session("event_neighborhood_filter") === $neighborhood->neighborhood_name ? "selected" : ""}}>{{$neighborhood->neighborhood_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="event_created_by" placeholder="Created By" value="{{session('event_created_by_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group input-medium date date-picker" data-date-format="yyyy-mm-dd">
                                        <input type="text" name="event_date_time" value="{{session('event_date_time_filter')}}"  size="16" class="form-control" placeholder="Event Date">
                                        <span class="input-group-btn">
                                            <button class="btn default height_34" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
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
                            <table class="table table-bordered table-checkable width_100" id="datatable">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="20%"> Title </th>
                                        <th width="20%"> Location </th>
                                        <th width="15%"> Neighborhood </th>
                                        <th width="10%"> Created By </th>
                                        <th width="15%"> Event Date </th>
                                        <th width="20%"> Actions</th>

                                    </tr>

                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
        </div>

	</div> --}}
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
