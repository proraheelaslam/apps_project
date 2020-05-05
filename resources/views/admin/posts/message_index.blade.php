
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
                            <span class="caption-subject font-dark sbold uppercase">Message Posts</span>
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
                                        <input type="text" class="form-control input-inline input-small width_100" name="message_upost_title" placeholder="Subject" value="{{session('message_upost_title_filter')}}">
                                        <input type="hidden" class="form-control form-filter input-sm" name="set_session" placeholder="set_session" value="1">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <input type="text" class="form-control input-inline input-small width_100" name="message_user" placeholder="User" value="{{session('message_user_filter')}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group width_100">
                                        <select name="message_neighborhood_name" class="form-control form-filter input-small width_100" id="message-neighborhood">
                                            <option value="" {{ session("message_neighborhood_filter") === "" ? "selected" : ""}}>Select Neighborhood</option>
                                            @foreach($neighborhoods as $k => $neighborhood)
                                                <option value="{{$neighborhood->_id}}" {{ session("message_neighborhood_name_filter") == $neighborhood->_id ? "selected" : ""}}>{{$neighborhood->neighborhood_name}}</option>
                                            @endforeach
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
                                        
                                        <th width="25%"> Subject</th>
                                        <th width="25%"> User</th>
                                        <th width="25%"> Neighborhood</th>
                                        <th width="25%"> Actions</th>
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
        let url = '{{ url('admin/posts/get') }}';
        let columns = [
            {data: 'upost_title', name: 'upost_title'},
            {data: 'post_user', name: 'post_user'},
            {data: 'neighborhood_name', name: 'neighborhood_name'},
            {data: 'action', name: 'action'}
        ];
        createDatable(url, columns, [0], "message_posts");

    </script>

@endsection