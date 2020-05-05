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
                            <span class="caption-subject font-dark sbold uppercase">Manage Classifieds</span>
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
                                        <input type="text" class="form-control input-inline input-small width_100" name="classified_title" placeholder="Title" value="{{session('classified_title_filter')}}">
                                        <input type="hidden" class="form-control form-filter input-sm" name="set_session" placeholder="set_session" value="1">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="number" class="form-control input-inline input-small width_100" name="classified_price" placeholder="Price" value="{{session('classified_price_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <select name="classified_neighborhood" class="form-control form-filter input-small width_100" id="classified-neighborhood">
                                            <option value="" {{ session("classified_neighborhood_filter") === "" ? "selected" : ""}}>Select Neighborhood</option>
                                            @foreach($neighborhoods as $k => $neighborhood)
                                                <option value="{{$neighborhood->_id}}" {{ session("classified_neighborhood_filter") === $neighborhood->_id ? "selected" : ""}}>{{$neighborhood->neighborhood_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="classified_created_by" placeholder="Created By" value="{{session('classified_created_by_filter')}}">
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
                            <table class="table table-bordered table-responsive width_100" id="datatable">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="15%"> Title </th>
                                        <th width="15%"> Price </th>
                                        <th width="15%"> Neighborhood </th>
                                        <th width="10%"> Created By </th>
                                        <th width="20%"> Actions</th>
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
                            <span class="caption-subject font-dark sbold uppercase">Classifieds</span>
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
                                        <input type="text" class="form-control input-inline input-small width_100" name="classified_title" placeholder="Title" value="{{session('classified_title_filter')}}">
                                        <input type="hidden" class="form-control form-filter input-sm" name="set_session" placeholder="set_session" value="1">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="number" class="form-control input-inline input-small width_100" name="classified_price" placeholder="Price" value="{{session('classified_price_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                    	<select name="classified_neighborhood" class="form-control form-filter input-small width_100" id="classified-neighborhood">
                                            <option value="" {{ session("classified_neighborhood_filter") === "" ? "selected" : ""}}>Select Neighborhood</option>
                                            @foreach($neighborhoods as $k => $neighborhood)
                                            	<option value="{{$neighborhood->neighborhood_name}}" {{ session("classified_neighborhood_filter") === $neighborhood->neighborhood_name ? "selected" : ""}}>{{$neighborhood->neighborhood_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="classified_created_by" placeholder="Created By" value="{{session('classified_created_by_filter')}}">
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
                                        <th width="15%"> Title </th>
                                        <th width="15%"> Price </th>
                                        <th width="15%"> Neighborhood </th>
                                        <th width="10%"> Created By </th>
                                        <th width="18%"> Actions</th>

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
		let url = '{{ url('admin/classifieds/get') }}';
		let columns = [
				{data: 'title', name: 'title'},
				{data: 'classified_price', name: 'classified_price'},
				{data: 'neighborhood_name', name: 'neighborhood_name'},
				{data: 'created_by', name: 'created_by'},
				{data: 'action', name: 'action'},
			];

		createDatable(url, columns, [0], "classifieds");


		// ----------------------------------------------------------
		$(document).ready(function(){
			
		});
	</script>
@endsection
