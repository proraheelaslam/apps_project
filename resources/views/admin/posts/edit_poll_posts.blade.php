@extends('admin.layout.app')
@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-12 ">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Edit Poll Post</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                @foreach($errors->all() as $message)
                                    <div>{{$message}}</div>
                                @endforeach
                            </div>
                        @endif

                        {{-- {{ dd($event)}} --}}
                        {{-- {{ dd($categories)}} --}}
                            <form method="POST" action="{{ route('admin.posts.update_poll_posts', ['post_id' => encodeId($post->_id)]) }}" class="form-horizontal">
                                {{ method_field("PUT") }}
                                {{ csrf_field() }}
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label col-xs-3">Title</label>
                                    <div class="col-md-7 col-xs-7">
                                        <textarea class="form-control" maxlength="500"  name="title" placeholder="Title" required="">{{ (old('title'))? old('title') : $post->upost_description }}</textarea>
                                    </div>
                                    <div class="col-md-2 col-xs-2">                                        
                                        <a href="javascript:;" class="btn btn-circle btn-icon-only green" id="add_new_option" title="Add New Option">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                                <span id="options_div">
                                    @foreach($post->post_questions as $k => $question)
                                        <div class="form-group" id="row_{{ ($loop->iteration - 1) }}">
                                            <label class="col-md-3 col-xs-3 control-label font-13">Option {{$loop->iteration}}</label>
                                            <div class="col-md-3 col-xs-7" id="poll_option">
                                                <input type="text" name="options[{{ 'existing-'.$question->_id }}][]" class="form-control" placeholder="Enter Option" value="{{$question->pquestion_question}}" maxlength="50">
                                            </div>                          
                                            <div class="col-md-4 col-xs-2">
                                                <a href="javascript:;" class="btn btn-circle btn-icon-only red" id="remove_row" title="Delete Option">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </div>
                                        </div>                                           
                                    @endforeach 
                                </span>
                                <div class="form-actions right">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <button type="button"  onclick="window.location='{{url('admin/poll_posts')}}'" class="btn default">Cancel</button>
                                            <button type="submit" class="btn blue">Save</button>
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <!-- END DASHBOARD STATS 1-->
        </div>
    </div>
@endsection
@section('script')
<script>
    var APP_URL = {!! json_encode(url('/')) !!};
    var selectedNeighborhoods = '';
    $(document).ready(function(){
        

        $(".select2-multiple").select2({
            placeholder: "Select Roles",
            width: null
        });
        $('.select2-multiple').val(selectedNeighborhoods).trigger("change");

        $(".form_meridian_datetime").datetimepicker({
            isRTL: App.isRTL(),
            // format: "dd MM yyyy - HH:ii P",
            format: "yyyy-M-dd  HH:ii P",
            showMeridian: true,
            autoclose: true,
            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
            todayBtn: true
        });


        $(document).on("click", "#remove_row",function(e){
            e.preventDefault();
            var row_id = $(this).parent().parent().prop("id");
            var totalLength = $("#options_div > div").length;
            if (totalLength == 1) {
                alert("Max 5 options are allowed to add.");
                return false;
            }
            $.confirm({
                title: 'Confirm!',
                content: 'Are you sure! You want remove this option.',
                type: 'red',
                typeAnimated: true,
                closeIcon: true,
                buttons: {
                    confirm: function () {
                        if (totalLength == 1) {
                            alert("All options are not allowed to delete.");
                            return false;
                        }
                        // console.log("#".row_id);
                        $("#"+row_id).remove();                        
                        $("#options_div > div > label").each(function(index){
                            $(this).text("Option " + (index + 1));
                        });
                    },
                    cancel: function () {},
                }
            });
            return false;

            
        });
        $(document).on("click", "#add_new_option",function(e){
            e.preventDefault();
            let totalLength = $("#options_div > div").length;
            // alert(totalLength);
            
            if (totalLength >= 5) {
                alert("Max 5 options are allowed to add.");
                return false;
            }
            let totalLengthCounter = (totalLength + 1);
            $new_option = `
                    <div class="form-group" id="row_${totalLength}">
                        <label class="col-md-3 control-label">Option ${totalLengthCounter}</label>
                        <div class="col-md-3">
                            <input type="text" name="options[]" class="form-control input-inline input-medium" placeholder="Enter Option" value="">
                        </div>                          
                        <div class="col-md-2">
                            <a href="javascript:;" class="btn btn-circle btn-icon-only red" id="remove_row">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div> 
            `;
            // console.log($new_option);
            $("#options_div").append($new_option);
        });

       
    });
    
</script>
@endsection