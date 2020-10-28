<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
{{--        <li class="nav-item">--}}
{{--            <a href="/home" class="nav-link {{Request::is('home')?'active':''}}">--}}
{{--                <i class="nav-icon fas fa-tachometer-alt"></i>--}}
{{--                <p>--}}
{{--                    首页--}}
{{--                </p>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item">--}}
{{--            <a href="{{ route('user.ranking') }}" class="nav-link {{Request::is('ranking*')?'active':''}}">--}}
{{--                <i class="nav-icon fas fa-chart-bar"></i>--}}
{{--                <p>--}}
{{--                    客服排行--}}
{{--                </p>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item">--}}
{{--            <a href="{{ route('member.index') }}" class="nav-link {{Request::is('member*')?'active':''}}">--}}
{{--                <i class="nav-icon fas fa-list-alt"></i>--}}
{{--                <p>--}}
{{--                    信息列表--}}
{{--                </p>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item">--}}
{{--            <a href="{{ route('track.index') }}" class="nav-link {{Request::is('track*')?'active':''}}">--}}
{{--                <i class="nav-icon fas fa-comments"></i>--}}
{{--                <p>--}}
{{--                    待跟踪回访--}}
{{--                    <span class="right badge badge-danger" id="onTracking"></span>--}}
{{--                </p>--}}
{{--            </a>--}}
{{--        </li>--}}
        <li class="nav-item has-treeview {{Request::is('data/*')?'menu-open':''}}">
            <a href="#" class="nav-link ">
                <i class="nav-icon fas fa-database"></i>
                <p>
                    数据管理
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('swt.index') }}" class="nav-link {{Request::is('data/swt*')?'active':''}}">
                        <i class="fas fa-comment-dots nav-icon"></i>
                        <p>商务通</p>
                    </a>
                </li>
{{--                <li class="nav-item">--}}
{{--                    <a href="{{ route('user.index') }}" class="nav-link {{Request::is('data/area*')?'active':''}}">--}}
{{--                        <i class="fas fa-map-marker-alt nav-icon"></i>--}}
{{--                        <p>地域</p>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a href="{{ route('user.index') }}" class="nav-link {{Request::is('data/time*')?'active':''}}">--}}
{{--                        <i class="far fa-clock nav-icon"></i>--}}
{{--                        <p>时段</p>--}}
{{--                    </a>--}}
{{--                </li>--}}
            </ul>
        </li>
        <li class="nav-item has-treeview {{Request::is('config/*')?'menu-open':''}}">
            <a href="#" class="nav-link ">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                    设置
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('hospital.index') }}" class="nav-link {{Request::is('config/hospital*')?'active':''}}">
                        <i class="fas fa-hospital nav-icon"></i>
                        <p>医院管理</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('channel.index') }}" class="nav-link {{Request::is('config/channel*')?'active':''}}">
                        <i class="fab fa-medium nav-icon"></i>
                        <p>媒体来源</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('consult.index') }}" class="nav-link {{Request::is('config/consult*')?'active':''}}">
                        <i class="fab fa-searchengin nav-icon"></i>
                        <p>搜索引擎</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('doctor.index') }}" class="nav-link {{Request::is('config/doctor*')?'active':''}}">
                        <i class="fas fa-user-md nav-icon"></i>
                        <p>医生管理</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('disease.index') }}" class="nav-link {{Request::is('config/disease*')?'active':''}}">
                        <i class="fas fa-disease nav-icon"></i>
                        <p>病种设置</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('condition.index') }}" class="nav-link {{Request::is('config/condition*')?'active':''}}">
                        <i class="far fa-question-circle nav-icon"></i>
                        <p>客户状态</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item has-treeview {{Request::is('sys/*')?'menu-open':''}}">
            <a href="#" class="nav-link ">
                <i class="nav-icon fas fa-cogs"></i>
                <p>
                    系统管理
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link {{Request::is('sys/user*')?'active':''}}">
                        <i class="fas fa-users nav-icon"></i>
                        <p>用户管理</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('role.index') }}" class="nav-link {{Request::is('sys/role*')?'active':''}}">
                        <i class="fas fa-lock nav-icon"></i>
                        <p>角色管理</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('permission.index') }}" class="nav-link {{Request::is('sys/permission*')?'active':''}}">
                        <i class="fas fa-key nav-icon"></i>
                        <p>权限管理</p>
                    </a>
                </li>
            </ul>
        </li>
{{--        <li class="nav-item">--}}
{{--            <a href="{{ route('gh.index') }}" class="nav-link {{Request::is('gh*')?'active':''}}">--}}
{{--                <i class="nav-icon fas fa-list-alt"></i>--}}
{{--                <p>--}}
{{--                    在线挂号--}}
{{--                </p>--}}
{{--            </a>--}}
{{--        </li>--}}
    </ul>
</nav>
<!-- /.sidebar-menu -->
