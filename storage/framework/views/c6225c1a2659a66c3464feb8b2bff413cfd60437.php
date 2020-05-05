<?php $__env->startSection('content'); ?>
    <div class="page-content" style="min-height:1240px">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN THEME PANEL -->

        <!-- END THEME PANEL -->
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb" style="display:none;">
                <li>
                    <a href="">Home</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>User</span>
                </li>
            </ul>
            <div class="page-toolbar" style="display: none">
                <div class="btn-group pull-right">
                    <button type="button" class="btn green btn-sm btn-outline dropdown-toggle" data-toggle="dropdown"> Actions
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>
                            <a href="#">
                                <i class="icon-bell"></i> Action</a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="icon-shield"></i> Another action</a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="icon-user"></i> Something else here</a>
                        </li>
                        <li class="divider"> </li>
                        <li>
                            <a href="#">
                                <i class="icon-bag"></i> Separated link</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title"> <?php echo e($user->full_name); ?>

        </h3>
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PROFILE SIDEBAR -->
                <div class="profile-sidebar">
                    <!-- PORTLET MAIN -->
                    <div class="portlet light profile-sidebar-portlet ">
                        <!-- SIDEBAR USERPIC -->
                        <div class="profile-userpic">
                            <strong></strong>
                            <?php if(\File::exists(public_path('upload/users/'.$user->user_image) )): ?>
                                <strong class="profileImg remove_profile" onclick="removeProfile('<?php echo e($user->_id); ?>')"><img src="<?php echo e($user->full_image); ?>" class="img-responsive" alt=""><i class="fa fa-trash fa-2x" aria-hidden="true"></i></strong>
                                
                            <?php else: ?>
                                <img src="<?php echo e($user->full_image); ?>" class="img-responsive" alt="">
                            <?php endif; ?>
                            
                        </div>
                        <!-- END SIDEBAR USERPIC -->
                        <!-- SIDEBAR USER TITLE -->
                        <div class="profile-usertitle">
                            <div class="profile-usertitle-name"> <?php echo e($user->full_name); ?> </div>
                            <div class="profile-usertitle-job">
                                <?php if($user->user_is_address_verified == 0): ?>
                                    <span class="label label-info btn-circle label-sm label-sm-custom"> Pending Address </span>
                                <?php elseif($user->user_is_address_verified == 1): ?>
                                    <span class="label label-primary btn-circle label-sm label-sm-custom"> Waiting for Approval</span>
                                <?php elseif($user->user_is_address_verified == 2): ?>
                                    <span class="label label-success btn-circle label-sm label-sm-custom"> Verified Address </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- END SIDEBAR USER TITLE -->
                        <!-- SIDEBAR BUTTONS -->
                        <div class="profile-userbuttons">
                            <button type="button" class="btn btn-circle green btn-sm" data-toggle="modal" href="#messageBox">Message</button>
                            <?php if($user->status->ustatus_name == 'pending'): ?>
                                <button type="button" id="approve_disapprove_status"
                                        onclick="approveDisapproveStatus(1)" value="0"
                                        class="btn btn-danger btn-circle pointer btn-sm">Not Approved
                                </button>
                            <?php elseif($user->status->ustatus_name == 'approved'): ?>
                                <button type="button" onclick="approveDisapproveStatus(0)"
                                        id="approve_disapprove_status" value="1"
                                        class="btn btn-success btn-circle pointer btn-sm">Approved
                                </button>
                            <?php elseif($user->status->ustatus_name == 'deactivate'): ?>
                                <button type="button" onclick="approveDisapproveStatus(1)"
                                        id="approve_disapprove_status" value="2"
                                        class="btn btn-danger btn-circle pointer btn-sm">Deactivated
                                </button>
                            <?php endif; ?>
                        </div>
                        <!-- END SIDEBAR BUTTONS -->
                        <!-- SIDEBAR MENU -->
                        <div class="profile-usermenu">
                            <ul class="nav">
                                <li style="display: none">
                                    <a href="page_user_profile_1.html">
                                        <i class="icon-home"></i> Overview </a>
                                </li>
                                <li class="active" style="display: none">
                                    <a href="page_user_profile_1_account.html">
                                        <i class="icon-settings"></i> Account Settings </a>
                                </li>
                                <li style="display: none">
                                    <a href="page_user_profile_1_help.html">
                                        <i class="icon-info"></i> Help </a>
                                </li>
                            </ul>
                        </div>
                        <!-- END MENU -->
                    </div>
                    <!-- END PORTLET MAIN -->
                    <!-- PORTLET MAIN -->
                    <div class="portlet light " style="display: none">
                        <!-- STAT -->
                        <div class="row list-separated profile-stat" >
                            <div class="col-md-4 col-sm-4 col-xs-6">
                                <div class="uppercase profile-stat-title"> 37 </div>
                                <div class="uppercase profile-stat-text"> Projects </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-6">
                                <div class="uppercase profile-stat-title"> 51 </div>
                                <div class="uppercase profile-stat-text"> Tasks </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-6">
                                <div class="uppercase profile-stat-title"> 61 </div>
                                <div class="uppercase profile-stat-text"> Uploads </div>
                            </div>
                        </div>
                        <!-- END STAT -->
                        <div style="display:none;">
                            <h4 class="profile-desc-title">About Marcus Doe</h4>
                            <span class="profile-desc-text"> Lorem ipsum dolor sit amet diam nonummy nibh dolore. </span>
                            <div class="margin-top-20 profile-desc-link">
                                <i class="fa fa-globe"></i>
                                <a href="http://www.keenthemes.com">www.keenthemes.com</a>
                            </div>
                            <div class="margin-top-20 profile-desc-link">
                                <i class="fa fa-twitter"></i>
                                <a href="http://www.twitter.com/keenthemes/">@keenthemes</a>
                            </div>
                            <div class="margin-top-20 profile-desc-link">
                                <i class="fa fa-facebook"></i>
                                <a href="http://www.facebook.com/keenthemes/">keenthemes</a>
                            </div>
                        </div>
                    </div>
                    <!-- END PORTLET MAIN -->
                </div>
                <!-- END BEGIN PROFILE SIDEBAR -->
                <!-- BEGIN PROFILE CONTENT -->
                <div class="profile-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light ">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#tab_1_1" data-toggle="tab">Personal Information</a>
                                        </li>
                                       
                                        <li>
                                            <a href="#tab_1_3" data-toggle="tab">Address Status</a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_4" data-toggle="tab">Location</a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_5" data-toggle="tab">Chat</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div class="tab-pane active" id="tab_1_1">
                                            <?php if($errors->any()): ?>
                                                <div class="alert alert-danger alert-dismissable">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                                    </button>
                                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div><?php echo e($message); ?></div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php echo Form::open(['url'=>'admin/user/profile/update','method' => 'post','class'=> '','data-toggle'=>'validator','role'=>'form']); ?>

                                                <input type="hidden" name="user_id" value="<?php echo e($user->_id); ?>">
                                                <div class="form-group">
                                                    <label class="control-label">First Name</label>
                                                    <?php echo Form::text('first_name',$user->user_fname,['class'=>'form-control','placeholder'=>'First Name','required'=>'required']); ?>

                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Last Name</label>
                                                    <?php echo Form::text('last_name',$user->user_lname,['class'=>'form-control','placeholder'=>'Last name','required'=>'required']); ?>


                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Mobile Number</label>
                                                    <?php echo Form::text('user_phone',$user->user_phone,['class'=>'form-control','placeholder'=>'Phone']); ?>

                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Email</label>
                                                    <?php echo Form::text('email',$user->user_email,['class'=>'form-control','placeholder'=>'Email','required'=>'required']); ?>

                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Gender</label>
                                                    <select name="gender" class="form-control">
                                                        <option value="Male" <?php echo e($user->gender->gender_name =='Male' ? 'selected' : ''); ?>>Male</option>
                                                        <option value="Female" <?php echo e($user->gender->gender_name =='Female' ? 'selected' : ''); ?>>Female</option>
                                                        <option value="Other" <?php echo e($user->gender->gender_name =='Other' ? 'selected' : ''); ?>>Other</option>
                                                    </select>
                                                </div>
                                                <div class="margiv-top-10">
                                                    <input type="submit" class="btn green "  value="Save Changes">
                                                    <a href="<?php echo e(url('admin/users')); ?>" class="btn default"> Cancel </a>
                                                </div>
                                            <?php echo Form::close(); ?>

                                        </div>
                                        <!-- END PERSONAL INFO TAB -->
                                        <!-- CHANGE AVATAR TAB -->
                                        <div class="tab-pane" id="tab_1_2">
                                            <p> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                                                eiusmod. </p>
                                            <form action="#" role="form">
                                                <div class="form-group">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""> </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                        <div>
                                                                        <span class="btn default btn-file">
                                                                            <span class="fileinput-new"> Select image </span>
                                                                            <span class="fileinput-exists"> Change </span>
                                                                            <input type="file" name="..."> </span>
                                                            <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix margin-top-10">
                                                        <span class="label label-danger">NOTE! </span>
                                                        <span>Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                                    </div>
                                                </div>
                                                <div class="margin-top-10">
                                                    <a href="javascript:;" class="btn green"> Submit </a>
                                                    <a href="javascript:;" class="btn default"> Cancel </a>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- END CHANGE AVATAR TAB -->
                                        <!-- CHANGE PASSWORD TAB -->
                                        <div class="tab-pane" id="tab_1_3">
                                            <?php if(!empty($user->user_address_verify_code) || ($user->user_address_verify_code !=0)): ?>
                                                <table class="table table-light table-hover">
                                                    <tbody>
                                                    <tr>
                                                        <td> Address Pin Code
                                                            <span class="pull-right"><?php echo e($user->user_address_verify_code); ?></span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            <?php endif; ?>
                                                <div class="form-group">
                                                    <?php if($user->user_is_address_verified == 1): ?>
                                                        <?php if($user->user_address_type == 'document'): ?>
                                                            <img width="200px" height="200px"
                                                                 src="<?php echo e($user->user_address_image); ?>">
                                                            <?php if(\File::exists(public_path('upload/addresses/'.$user->user_address_document) )): ?>
                                                                <button style="margin-top: 164px;"
                                                                        onclick="removeAddressImage('<?php echo e($user->_id); ?>')" type="button"
                                                                        class="btn btn-circle red btn-sm">Remove image
                                                                </button>
                                                            <?php endif; ?>
                                                            <div class="clearfix"></div><br>
                                                            <button onclick="verifyAddress('<?php echo e($user->_id); ?>')" type="button"
                                                                    class="btn green">Verify Address
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>

                                        </div>
                                        <!-- END CHANGE PASSWORD TAB -->
                                        <!-- PRIVACY SETTINGS TAB -->
                                        <div class="tab-pane" id="tab_1_4">
                                            <?php if($user->user_address_latitude == 0 && $user->user_address_longitude == 0): ?>
                                                <p class="text-center">User Location does not exist</p>
                                            <?php endif; ?>
                                            <div id="user_address" style="width:100%;height:300px;"></div>
                                        </div>
                                        <!-- END PRIVACY SETTINGS TAB -->
                                        <div class="tab-pane" id="tab_1_5">
                                            <div class="portlet-body">
                                                <div class="table-container">
                                                    <table class="table table-bordered  width_100" id="datatable">
                                                        <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="70%"> Chat From User</th>
                                                            <th width="70%"> Chat To User </th>
                                                            <th width="30%"> Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PROFILE CONTENT -->
            </div>
        </div>
    </div>
