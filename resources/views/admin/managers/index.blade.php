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
                            <span class="caption-subject font-dark sbold uppercase">Manage Admin Managers</span>
                        </div>
                        @can('add managers')
                            <a href="{{url('admin/managers/create')}}" type="button" class="btn btn-primary pull-right">Add
                                User</a>
                        @endcan
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
                                        <input type="text" class="form-control input-inline input-small width_100" name="name" placeholder="Name" value="{{session('name_filter')}}">
                                        <input type="hidden" class="form-control form-filter input-sm" name="set_session" placeholder="set_session" value="1">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="email" placeholder="Email" value="{{session('email_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="role" placeholder="Role" value="{{session('role_filter')}}">
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
                                        <th> Name</th>
                                        <th> Email</th>
                                        <th> Role </th>
                                        <th> Action</th>
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
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Manage Users</span>
                        </div>
                        @can('add managers')
                            <a href="{{url('admin/managers/create')}}" type="button" class="btn btn-primary pull-right">Add
                                User</a>
                        @endcan
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
                                        <input type="text" class="form-control input-inline input-small width_100" name="name" placeholder="Name" value="{{session('name_filter')}}">
                                        <input type="hidden" class="form-control form-filter input-sm" name="set_session" placeholder="set_session" value="1">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="email" placeholder="Email" value="{{session('email_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="role" placeholder="Role" value="{{session('role_filter')}}">
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
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="portlet-body table-responsive">
                                <table class="table table-striped table-bordered table-hover table-checkable order-column"
                                       id="datatable">
                                    <thead>
                                    <tr>
                                        <th> Name</th>
                                        <th> Email</th>
                                        <th> Role </th>
                                        <th> Action</th>
                                    </tr>

                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div> --}}
@endsection
@section('script')
    <script>
        let url = '{{ url('admin/managers/get') }}';
        let columns = [
            {data: 'name', name: 'name', "width": "30%"},
            {data: 'email', name: 'email'},
            {data: 'role', name: 'role.name'},
            {data: 'action', name: 'action'}
        ];
        createDatable(url, columns, [0],"managers");

    </script>

@endsection