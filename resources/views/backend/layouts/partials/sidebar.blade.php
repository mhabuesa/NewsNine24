<nav id="sidebar" aria-label="Main Navigation">
    <div class="content-header">
        <a class="fw-semibold text-dual m-auto" href="{{ route('dashboard') }}">
            <span class="smini-visible">
                <i class="fa fa-circle-notch text-primary"></i>
            </span>
            <small class="smini-hide tracking-wider">{{ config('app.name') }}</small>
        </a>
        <div>
            <a class="d-lg-none btn btn-sm btn-alt-secondary ms-1" data-toggle="layout" data-action="sidebar_close"
                href="javascript:void(0)">
                <i class="fa fa-fw fa-times"></i>
            </a>
        </div>
    </div>
    <div class="js-sidebar-scroll">
        <div class="content-side">
            <ul class="nav-main">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="nav-main-link-icon si si-speedometer"></i>
                        <span class="nav-main-link-name">Dashboard</span>
                    </a>
                </li>
                <li class="nav-main-heading">Category Management</li>
                <li
                    class="nav-main-item {{ request()->routeIs('category.*') ? 'open' : (request()->routeIs('subcategory.*') ? 'open' : '') }}">
                    <a class="nav-main-link nav-main-link-submenu {{ request()->routeIs('category.*') ? 'active' : '' }}"
                        data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon fas fa-file-invoice-dollar"></i>
                        <span class="nav-main-link-name">Categories</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->routeIs('category.index') ? 'active' : '' }}"
                                href="{{ route('category.index') }}">
                                <span class="nav-main-link-name">
                                    <i class="fas fa-layer-group me-2"></i>Categories
                                </span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->routeIs('subcategory.*') ? 'active' : '' }}"
                                href="{{ route('subcategory.index') }}">
                                <span class="nav-main-link-name">
                                    <i class="fas fa-folder-open me-2"></i> Sub Categories
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-main-heading">
                    </i> News Management
                </li>
                <li class="nav-main-item {{ request()->routeIs('news.*') ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu {{ request()->routeIs('news.*') ? 'active' : '' }}"
                        data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">

                        <i class="nav-main-link-icon fas fa-newspaper"></i>
                        <span class="nav-main-link-name">News</span>
                    </a>

                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->routeIs('news.index') ? 'active' : (request()->routeIs('news.show') ? 'active' : '') }}"
                                href="{{ route('news.index') }}">
                                <span class="nav-main-link-name">
                                    <i class="fas fa-file-invoice me-2"></i> News List
                                </span>
                            </a>
                        </li>

                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->routeIs('news.create') ? 'active' : '' }}"
                                href="{{ route('news.create') }}">
                                <span class="nav-main-link-name">
                                    <i class="fas fa-circle-plus me-2"></i> Add New News
                                </span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->routeIs('news.trash') ? 'active' : '' }}"
                                href="{{ route('news.trash') }}">
                                <span class="nav-main-link-name">
                                    <i class="fas fa-trash me-2"></i>Trash
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-main-heading">Announcements</li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="">
                        <i class="nav-main-link-icon fas fa-file-contract"></i>
                        <span class="nav-main-link-name"> Terms & Conditions</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
