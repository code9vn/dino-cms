<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">
    <div class="sidebar-content">
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                @foreach ($menus = DashboardMenu::getAll() as $menu)
                @php $menu = apply_filters(BASE_FILTER_DASHBOARD_MENU, $menu); @endphp
                <li class="nav-item @if (isset($menu['children']) && count($menu['children'])) nav-item-submenu @endif @if ($menu['active']) nav-item-open @endif"
                    id="{{ $menu['id'] }}">
                    <a href="{{ $menu['url'] }}" class="nav-link @if ($menu['active']) active @endif">
                        @if ($menu['icon'])
                        <i class="{{ $menu['icon'] }}"></i>
                        @endif
                        <span>{{ !is_array(trans($menu['name'])) ? trans($menu['name']) : null }}
                            {!! apply_filters(BASE_FILTER_APPEND_MENU_NAME, null, $menu['id']) !!}</span>
                    </a>
                    @if (isset($menu['children']) && count($menu['children']))
                    <ul class="nav-group-sub @if ($menu['active']) collapse show @else collapse @endif">
                        @foreach ($menu['children'] as $item)
                        <li class="nav-item" id="{{ $item['id'] }}">
                            <a href="{{ $item['url'] }}" class="nav-link @if ($item['active']) active @endif">
                                @if ($item['icon'])
                                <i class="{{ $item['icon'] }}"></i>
                                @endif
                                {{ trans($item['name']) }}
                                {!! apply_filters(BASE_FILTER_APPEND_MENU_NAME, null, $item['id']) !!}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
