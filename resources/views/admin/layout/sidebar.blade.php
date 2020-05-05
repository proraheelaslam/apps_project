<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu  page-header-fixed  {{ Session::get('sidebar-ulSidebarState') }}" data-keep-expanded="false" data-auto-scroll="true"
            data-slide-speed="200" style="padding-top: 20px" id="sidebar-ul">
            {{--  page-sidebar-menu-closed  --}}
            <li class="sidebar-toggler-wrapper hide">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler">
                    <span></span>
                </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>
            @can('view neighborhoods')
            <li class="nav-item {{ isActiveRoute('neighborhoods') }}">
                <a href="{{url('admin/neighborhoods')}}" class="nav-link ">
                    <i class="fa fa-map-pin"></i>
                    <span class="title">Manage Neighborhoods</span>
                </a>
            </li>
            @endcan
            @can('view export_neighborhood')
                <li class="nav-item {{ isActiveRoute('neighborhood/export/create') }}">
                    <a href="{{url('admin/neighborhood/export/create')}}" class="nav-link ">
                        <i class="fa fa-file-code-o"></i>
                        <span class="title">Export Neighborhood</span>
                    </a>
                </li>
            @endcan
            {{--@can('view postCategories')
            <li class="nav-item {{ isActiveRoute('categories') }}">
                <a href="{{url('admin/post/categories')}}" class="nav-link ">
                    <i class="fa fa-tags"></i>
                    <span class="title">Post Categories</span>
                </a>
            </li>
            @endcan--}}
            @can('view message posts')
                <li class="nav-item {{ isActiveRoute('message_post') }} {{(isset($post) && $post->post_type == 'message') ? 'open ' :''}}" >
                    <a href="{{url('admin/message_posts')}}" class="nav-link ">
                        <i class="fa fa-newspaper-o"></i>
                        <span class="title">Message Posts</span>
                    </a>
                </li>
            @endcan
            @can('view poll posts')
            <li class="nav-item {{ isActiveRoute('poll_posts') }} {{(isset($post) && $post->post_type == 'poll') ? 'open ' :''}}">
                <a href="{{url('admin/poll_posts')}}" class="nav-link ">
                    <i class="icon-graph"></i>
                    <span class="title">Manage Polls</span>
                </a>
            </li>
            @endcan
            @can('view alert posts')
            <li class="nav-item {{ isActiveRoute('alert_posts') }} {{(isset($post) && $post->post_type == 'alert') ? 'open ' :''}}">
                <a href="{{url('admin/alert_posts')}}" class="nav-link ">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span class="title">Manage User Alerts</span>
                </a>
            </li>
            @endcan
            @can('view event_categories')
                <li class="nav-item {{ isActiveRoute('event_categories') }}">
                    <a href="{{url('admin/event_categories')}}" class="nav-link ">
                        <i class="icon-drawer"></i>
                        <span class="title">Manage Event Categories</span>
                    </a>
                </li>
            @endcan
            @can('view events')
            <li class="nav-item {{ isActiveRoute('events') }}">
                <a href="{{url('admin/events')}}" class="nav-link ">
                    <i class="fa fa-calendar-o"></i>
                    <span class="title">Manage Events</span>
                </a>
            </li>
            @endcan

            @can('view business_categories')
            <li class="nav-item {{ isActiveRoute('business_categories') }}">
                <a href="{{url('admin/business_categories')}}" class="nav-link ">
                    <i class="icon-puzzle"></i>
                    <span class="title">Business Categories</span>
                </a>
            </li>
            @endcan

            @can('view businesses')
            <li class="nav-item {{ isActiveRoute('businesses') }}">
                <a href="{{url('admin/businesses')}}" class="nav-link ">
                    <i class="fa fa-briefcase"></i>
                    <span class="title">Manage Businesses</span>
                </a>
            </li>
            @endcan

            @can('view business_report_reason')
            <li class="nav-item {{ isActiveRoute('business_report_reason') }}">
                <a href="{{url('admin/business_report_reason')}}" class="nav-link ">
                    <i class="fa fa-warning"></i>
                    <span class="title">Report Reason</span>
                </a>
            </li>
            @endcan
            
            @can('view classified categories')
            <li class="nav-item {{ isActiveRoute('classified_category') }}">
                <a href="{{url('admin/classified_category')}}" class="nav-link ">
                    <i class="fa fa-object-group"></i>
                    <span class="title">Classified Categories</span>
                </a>
            </li>
            @endcan
            
            @can('view classifieds')
            <li class="nav-item {{ isActiveRoute('classifieds') }}">
                <a href="{{url('admin/classifieds')}}" class="nav-link ">
                    <i class="fa fa-reorder"></i>
                    <span class="title">Manage Classifieds</span>
                </a>
            </li>
            @endcan
            @can('view users')
                <li class="nav-item {{ isActiveRoute('users') }}">
                    <a href="{{url('admin/users')}}" class="nav-link ">
                        <i class="icon-user"></i>
                        <span class="title">Manage Users</span>
                    </a>
                </li>
            @endcan
            @can('view pages')
            <li class="nav-item {{ isActiveRoute('pages') }}">
                <a href="{{url('admin/pages')}}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">Manage Pages</span>
                </a>
            </li>
            @endcan
            @can('view emails')
            <li class="nav-item {{ isActiveRoute('emails') }}">
                <a href="{{url('admin/emails')}}" class="nav-link ">
                    <i class="fa fa-envelope"></i>
                    <span class="title">Manage Emails</span>
                </a>
            </li>
            @endcan
            @can('view ads')
            <li class="nav-item {{ isActiveRoute('ads') }}">
                <a href="{{url('admin/ads')}}" class="nav-link ">
                    <i class="fa fa-buysellads"></i>
                    <span class="title">Manage Ads</span>
                </a>
            </li>
            @endcan
            @can('view notifications')
            <li class="nav-item {{ isActiveRoute('app_notification') }}">
                <a href="{{url('admin/app_notification')}}" class="nav-link ">
                    <i class="fa fa-bell"></i>
                    <span class="title">Manage App Notifications</span>
                </a>
            </li>
            @endcan
            @can('push_notification_view')
                <li class="nav-item {{ isActiveRoute('app/push_notifications') }}">
                    <a href="{{url('admin/app/push_notifications')}}" class="nav-link ">
                        <i class="fa fa-bell-o"></i>
                        <span class="title">App Push Notifications</span>
                    </a>
                </li>
            @endcan
            @can('view appSettings')
            <li class="nav-item {{ isActiveRoute('app_settings') }}">
                <a href="{{url('admin/app_settings')}}" class="nav-link ">
                    <i class="fa fa-cog"></i>
                    <span class="title">Manage App Settings</span>
                </a>
            </li>
            @endcan
            @can('view managers')
                <li class="nav-item {{ isActiveRoute('managers') }}">
                    <a href="{{url('admin/managers')}}" class="nav-link ">
                        <i class="fa fa-user-plus"></i>
                        <span class="title">Manage Admin Managers</span>
                    </a>
                </li>
            @endcan
              @can('view roles')
            <li class="nav-item {{ isActiveRoute('roles') }}">
                <a href="{{url('admin/roles')}}" class="nav-link ">
                    <i class="fa fa-tasks"></i>
                    <span class="title">Manage Admin Roles</span>
                </a>
            </li>
            @endcan
            <li class="nav-item {{ isActiveRoute('roles') }}">
                <a href="{{url('admin/db/backup')}}" class="nav-link ">
                    <i class="fa fa-tasks"></i>
                    <span class="title">Manage DB Backup</span>
                </a>
            </li>
            {{-- @can('view permission')
            <li class="nav-item {{ isActiveRoute('permissions') }}">
                <a href="{{url('admin/permissions')}}" class="nav-link ">
                    <i class="fa fa-lock"></i>

                    <span class="title">Permissions</span>
                </a>
            </li>
            @endcan --}}

        </ul>
        <!-- END SIDEBAR MENU -->
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>
<!-- END SIDEBAR -->