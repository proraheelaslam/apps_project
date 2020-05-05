<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="{{url('admin/home')}}" >
                <img src="{{asset('theme/layouts/layout/img/logo.png')}}" alt="logo" class="logo-default"/> </a>
            <div class="menu-toggler sidebar-toggler">
                <span>
                    <i class="toggler-class"></i>
                </span>
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse">
            <span><i class="toggler-class"></i></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN NOTIFICATION DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                       data-close-others="true">
                        @php
                            $user = Auth::user();
                        @endphp
                        @if(isset($user->profile_image))
                            <img alt="" style="width: 29px" class="img-circle" src=" {{$user->full_image}}"/>
                        @else
                            <img alt="" class="img-circle" src=" {{asset('theme/layouts/layout/img/avator_9.png')}}"/>
                        @endif
                        <span class="username username-hide-on-mobile"> {{@Auth::guard('admin')->user()->name}} </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="{{url('admin/profile')}}">
                                <i class="icon-user"></i> My Profile </a>
                        </li>
                        {{--<li class="divider"></li>--}}
                        <li>
                            <a href="{{url('admin/password/change')}}">
                                <i class="icon-lock"></i> Change Password </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.logout') }}"
                               onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                <i class="icon-key"></i> Log Out
                            </a>

                            <form id="logout-form" action="{{ url('admin/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
