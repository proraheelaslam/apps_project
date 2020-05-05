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
                            <span class="caption-subject font-dark sbold uppercase">Manage Emails</span>
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
                                        <input type="text" class="form-control input-inline input-small width_100" name="email_name" placeholder="Name" value="{{session('email_name_filter')}}">
                                        <input type="hidden" class="form-control form-filter input-sm" name="set_session" placeholder="set_session" value="1">
                                    </div>

                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="email_from" placeholder="Email" value="{{session('email_from_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="email_subject" placeholder="Subject" value="{{session('email_subject_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <form method="POST" id="search-form" style="margin: 0px;" role="form">
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
                                        <th width="25%"> Name</th>
                                        <th width="16%"> From </th>
                                        <th width="16%"> Subject</th>
                                        <th width="5%"> Action</th>
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
                            <span class="caption-subject font-dark sbold uppercase">Email Templates</span>
                        </div>
                    </div>
                    <div class="portlet-title"><!-- filtersBar-->
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Filter Results</span>
                        </div>
                        <table class="table table-light responsive_filterHead">
                            <tr role="row" class="filter">
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="email_name" placeholder="Name" value="{{session('email_name_filter')}}">
                                        <input type="hidden" class="form-control form-filter input-sm" name="set_session" placeholder="set_session" value="1">
                                    </div>

                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="email_from" placeholder="Email" value="{{session('email_from_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="email_subject" placeholder="Subject" value="{{session('email_subject_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <form method="POST" id="search-form" style="margin: 0px;" role="form">
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
                        <div class="table-responsive">
                            <table class="table table-bordered table-checkable width_100" id="datatable">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="25%"> Name</th>
                                        <th width="16%"> From </th>
                                        <th width="16%"> Subject</th>
                                        <th width="5%"> Action</th>
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
@section('script')
    <script>
        let url = '{{ url('admin/emails/get') }}';
        let columns = [
            {data: 'name', name: 'name'},
            {data: 'from', name: 'from', },
            {data: 'subject', name: 'subject', },
            {data: 'action', name: 'action'}
        ];
        createDatable(url, columns, [0], "email_templates");

    </script>

@endsection