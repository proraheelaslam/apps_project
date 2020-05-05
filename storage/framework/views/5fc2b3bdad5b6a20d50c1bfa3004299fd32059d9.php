<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu  page-header-fixed  <?php echo e(Session::get('sidebar-ulSidebarState')); ?>" data-keep-expanded="false" data-auto-scroll="true"
            data-slide-speed="200" style="padding-top: 20px" id="sidebar-ul">
            
            <li class="sidebar-toggler-wrapper hide">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler">
                    <span></span>
                </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view neighborhoods')): ?>
            <li class="nav-item <?php echo e(isActiveRoute('neighborhoods')); ?>">
                <a href="<?php echo e(url('admin/neighborhoods')); ?>" class="nav-link ">
                    <i class="fa fa-map-pin"></i>
                    <span class="title">Manage Neighborhoods</span>
                </a>
            </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view export_neighborhood')): ?>
                <li class="nav-item <?php echo e(isActiveRoute('neighborhood/export/create')); ?>">
                    <a href="<?php echo e(url('admin/neighborhood/export/create')); ?>" class="nav-link ">
                        <i class="fa fa-file-code-o"></i>
                        <span class="title">Export Neighborhood</span>
                    </a>
                </li>
            <?php endif; ?>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view message posts')): ?>
                <li class="nav-item <?php echo e(isActiveRoute('message_post')); ?> <?php echo e((isset($post) && $post->post_type == 'message') ? 'open ' :''); ?>" >
                    <a href="<?php echo e(url('admin/message_posts')); ?>" class="nav-link ">
                        <i class="fa fa-newspaper-o"></i>
                        <span class="title">Message Posts</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view poll posts')): ?>
            <li class="nav-item <?php echo e(isActiveRoute('poll_posts')); ?> <?php echo e((isset($post) && $post->post_type == 'poll') ? 'open ' :''); ?>">
                <a href="<?php echo e(url('admin/poll_posts')); ?>" class="nav-link ">
                    <i class="icon-graph"></i>
                    <span class="title">Manage Polls</span>
                </a>
            </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view alert posts')): ?>
            <li class="nav-item <?php echo e(isActiveRoute('alert_posts')); ?> <?php echo e((isset($post) && $post->post_type == 'alert') ? 'open ' :''); ?>">
                <a href="<?php echo e(url('admin/alert_posts')); ?>" class="nav-link ">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span class="title">Manage User Alerts</span>
                </a>
            </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view event_categories')): ?>
                <li class="nav-item <?php echo e(isActiveRoute('event_categories')); ?>">
                    <a href="<?php echo e(url('admin/event_categories')); ?>" class="nav-link ">
                        <i class="icon-drawer"></i>
                        <span class="title">Manage Event Categories</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view events')): ?>
            <li class="nav-item <?php echo e(isActiveRoute('events')); ?>">
                <a href="<?php echo e(url('admin/events')); ?>" class="nav-link ">
                    <i class="fa fa-calendar-o"></i>
                    <span class="title">Manage Events</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view business_categories')): ?>
            <li class="nav-item <?php echo e(isActiveRoute('business_categories')); ?>">
                <a href="<?php echo e(url('admin/business_categories')); ?>" class="nav-link ">
                    <i class="icon-puzzle"></i>
                    <span class="title">Business Categories</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view businesses')): ?>
            <li class="nav-item <?php echo e(isActiveRoute('businesses')); ?>">
                <a href="<?php echo e(url('admin/businesses')); ?>" class="nav-link ">
                    <i class="fa fa-briefcase"></i>
                    <span class="title">Manage Businesses</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view business_report_reason')): ?>
            <li class="nav-item <?php echo e(isActiveRoute('business_report_reason')); ?>">
                <a href="<?php echo e(url('admin/business_report_reason')); ?>" class="nav-link ">
                    <i class="fa fa-warning"></i>
                    <span class="title">Report Reason</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view classified categories')): ?>
            <li class="nav-item <?php echo e(isActiveRoute('classified_category')); ?>">
                <a href="<?php echo e(url('admin/classified_category')); ?>" class="nav-link ">
                    <i class="fa fa-object-group"></i>
                    <span class="title">Classified Categories</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view classifieds')): ?>
            <li class="nav-item <?php echo e(isActiveRoute('classifieds')); ?>">
                <a href="<?php echo e(url('admin/classifieds')); ?>" class="nav-link ">
                    <i class="fa fa-reorder"></i>
                    <span class="title">Manage Classifieds</span>
                </a>
            </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view users')): ?>
                <li class="nav-item <?php echo e(isActiveRoute('users')); ?>">
                    <a href="<?php echo e(url('admin/users')); ?>" class="nav-link ">
                        <i class="icon-user"></i>
                        <span class="title">Manage Users</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view pages')): ?>
            <li class="nav-item <?php echo e(isActiveRoute('pages')); ?>">
                <a href="<?php echo e(url('admin/pages')); ?>" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">Manage Pages</span>
                </a>
            </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view emails')): ?>
            <li class="nav-item <?php echo e(isActiveRoute('emails')); ?>">
                <a href="<?php echo e(url('admin/emails')); ?>" class="nav-link ">
                    <i class="fa fa-envelope"></i>
                    <span class="title">Manage Emails</span>
                </a>
            </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view ads')): ?>
            <li class="nav-item <?php echo e(isActiveRoute('ads')); ?>">
                <a href="<?php echo e(url('admin/ads')); ?>" class="nav-link ">
                    <i class="fa fa-buysellads"></i>
                    <span class="title">Manage Ads</span>
                </a>
            </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view notifications')): ?>
            <li class="nav-item <?php echo e(isActiveRoute('app_notification')); ?>">
                <a href="<?php echo e(url('admin/app_notification')); ?>" class="nav-link ">
                    <i class="fa fa-bell"></i>
                    <span class="title">Manage App Notifications</span>
                </a>
            </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('push_notification_view')): ?>
                <li class="nav-item <?php echo e(isActiveRoute('app/push_notifications')); ?>">
                    <a href="<?php echo e(url('admin/app/push_notifications')); ?>" class="nav-link ">
                        <i class="fa fa-bell-o"></i>
                        <span class="title">App Push Notifications</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view appSettings')): ?>
            <li class="nav-item <?php echo e(isActiveRoute('app_settings')); ?>">
                <a href="<?php echo e(url('admin/app_settings')); ?>" class="nav-link ">
                    <i class="fa fa-cog"></i>
                    <span class="title">Manage App Settings</span>
                </a>
            </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view managers')): ?>
                <li class="nav-item <?php echo e(isActiveRoute('managers')); ?>">
                    <a href="<?php echo e(url('admin/managers')); ?>" class="nav-link ">
                        <i class="fa fa-user-plus"></i>
                        <span class="title">Manage Admin Managers</span>
                    </a>
                </li>
            <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view roles')): ?>
            <li class="nav-item <?php echo e(isActiveRoute('roles')); ?>">
                <a href="<?php echo e(url('admin/roles')); ?>" class="nav-link ">
                    <i class="fa fa-tasks"></i>
                    <span class="title">Manage Admin Roles</span>
                </a>
            </li>
            <?php endif; ?>
            <li class="nav-item <?php echo e(isActiveRoute('roles')); ?>">
                <a href="<?php echo e(url('admin/db/backup')); ?>" class="nav-link ">
                    <i class="fa fa-tasks"></i>
                    <span class="title">Manage DB Backup</span>
                </a>
            </li>
            

        </ul>
        <!-- END SIDEBAR MENU -->
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>
<!-- END SIDEBAR -->