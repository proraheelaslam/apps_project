@extends('admin.layout.app')
<style type="text/css">
    .table-checkable tr>td:first-child, tr>th:first-child{
        padding-left: 18px !important;
    }
</style>
@section('content')

    <div class="page-content">
        <div class="row ">
            <div class="col-md-12">
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Manage Neighborhoods</span>
                        </div>
                    </div>
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Filter Results</span>
                        </div>
                        {{-- {{ asset("theme/images/loading_spinner.gif") }} --}}
                        {{-- <img src="http://localhost:8000/theme/images/loading_spinner.gif" alt="Processing..." style="width:70px; height:70px; background-image:none" top="40%" left="50%"> --}}
                        <table class="table table-light responsive_filterHead">
                            <tr role="row" class="filter">
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-sm width_100" name="neighborhood_name" placeholder="Name" value="{{session('neighborhood_name_filter')}}">
                                        <input type="hidden" class="form-control form-filter input-sm" name="set_session" placeholder="set_session" value="1">
                                    </div>

                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control form-filter input-sm width_100" name="neighborhood_address" placeholder="Address" value="{{session('neighborhood_address_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="number" class="form-control form-filter input-sm width_100" name="neighborhood_total_users" placeholder="Total Users" value="{{session('neighborhood_total_users_filter')}}" min="0"   onkeypress="return event.charCode != 45">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <select name="verified_by_admin" class="form-control form-filter input-sm width_100" id="input-status">
                                            <option value="" {{ session("verified_by_admin_filter") == "" ? "selected" : ""}}>Select Status</option>
                                            <option value="0" {{ session("verified_by_admin_filter") == "0" ? "selected" : ""}}>Pending</option>
                                            <option value="1" {{ session("verified_by_admin_filter") == "1" ? "selected" : ""}}>Approved</option>
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
                                        <th width="30%"> Name </th>
                                        <th width="35%"> Address </th>
                                        <th width="10%"> Total users </th>
                                        <th width="10%"> Status </th>
                                        <th width="15%"> Actions </th>
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
@section('script')
    <script>
        let url = '{{ url('admin/neighborhoods/get') }}';
        console.log("url", url);
        let columns = [
            {data: 'neighborhood_name', name: 'neighborhood_name'},
            {data: 'neighborhood_address', name: 'neighborhood_address'},
            {data: 'neighborhood_total_users', name: 'neighborhood_total_users'},
            {data: 'neighbourhood_is_verify', name: 'neighbourhood_is_verify'},
            {data: 'action', name: 'action'}
        ];
        createDatable(url, columns, [0], "neighbourhood");




        /*var TableDatatablesAjax = function () {

                var handleRecords = function () {

                    var grid = new Datatable();

                    grid.init({
                        src: $("#datatable_ajax_nextneighbour"),
                        onSuccess: function (grid, response) {
                            // grid:        grid object
                            // response:    json object of server side ajax response
                            // execute some code after table records loaded
                        },
                        onError: function (grid) {
                            // execute some code on network or other general error  
                        },
                        onDataLoad: function(grid) {
                            // execute some code on ajax data load
                        },
                        loadingMessage: 'Loading...',
                        dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                            // So when dropdowns used the scrollable div should be removed. 
                            //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                            
                            "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

                            "lengthMenu": [
                                [10, 20, 50, 100, 150, -1],
                                [10, 20, 50, 100, 150, "All"] // change per page values here
                            ],
                            "pageLength": 10, // default record count per page
                            "ajax": {
                                "url": url, // ajax source
                            },
                            "order": [
                                [1, "asc"]
                            ]// set first column as a default sort by asc
                        }
                    });

                    // handle group actionsubmit button click
                    grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
                        e.preventDefault();
                        var action = $(".table-group-action-input", grid.getTableWrapper());
                        if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                            grid.setAjaxParam("customActionType", "group_action");
                            grid.setAjaxParam("customActionName", action.val());
                            grid.setAjaxParam("id", grid.getSelectedRows());
                            grid.getDataTable().ajax.reload();
                            grid.clearAjaxParams();
                        } else if (action.val() == "") {
                            App.alert({
                                type: 'danger',
                                icon: 'warning',
                                message: 'Please select an action',
                                container: grid.getTableWrapper(),
                                place: 'prepend'
                            });
                        } else if (grid.getSelectedRowsCount() === 0) {
                            App.alert({
                                type: 'danger',
                                icon: 'warning',
                                message: 'No record selected',
                                container: grid.getTableWrapper(),
                                place: 'prepend'
                            });
                        }
                    });

                    //grid.setAjaxParam("customActionType", "group_action");
                    //grid.getDataTable().ajax.reload();
                    //grid.clearAjaxParams();
                }

                return {

                    //main function to initiate the module
                    init: function () {
                        handleRecords();
                    }

                };

            }();

            jQuery(document).ready(function() {
                TableDatatablesAjax.init();
            });*/

    </script>

@endsection