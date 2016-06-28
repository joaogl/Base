<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
                <a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-users fa-fw"></i> Users<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ url('admin/users/list') }}">Users</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/users/create') }}">Add users</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/users/deleted') }}">Deleted users</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/users/pending') }}">Pending registrations</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/users/blocked') }}">Blocked Users</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-comment fa-fw"></i> Blog<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ url('admin/categories/list') }}">Categories</a>
                    </li>
                    <li class="hidden">
                        <a href="{{ url('admin/categories/create') }}">Add category</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/posts/list') }}">Posts</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/categories/deleted') }}">Deleted categories</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/posts/deleted') }}">Deleted posts</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-cogs fa-fw"></i> System<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ url('admin/groups/list') }}">Groups</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/settings') }}">Settings</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/visits') }}">Visits</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/queues') }}">Queues</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/logs') }}">Logs</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->