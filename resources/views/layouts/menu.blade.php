<li class="{{ Request::is('admin/home*') || Request::is('admin/search*') ? 'active' : '' }}">
    <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-home"></i><span>Home</span></a>
</li>
<li class="{{ Request::is('admin/letters*')? 'active':'' }}">
    <a href="{!! route('letters.index') !!}"><i class="fa fa fa-envelope"></i><span>Letters</span></a>
</li>
<li class="{{ Request::is('admin/leave_requests*')? 'active':'' }}">
    <a href="{!! route('leave_requests.index') !!}"><i class="fa fa-address-card-o"></i><span>Leave Requests</span></a>
</li>
@can("view_leave_roster",auth()->user())
<li class="{{ Request::is('admin/leave_roster*')? 'active':'' }}">
    <a href="{!! route('leave_roster.index') !!}"><i class="fa fa-address-card-o"></i><span>Leave Roster</span></a>
</li>
@endcan
<li class="{{ Request::is('admin/cash_requests*')? 'active':'' }}">
    <a href="{!! route('cash_requests.index') !!}"><i class="fa fa-money"></i><span>Cash Requests</span></a>
</li>
<li class="treeview {{ Request::is('admin/plugin*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-plug"></i>
        <span>Plugins</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('admin/plugins/image2pdf*') ? 'active' : '' }}">
            <a href="{!! route('image2pdf.create') !!}"><i class="fa fa-file-image-o"></i><span>Images to PDF</span></a>
        </li>
    </ul>
</li>

@can('read users')
    <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
        <a href="{!! route('users.index') !!}"><i class="fa fa-users"></i><span>Users</span></a>
    </li>
@endcan
@can('read tags')
    <li class="{{ Request::is('admin/tags*') ? 'active' : '' }}">
        <a href="{!! route('tags.index') !!}"><i
                class="fa fa-tags"></i><span>{{ucfirst(config('settings.tags_label_plural'))}}</span></a>
    </li>
@endcan

@if(auth()->user()->is_super_admin)
    <li class="treeview {{ Request::is('admin/advanced*') ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-info-circle"></i>
            <span>Advanced Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{ Request::is('admin/advanced/settings*') ? 'active' : '' }}">
                <a href="{!! route('settings.index') !!}"><i class="fa fa-gear"></i><span>Settings</span></a>
            </li>
            <li class="{{ Request::is('admin/advanced/custom-fields*') ? 'active' : '' }}">
                <a href="{!! route('customFields.index') !!}"><i
                        class="fa fa-file-text-o"></i><span>Custom Fields</span></a>
            </li>
            <li class="{{ Request::is('admin/advanced/file-types*') ? 'active' : '' }}">
                <a href="{!! route('fileTypes.index') !!}"><i class="fa fa-file-o"></i><span>{{ucfirst(config('settings.file_label_singular'))}} Types</span></a>
            </li>
        </ul>
    </li>
@endif

