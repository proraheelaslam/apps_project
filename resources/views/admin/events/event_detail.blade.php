@extends('admin.layout.app')
<style>
  .cbp-popup-lightbox-img{
      max-height: 500px !important;
  }
  .cbp-popup-lightbox-iframe video{
    max-height: 500px !important;
  }
</style>
@section('content')
    <div class="page-content">

        @php
            // echo "<pre>";
            // dd($post);//["post_questions"][0]["pquestion_question"]
        // echo count($userAnswers) . "<br>";
            // dd($userAnswers);
        @endphp
        <!-- BEGIN PAGE HEADER-->
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <div class="blog-page blog-content-1">
            <div class="row">
                <div class="col-lg-12">
                    <div class="blog-post-lg bordered blog-container">
                        <h3 class="page-title"> Event Detail
                            <div class="pull-right">
                                <a href="{{url('admin/events')}}" type="button" class="btn btn-primary">Back</a>
                            </div>
                            <!-- <small>dashboard & statistics</small> -->
                        </h3>
                        <div class="blog-post-content">
                            <h2 class="blog-title blog-post-title">
                                <a href="javascript:;">{{$eventDetail->title}}</a>
                            </h2>
                            <p class="blog-post-desc"> {{$eventDetail->event_description}} </p>
                            <div class="blog-post-foot">
                                 <ul class="blog-post-tags">
                                     <li class="uppercase">
                                        <i class="icon-pointer font-blue"></i>
                                        <a href="javascript:;">{{$eventDetail->event_locations}}</a>
                                     </li>
                                 </ul>
                                <div class="blog-post-meta">
                                    <i class="icon-check font-blue"></i>
                                    <a href="javascript:;">{{ $eventDetail->event_total_joining }} people joining</a>
                                </div>
                                <div class="blog-post-meta">
                                    <i class="icon-user font-blue"></i>
                                    <a href="javascript:;">{{ $eventDetail->event_total_maybe }} people interested</a>
                                </div>
                                <div class="blog-post-meta">
                                    <i class="icon-clock font-blue"></i>
                                    <a href="javascript:;">{{ date("Y M d h:i A", strtotime($eventDetail->event_date)) }}</a>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-sm-6">

                        </div>
                        <div class="col-sm-6">

                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin: life time stats -->
                    <div class="portlet light portlet-fit portlet-datatable bordered">

                        <div class="portlet-body">
                            <div class="tabbable-line">
                                <ul class="nav nav-tabs nav-tabs-lg">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab" aria-expanded="true"> Media 
                                            <span class="badge badge-danger"> {{count($eventDetail->event_images)}}</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_2" data-toggle="tab" aria-expanded="false"> Participants
                                            <span class="badge badge-danger"> {{count($eventDetail->participants)}}</span>
                                        </a>
                                    </li>
                                    
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="table-container" style="">
                                            @if(count($eventDetail->event_images) > 0)
                                                <div class="portfolio-content portfolio-1">
                                                    <div id="js-grid-juicy-projects" class="cbp">
                                                        @foreach($eventDetail->event_images as $eventImg)
                                                            @if($eventImg->type === 'image')
                                                                <div class="cbp-item graphic "
                                                                     style="width: 342px;left: 0;top: 241px; max-height: 500px">
                                                                    <div class="cbp-caption">
                                                                        <div class="cbp-caption-defaultWrap">
                                                                            <img src="{{$eventImg->full_event_image}}"
                                                                                 alt="" style="height: 300px"></div>
                                                                        <div class="cbp-caption-activeWrap">
                                                                            <div class="cbp-l-caption-alignCenter">
                                                                                <div class="cbp-l-caption-body">
                                                                                    <a href="javascript:void(0)"
                                                                                       class="  btn red uppercase btn red uppercase"
                                                                                       onclick="removeEventMedia('{{$eventImg->_id}}')">Remove</a>
                                                                                    <a href="{{$eventImg->full_event_image}}"
                                                                                       class="cbp-lightbox cbp-l-caption-buttonRight btn red uppercase btn red uppercase"
                                                                                       data-title="">view
                                                                                        larger</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if($eventImg->type === 'video')
                                                                <div class="cbp-item graphic "
                                                                     style="width: 342px;left: 0;top: 241px;">
                                                                    <div class="cbp-caption">
                                                                        <div class="cbp-caption-defaultWrap">
                                                                            <img src="{{$eventImg->full_event_image}}"
                                                                                 alt="" style="height: 300px">
                                                                        </div>
                                                                            
                                                                        <div class="cbp-caption-activeWrap">
                                                                            <div class="cbp-l-caption-alignCenter">
                                                                                <div class="cbp-l-caption-body">
                                                                                    <a href="javascript:void(0)"
                                                                                       class="  btn red uppercase btn red uppercase"
                                                                                       onclick="removeEventMedia('{{$eventImg->_id}}')">Remove</a>
                                                                                    <a href="{{$eventImg->full_event_video}}"
                                                                                       class="cbp-lightbox cbp-l-caption-buttonRight btn red uppercase btn red uppercase"
                                                                                       data-title="">view
                                                                                        larger</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <p>No Media Found!</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_2">
                                        <div class="table-container" style="">
                                            <div class="portlet grey-cascade box">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-cogs"></i>Participants
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th> Name </th>                                                          
                                                                <th> Participation Type </th>                             
                                                                <th> Adults </th>                                                          
                                                                <th> Children </th>                                                          
                                                                <th> Actions </th>                                                            
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @if(count($eventDetail->participants) > 0)
                                                                @foreach($eventDetail->participants as $participants)
                                                                    <tr>
                                                                        <td>{{$participants->users->full_name}}</td>
                                                                        <td>{{ ucfirst($participants->participation_type)}}</td>
                                                                        <td>{{$participants->epart_adults}}</td>
                                                                        <td>{{$participants->epart_children}}</td>
                                                                        <td>
                                                                            <a title="Delete Participant"
                                                                               onclick="deleteParticipant('{{$participants->event_id}}', '{{$participants->user_id}}')"
                                                                               href="javascript:void(0)"
                                                                               class="btn-delete btn btn-sm btn-circle btn-default btn-editable">
                                                                                <i class="fa fa-trash"></i> Delete
                                                                            </a>
                                                                        </td>

                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td colspan="3"><p class="text-center">No participants
                                                                            found</p></td>
                                                                </tr>
                                                            @endif

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- tab-2 -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End: life time stats -->
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
</style>
@section('script')


    @if(Session::has('media_tab'))
        <script>$('a[href="#tab_1"]').trigger('click');</script> {{Session::forget('media_tab')}} @endif
    @if(Session::has('participant_tab'))
        <script>$('a[href="#tab_2"]').trigger('click');</script> {{Session::forget('participant_tab')}} @endif


    <script>

        function removeEventMedia(media_id) {
            $.confirm({
                title: 'Delete Media!',
                content: 'Are you sure you want to delete media!',
                draggable: false,
                type: 'red',
                typeAnimated: true,
                closeIcon: true,
                buttons: {
                    confirm: function () {
                        $.LoadingOverlay("show");
                        $.ajax({
                            url: "{{url('admin/event/media/delete')}}",
                            type: 'post',
                            data: {media_id: media_id},
                            success: function (data) {
                                if (data.status == true) {
                                    $.LoadingOverlay("hide");
                                    success_message(data.message);
                                    setTimeout(function () {
                                        location.reload();
                                    }, 100)
                                }
                            },
                        });
                    },
                    cancel: function () {
                    }
                }
            });
        }

        function deleteParticipant(event_id, user_id) {
            $.confirm({
                title: 'Delete Participant!',
                content: 'Are you sure you want to delete participant!',
                draggable: false,
                type: 'red',
                typeAnimated: true,
                closeIcon: true,
                buttons: {
                    confirm: function () {
                        $.LoadingOverlay("show");
                        $.ajax({
                            url: "{{url('admin/event/participants/delete')}}",
                            type: 'post',
                            data: {event_id: event_id, user_id: user_id},
                            success: function (data) {
                                if (data.status == true) {
                                    $.LoadingOverlay("hide");
                                    success_message(data.message);
                                    setTimeout(function () {
                                        location.reload();
                                    }, 100)
                                }
                            },
                        });
                    },
                    cancel: function () {
                    }
                }
            });
        }
    </script>
@endsection