<div class="modal fade" id="messageBox" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Send Message</h4>
            </div>
            <div class="modal-body">
                <textarea  id="user_message" class="form-control" style="min-height: 20px"></textarea>
                <button type="button" class="btn dark btn-outline pull-right btnModal" data-dismiss="modal">Close
                </button>
                <button type="button" onclick="sendMessage('<?php echo e($user->_id); ?>')" class="btn green pull-right btnModal">
                    Send Message
                </button>
                
            </div>
            <div class="modal-footer"></div>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>
<style>
    .modal-footer {
        border-top: 0px solid #e5e5e5 !important;
    }

    .btnModal {
        margin-right: 12px;
        margin-top: 10px;
    }
    .remove_profile:hover {
        cursor: pointer;
    }
</style>
<?php $__env->startSection('script'); ?>
<?php if(Session::has('verified')): ?>
    <script>$('a[href="#tab_1_3"]').trigger('click');</script>
    <?php echo e(Session::forget('verified')); ?>

<?php endif; ?>

<script>
    let session = "<?php echo e(Session::get('verified')); ?>";
    var user_id = "<?php echo e($user->_id); ?>";

    let url = '<?php echo e(url('admin/chat/get/user/'.$user->_id)); ?>';
    let columns = [
        {data: 'chat_from', name: 'chat_from'},
        {data: 'chat_to', name: 'chat_to'},
        {data: 'action', name: 'action'},
    ];
    createDatable(url, columns, [0], "chat_thread");


    function showLcoation(){
        let user_address_latitude = "<?php echo e($user->user_address_latitude); ?>";
        let user_address_longitude = "<?php echo e($user->user_address_longitude); ?>";
        if (user_address_latitude != 0 && user_address_longitude != 0) {
            let userLatlong = new google.maps.LatLng(user_address_latitude, user_address_longitude);
            let userMapOptions = {
                zoom: 18,
                center: userLatlong,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(document.getElementById("user_address"), userMapOptions);
            let marker = new google.maps.Marker({
                position: userLatlong,
                map: map,
                title: "<?php echo e($user->user_address); ?>"
            });
        }
    }
    $(document).ready(function() {
        $('a[href="#tab_1_4"]').on('click', function() {
                showLcoation();
        });
    });
    function sendMessage(user_id) {
        let message = $("#user_message").val();
        if (message.length == 0) {
            $("#user_message").css({"border": "1px solid red"});
            return false;
        } else {
            $("#user_message").css({"border": "0px solid red"});
        }
        $.LoadingOverlay("show");
        $.ajax({
            url: "<?php echo e(url('admin/user/send_message')); ?>",
            type: 'post',
            data: {user_id: user_id, message: message},
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

    }
    function removeProfile(user_id) {
        $.confirm({
            title: 'Delete Profile!',
            content: 'Are you sure you want to delete profile!',
            draggable: false,
            type: 'red',
            typeAnimated: true,
            closeIcon: true,
            buttons: {
                confirm: function () {
                    $.LoadingOverlay("show");
                    $.ajax({
                        url: "<?php echo e(url('admin/user/delete_profile')); ?>",
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
    function removeAddressImage(user_id) {
        $.confirm({
            title: 'Delete address image!',
            content: 'Are you sure you want to address image!',
            draggable: false,
            type: 'red',
            typeAnimated: true,
            closeIcon: true,
            buttons: {
                confirm: function () {
                    $.LoadingOverlay("show");
                    $.ajax({
                        url: "<?php echo e(url('admin/user/delete_address_image')); ?>",
                        type: 'post',
                        data: {user_id: user_id},
                        success: function (data) {
                            if (data.status == true) {
                                $.LoadingOverlay("hide");
                                success_message(data.message);
                                setTimeout(function () {
                                    location.reload();
                                }, 100)
                            $('a[href="#tab_1_3"]').trigger('click');
                            }
                        },
                    });
                },
                cancel: function () {
                }
            }
        });
    }
    function verifyAddress(user_id) {
        $.confirm({
            title: 'Address Verification!',
            content: 'Are you sure you want to verify user address!',
            draggable: false,
            type: 'red',
            typeAnimated: true,
            closeIcon: true,
            buttons: {
                confirm: function () {
                    $.LoadingOverlay("show");
                    $.ajax({
                        url: "<?php echo e(url('admin/user/address/verify')); ?>",
                        type: 'post',
                        data: {user_id: user_id},
                        success: function (data) {
                            if (data.status == true) {
                                $.LoadingOverlay("hide");
                                success_message(data.message);
                                setTimeout(function () {
                                    location.reload();
                                }, 100)

                                $('a[href="#tab_1_3"]').trigger('click');

                            }
                        },
                    });
                },
                cancel: function () {
                }
            }
        });

    }
    function approveDisapproveStatus(status)
    {
        $.confirm({
            title: 'Change Status!',
            content: 'Are you sure you want to change status!',
            draggable: false,
            type: 'red',
            typeAnimated: true,
            closeIcon: true,
            buttons: {
                confirm: function () {
                    $.LoadingOverlay("show");
                    $.ajax({
                        url: baseUrl +'/admin/user/update_approve_status',
                        type: 'POST',
                        data: {user_id: user_id, value: status},
                        success: function (data) {
                            if (data.status == true) {
                                $.LoadingOverlay("hide");
                                success_message(data.message);
                                setTimeout(function () {
                                    location.reload();
                                }, 50)
                            }
                        },
                    });
                },
                cancel: function () {
                }
            }
        });
    }


    // ----------------------------------------------------------

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>