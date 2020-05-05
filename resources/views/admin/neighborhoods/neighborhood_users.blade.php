@extends('admin.layout.app')
<style type="text/css">
    .table-checkable tr>td:first-child, tr>th:first-child{
        padding-left: 18px !important;
    }
</style>
@section('content')
    <div class="page-content">

        <div class="row">
            <div class="col-md-12">
                <!-- Begin: life time stats -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Neighborhood Users</span>
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
                                        <input type="text" class="form-control input-inline input-sm width_100" name="nu_full_name" placeholder="Name" value="{{session('nu_full_name_filter')}}">
                                        <input type="hidden" class="form-control form-filter input-sm" name="set_session" placeholder="set_session" value="1">
                                    </div>
                                     
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-sm width_100" name="nu_user_email" placeholder="Email" value="{{session('nu_user_email_filter')}}">
                                    </div>
                                     
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-sm width_100" name="nu_user_address" placeholder="Address" value="{{session('nu_user_address_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-sm width_100" name="nu_user_phone" placeholder="Phone" value="{{session('nu_user_phone_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <select name="nu_user_address_status" class="form-control input-inline input-sm width_100" id="address-status">
                                            <option value="" {{ session("nu_user_address_status_filter") === "" ? "selected" : ""}}>Select Status</option>
                                            <option value="Pending" {{ session("nu_user_address_status_filter") === "Pending" ? "selected" : ""}}>Pending</option>
                                            <option value="Approved" {{ session("nu_user_address_status_filter") === "Approved" ? "selected" : ""}}>Approved</option>
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
                            <table class="table table-bordered table-checkable width_100" id="datatable">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="20%"> Name</th>
                                        <th> Email</th>
                                        <th width="20%"> Address</th>
                                        <th> Phone</th>
                                        <th> Status</th>
                                        <th> Action</th>
                                    </tr>
                                    
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
        </div>

    </div>
@endsection
@section('script')
    <script>
        let url = '{{ url('admin/neighborhoods/users/get/'.$id) }}';
        let columns = [
            {data: 'full_name', name: 'full_name'},
            {data: 'user_email', name: 'user_email'},
            {data: 'user_address', name: 'user_address'},
            {data: 'user_phone', name: 'user_phone'},
            {data: 'user_address_status', name: 'user_address_status'},
            {data: 'action', name: 'action'}
        ];
        createDatable(url,columns,true, "neighbourhood_users");
    </script>

@endsection