@extends('admin.layout.app')
<style type="text/css">
    .table-checkable tr>td:first-child{
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
                            <span class="caption-subject font-dark sbold uppercase">POST CATEGORIES</span>
                        </div>
                        <a href="{{url('admin/post/categories/create')}}" type="button" class="btn btn-primary pull-right">Add new</a>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <table class="table table-striped table-bordered width_100" id="datatable">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="70%"> Name </th>
                                        <th width="30%"> Actions </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td>
                                            <div class="input-group input-xlarge margin-bottom-5">
                                                <input type="text" class="form-control input-inline input-sm" name="pcat_name" placeholder="Name" value="{{session('pcat_name_filter')}}">
                                                <input type="hidden" class="form-control form-filter input-sm" name="set_session" placeholder="set_session" value="1">
                                            </div>
                                             
                                        </td>
                                        <td>
                                            <form method="POST" id="search-form" class="form-inline" role="form">
                                                <div class="margin-bottom-5">
                                                    <button type="Submit" class="btn btn-sm green btn-outline margin-bottom">
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
                                                    <button class="btn btn-sm red btn-outline reset-filter">
                                                        <i class="fa fa-times"></i> Reset
                                                    </button>
                                                </div>
                                                
                                            </form>
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
        </div>



        {{-- <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Post Categories</span>
                        </div>
                        <a href="{{url('admin/post/categories/create')}}" type="button" class="btn btn-primary pull-right">Add new</a>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover table-checkable order-column"
                                       id="datatable">
                                    <thead>
                                    <tr>
                                        <th> Name</th>
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
        </div> --}}



    </div>
@endsection
@section('script')
    <script>
        let url = '{{ url('admin/post/categories/get') }}';
        let columns = [
            {data: 'pcat_name', name: 'pcat_name'},
            {data: 'action', name: 'action'}
        ];
        createDatable(url, columns, [0], "post_categories");

    </script>

@endsection