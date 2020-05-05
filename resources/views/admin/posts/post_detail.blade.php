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
        <!-- BEGIN PAGE HEADER-->
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <div class="blog-page blog-content-1">
            <div class="row">
                <div class="col-lg-12">
                    <div class="blog-post-lg blog-container">
                        <h3 class="page-title">{{ @ucfirst($post->post_type)}} Post Detail
                            <div class="pull-right">
                                <a href="{{url('admin').'/'.$post->post_type.'_posts'}}" type="button" class="btn btn-primary">Back</a>
                            </div>
                            <!-- <small>dashboard & statistics</small> -->
                        </h3>
                        <div class="blog-post-content">
                            <h2 class="blog-title blog-post-title">
                                <a href="javascript:;" class="no-pointer">{{$post->upost_title}}</a>
                            </h2>
                            <p class="blog-post-desc"> {{$post->upost_description}} </p>
                            @if($post->post_type == "poll")
                                <div class="row">
                                    <div class="col-lg-6">

                                        <div class="portlet light ">
                                            <div class="portlet-body todo-project-list-content" style="height: auto;">
                                                <div class="todo-project-list">
                                                    <ul class="nav nav-stacked">
                                                        @foreach($post["post_questions"] as $options)
                                                            <li>
                                                                <a href="javascript:;" class="no-pointer">
                                                                    @php
                                                                        switch (true) {
                                                                            case $options->percentage >= 100:
                                                                                $classExtra = "badge-success";
                                                                            break;
                                                                            case $options->percentage >= 50 && $options->percentage < 100:
                                                                                $classExtra = "badge-info";
                                                                            break;
                                                                            case $options->percentage == 0:
                                                                                $classExtra = "badge-danger";
                                                                            break;
                                                                            
                                                                            default:
                                                                                $classExtra = "badge-info";
                                                                            break;
                                                                        }
                                                                    @endphp
                                                                    <span class="badge @php echo $classExtra; @endphp"> 
                                                                        {{$options->percentage . "%"}} 
                                                                    </span> 
                                                                    {{$options->pquestion_question}} 
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="blog-post-foot">
                                 <ul class="blog-post-tags">
                                     <li class="uppercase">
                                         <a href="javascript:;"  >{{$post->neighborhood->neighborhood_name}}</a>
                                     </li>
                                 </ul>
                                <div class="blog-post-meta">
                                    <i class="icon-calendar font-blue"></i>
                                    <a href="javascript:"  >{{$post->post_date}}</a>
                                </div>
                                <div class="blog-post-meta">
                                    <i class="icon-bubble font-blue"></i>
                                    <a href="javascript:" >{{count($post->replies)}} Comments</a>
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
                        {{-- {{$post->post_type}} --}}
                        <div class="portlet-body">
                            <div class="tabbable-line">
                                <ul class="nav nav-tabs nav-tabs-lg">
                                    @if($post->post_type == "message")
                                        <li class="active">
                                            <a href="#tab_1" data-toggle="tab" aria-expanded="true"> Images 
                                                <span class="badge badge-danger"> {{count($post->post_images)}}</span>
                                                
                                            </a>
                                        </li>
                                    @endif
                                    @php
                                        $isActive = '';
                                        if($post->post_type != "message"){
                                            $isActive = "active";
                                        }
                                    @endphp
                                    
                                    <li class="@php echo $isActive; @endphp">
                                        <a href="#tab_3" data-toggle="tab" aria-expanded="true"> Comments 
                                            <span class="badge badge-danger"> {{count($post->replies)}}</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_4" data-toggle="tab" aria-expanded="false"> Thanks
                                            <span class="badge badge-danger"> {{count($post->thanks)}}</span>
                                        </a>
                                    </li>
                                    @if($post->post_type == "poll")
                                        <li class="">
                                            <a href="#tab_5" data-toggle="tab" aria-expanded="false"> Users
                                                <span class="badge badge-danger"> {{count($userAnswers)}}</span>
                                            </a>
                                        </li>
                                    @endif
                                    
                                </ul>
                                <div class="tab-content">
                                    
                                        <div class="tab-pane active" id="tab_1">
                                            
                                            <div class="table-container" style="">
                                                @if($post->post_type == "message")
                                                    @if(count($post->post_images) > 0)
                                                        <div class="portfolio-content portfolio-1">
                                                            <div id="js-grid-juicy-projects" class="cbp">
                                                                @foreach($post->post_images as $postImg)
                                                                    @if($postImg->type == 'image')
                                                                        <div class="cbp-item graphic "
                                                                             style="width: 342px;left: 0;top: 241px; max-height: 500px">
                                                                            <div class="cbp-caption">
                                                                                <div class="cbp-caption-defaultWrap">
                                                                                    <img src="{{$postImg->full_post_image}}"
                                                                                         alt="" style="height: 300px"></div>
                                                                                <div class="cbp-caption-activeWrap">
                                                                                    <div class="cbp-l-caption-alignCenter">
                                                                                        <div class="cbp-l-caption-body">
                                                                                            <a href="javascript:void(0)"
                                                                                               class="  btn red uppercase btn red uppercase"
                                                                                               onclick="removePostfile('{{$postImg->_id}}')">Remove</a>
                                                                                            <a href="{{$postImg->full_post_image}}"
                                                                                               class="cbp-lightbox cbp-l-caption-buttonRight btn red uppercase btn red uppercase"
                                                                                               data-title="">view
                                                                                                larger</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if($postImg->type === 'video')
                                                                        <div class="cbp-item graphic "
                                                                             style="width: 342px;left: 0;top: 241px;; max-height: 500px">
                                                                            <div class="cbp-caption">
                                                                                <div class="cbp-caption-defaultWrap">
                                                                                    <img src="{{$postImg->full_post_image}}"
                                                                                         alt="" style="height: 300px">
                                                                                </div>
                                                                                    
                                                                                <div class="cbp-caption-activeWrap">
                                                                                    <div class="cbp-l-caption-alignCenter">
                                                                                        <div class="cbp-l-caption-body">
                                                                                            <a href="javascript:void(0)"
                                                                                               class="  btn red uppercase btn red uppercase"
                                                                                               onclick="removePostfile('{{$postImg->_id}}')">Remove</a>
                                                                                            <a href="{{$postImg->full_post_video}}"
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
                                                        <p>No image Found!</p>
                                                    @endif
                                                @endif
                                            </div>
                                            
                                        </div>
                                    
                                    <div class="tab-pane" id="tab_2">
                                        <div class="table-container" style="">

                                        </div>
                                    </div>
                                    @php
                                        $isActive = '';
                                        if($post->post_type != "message"){
                                            $isActive = "active";
                                        }
                                    @endphp
                                    <div class="tab-pane @php echo $isActive; @endphp" id="tab_3">
                                        <div class="table-container" style="">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-6">
                                                    <div class="portlet light bordered">
                                                        <div class="portlet-title">
                                                            <div class="caption caption-md">
                                                                <i class="icon-bar-chart font-dark hide"></i>
                                                                <span class="caption-subject font-dark bold uppercase">Comments</span>
                                                            </div>

                                                        </div>
                                                        <div class="portlet-body">
                                                            <div>
                                                                <div class="general-item-list">
                                                                    @if(count($post->replies) > 0)
                                                                        @foreach($post->replies as $replies)
                                                                            <div class="item">
                                                                                <div class="item-head margin-bottom-10">
                                                                                    <div class="item-details">
                                                                                        <img class="item-pic rounded "
                                                                                             style="height: 25px;width: 25px;"
                                                                                             src="{{$replies->user->full_image}}">
                                                                                        <a href=""
                                                                                           class="item-name primary-link">{{$replies->user->full_name}}</a>
                                                                                        <span class="item-label">{{$replies->comment_time}}</span>
                                                                                    </div>

                                                                                    {{-- <span class="item-status"
                                                                                          id="delete-comment"
                                                                                          onclick="deleteComment('{{$replies->_id}}')">
                                                                                            <i title="delete comment"
                                                                                               class="fa fa-trash-o trash-comment"></i>
                                                                                        </span> --}}

                                                                                    <span class="item-status-custom btn btn-sm btn-circle btn-default btn-editable "
                                                                                      id="delete-comment"
                                                                                      onclick="deleteComment('{{$replies->_id}}')">
                                                                                        <i title="delete comment"
                                                                                           class="fa fa-trash"></i>
                                                                                           Delete
                                                                                    </span>
                                                                                    <span class="item-status-custom">
                                                                                        <a href="javascript:void(0)"  data-toggle="modal"
                                                                                           onclick="editComment('{{$replies->_id}}')"
                                                                                           class=" btn btn-sm btn-circle btn-default btn-editable ">
                                                                                        <i class="fa fa-pencil"></i> Edit</a>
                                                                                    </span>

                                                                                </div>
                                                                                <div class="item-body  break-words">
                                                                                    <span>{{$replies->preply_comment}}</span>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        <p>No record found!</p>
                                                                    @endif


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_4">
                                        <div class="table-container" style="">
                                            <div class="portlet grey-cascade box">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-users"></i>Users
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th> Name</th>
                                                                <th> Email</th>
                                                                <th> Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @if(count($post->thanks) > 0)
                                                                @foreach($post->thanks as $thanks)
                                                                    <tr>
                                                                        <td>{{$thanks->user->full_name}}</td>
                                                                        <td>{{$thanks->user->email}}</td>
                                                                        <td>
                                                                            <a title="Delete user"
                                                                               onclick="deleteThankUser('{{$post->_id}}','{{$thanks->user->_id}}')"
                                                                               href="javascript:void(0)"
                                                                               class="btn-delete btn btn-sm btn-circle btn-default btn-editable">
                                                                                <i class="fa fa-trash"></i> Delete</a>
                                                                        </td>

                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td colspan="3"><p class="text-center">No record
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
                                    @if($post->post_type == "poll")
                                        <div class="tab-pane" id="tab_5">
                                            <div class="table-container" style="">
                                                <div class="portlet grey-cascade box">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="fa fa-users"></i>Users
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover table-bordered table-striped">
                                                                <thead>
                                                                <tr>
                                                                    <th> Name </th>
                                                                    <th> Answer </th>                                                            
                                                                    <th> Actions </th>                                                            
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @if(count($userAnswers) > 0)
                                                                    @foreach($userAnswers as $userAnswer)
                                                                        <tr>
                                                                            <td>{{$userAnswer->users->full_name}}</td>
                                                                            <td>{{$userAnswer->questions->pquestion_question}}</td>
                                                                            <td>
                                                                                <a title="Delete Answer"
                                                                                   onclick="deleteUserAnswer('{{$userAnswer->_id}}')"
                                                                                   href="javascript:void(0)"
                                                                                   class="btn-delete btn btn-sm btn-circle btn-default btn-editable">
                                                                                    <i class="fa fa-trash"></i> Delete</a>
                                                                                </td>

                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="3"><p class="text-center">No record
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
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End: life time stats -->
                </div>
            </div>
        </div>
    </div>
    {{--comment modal--}}
    <div id="comment_modal" class="modal fade" tabindex="-1" data-width="400">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Edit Comment</h4>
                </div>
                {!! Form::open(['url'=>'admin/post/comment/update','method' => 'post','id'=> 'commentForm','data-toggle'=>'validator','role'=>'form']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4></h4>
                            {{-- <textarea  class="form-control"></textarea> --}}
                            <textarea class="form-control" maxlength="500"  id="comment_popup" name="comment" placeholder="Enter Comment" required=""></textarea>
                            <input type="hidden" name="comment_id" id="comment_id" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
                    <button type="button" class="btn btn-success mt-ladda-btn ladda-button " onclick="saveComment()" data-style="zoom-out">
                        <span class="ladda-label">Save</span>
                    </button>
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
@endsection

@section('script')
    @if(Session::has('comment_tab'))
        <script>$('a[href="#tab_3"]').trigger('click');</script> {{Session::remove('comment_tab')}} @endif
    @if(Session::has('thanks_tab'))
        <script>$('a[href="#tab_4"]').trigger('click');</script> {{Session::forget('thanks_tab')}} @endif

    @if(Session::has('userAnswer_tab'))
        <script>$('a[href="#tab_5"]').trigger('click');</script> {{Session::forget('userAnswer_tab')}} @endif
    <script>
        


        function removePostfile(post_image_id) {
            $.confirm({
                title: 'Delete Image!',
                content: 'Are you sure you want to delete image!',
                draggable: false,
                type: 'red',
                typeAnimated: true,
                closeIcon: true,
                buttons: {
                    confirm: function () {
                        $.LoadingOverlay("show");
                        $.ajax({
                            url: "{{url('admin/post/image/delete')}}",
                            type: 'post',
                            data: {post_image_id: post_image_id},
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

        function editComment(comment_id) {
            $('#comment_modal').modal('show', {backdrop: 'static'});
            
            //offsetHeight
            $.ajax({
                url: "{{url('admin/post/comment/get')}}",
                type: 'post',
                data: {comment_id: comment_id},
                success: function (data) {
                    console.log(data);
                    if (data.status == true) {
                        $("#comment_popup").html(data.message);
                        $("#comment_id").val(data.id);
                        $("textarea#comment_popup").height( $("textarea#comment_popup")[0].scrollHeight +'px' );
                    }
                },
            });
        }
        function saveComment() {
            let comment = $("#comment_popup").val();
            if (comment.length == 0 || comment == " ") {
                alert("Comment is required");
                return false;
            }
            $('#commentForm').submit();
        }
        function deleteThankUser(post_id, user_id) {
            $.confirm({
                title: 'Delete Thank User!',
                content: 'Are you sure you want to delete Thank user!',
                draggable: false,
                type: 'red',
                typeAnimated: true,
                closeIcon: true,
                buttons: {
                    confirm: function () {
                        $.LoadingOverlay("show");
                        $.ajax({
                            url: "{{url('admin/post/thank/user/delete')}}",
                            type: 'post',
                            data: {user_id: user_id, post_id: post_id},
                            success: function (data) {
                                console.log(data);
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

        function deleteComment(comment_id) {
            $.confirm({
                title: 'Delete Comment!',
                content: 'Are you sure you want to delete comment!',
                draggable: false,
                type: 'red',
                typeAnimated: true,
                closeIcon: true,
                buttons: {
                    confirm: function () {
                        $.LoadingOverlay("show");
                        $.ajax({
                            url: "{{url('admin/post/comment/delete')}}",
                            type: 'post',
                            data: {comment_id: comment_id},
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

        function removeAddressImage(user_id) {
            $.confirm({
                title: 'Delete address image!',
                content: 'Are you sure you want to delete address image!',
                draggable: false,
                type: 'red',
                typeAnimated: true,
                closeIcon: true,
                buttons: {
                    confirm: function () {
                        $.LoadingOverlay("show");
                        $.ajax({
                            url: "{{url('admin/user/delete_address_image')}}",
                            type: 'post',
                            data: {user_id: user_id},
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

        function deleteUserAnswer(answer_id) {
            $.confirm({
                title: 'Delete Answer!',
                content: 'Are you sure you want to delete answer!',
                draggable: false,
                type: 'red',
                typeAnimated: true,
                closeIcon: true,
                buttons: {
                    confirm: function () {
                        $.LoadingOverlay("show");
                        $.ajax({
                            url: "{{url('admin/post/user_answer/delete')}}",
                            type: 'post',
                            data: {answer_id: answer_id},
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