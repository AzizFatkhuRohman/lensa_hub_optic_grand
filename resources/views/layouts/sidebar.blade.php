<!--  App Topstrip -->
<div class="app-topstrip bg-dark py-3 px-4 w-100 d-lg-flex align-items-center justify-content-between">
    <div class="d-none d-sm-flex align-items-center justify-content-center gap-9 mb-3 mb-lg-0">
        <a class="d-flex justify-content-center" href="https://adminmart.com/" target="_blank">
            <img src="{{ asset('assets/images/logos/logo-adminmart.svg') }}" alt="" width="150">
        </a>
    </div>

    <div class="d-lg-flex align-items-center gap-3">
        <h3 class="text-linear-gradient mb-3 mb-lg-0 fs-3 text-uppercase text-center fw-semibold" id="realtimeClock">
            00:00:00
        </h3>
    </div>

</div>
<!-- Sidebar Start -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <!-- Sidebar navigation-->
        @php
            $menus = $sidebar->pluck('menu');
            $parents = $menus->where('level', 1);
        @endphp
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ url('/') }}" aria-expanded="false">
                        <i class="ti ti-atom"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <!-- ---------------------------------- -->
                <!-- Dashboard -->
                <!-- ---------------------------------- -->
                {{-- <li class="sidebar-item">
                    <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)"
                        aria-expanded="false">
                        <div class="d-flex align-items-center gap-3">
                            <span class="d-flex">
                                <i class="ti ti-layout-grid"></i>
                            </span>
                            <span class="hide-menu">Front Pages</span>
                        </div>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a class="sidebar-link justify-content-between" target="_blank"
                                href="https://bootstrapdemos.adminmart.com/modernize/dist/main/frontend-landingpage.html">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Homepage</span>
                                </div>
                                <span class="hide-menu badge bg-secondary-subtle text-secondary fs-1 py-1">Pro</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link justify-content-between" target="_blank"
                                href="https://bootstrapdemos.adminmart.com/modernize/dist/main/frontend-aboutpage.html">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">About Us</span>
                                </div>
                                <span class="hide-menu badge bg-secondary-subtle text-secondary fs-1 py-1">Pro</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link justify-content-between" target="_blank"
                                href="https://bootstrapdemos.adminmart.com/modernize/dist/main/frontend-blogpage.html">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Blog</span>
                                </div>
                                <span class="hide-menu badge bg-secondary-subtle text-secondary fs-1 py-1">Pro</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link justify-content-between" target="_blank"
                                href="https://bootstrapdemos.adminmart.com/modernize/dist/main/frontend-blogdetailpage.html">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Blog Details</span>
                                </div>
                                <span class="hide-menu badge bg-secondary-subtle text-secondary fs-1 py-1">Pro</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link justify-content-between" target="_blank"
                                href="https://bootstrapdemos.adminmart.com/modernize/dist/main/frontend-contactpage.html">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Contact Us</span>
                                </div>
                                <span class="hide-menu badge bg-secondary-subtle text-secondary fs-1 py-1">Pro</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link justify-content-between" target="_blank"
                                href="https://bootstrapdemos.adminmart.com/modernize/dist/main/frontend-portfoliopage.html">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Portfolio</span>
                                </div>
                                <span class="hide-menu badge bg-secondary-subtle text-secondary fs-1 py-1">Pro</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link justify-content-between" target="_blank"
                                href="https://bootstrapdemos.adminmart.com/modernize/dist/main/frontend-pricingpage.html">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Pricing</span>
                                </div>
                                <span class="hide-menu badge bg-secondary-subtle text-secondary fs-1 py-1">Pro</span>
                            </a>
                        </li>
                    </ul>
                </li> --}}

                <li>
                    <span class="sidebar-divider lg"></span>
                </li>
                @php
                    $menus = $sidebar;
                    $parents = $menus->whereNull('parent_id');
                @endphp

                @foreach ($parents as $parent)
                    @php
                        $parentSubmenus = $menus->where('parent_id', $parent->id);
                    @endphp

                    @if ($parent->url && $parentSubmenus->isEmpty())
                        <li class="sidebar-item">
                            <a href="{{ url($parent->url) }}" class="sidebar-link">
                                <span class="d-flex">
                                    <i class="{{ $parent->icon ?: 'ti ti-circle' }}"></i>
                                </span>
                                <span class="hide-menu">{{ $parent->name }}</span>
                            </a>
                        </li>
                    @else
                        {{-- LEVEL 1 --}}
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">{{ $parent->name }}</span>
                        </li>
                    @endif

                    {{-- LEVEL 2 --}}
                    @foreach ($parentSubmenus as $submenu)
                        {{-- Ambil Level 3 berdasarkan parent_id --}}
                        @php
                            $submenus = $menus->where('parent_id', $submenu->id);
                        @endphp

                        @if ($submenus->isNotEmpty())
                            {{-- LEVEL 2 dengan anak --}}
                            <li class="sidebar-item">

                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">

                                    <span class="d-flex">
                                        <i class="{{ $submenu->icon ?? 'ti ti-circle' }}"></i>
                                    </span>

                                    <span class="hide-menu">
                                        {{ $submenu->name }}
                                    </span>

                                </a>

                                {{-- LEVEL 3 --}}
                                <ul aria-expanded="false" class="collapse first-level">

                                    @foreach ($submenus as $subsubmenu)
                                        <li class="sidebar-item">

                                            <a href="{{ $subsubmenu->url ? url($subsubmenu->url) : '#' }}" class="sidebar-link">

                                                <span class="d-flex">
                                                    <i class="{{ $subsubmenu->icon ?? 'ti ti-circle' }}"></i>
                                                </span>

                                                <span class="hide-menu">
                                                    {{ $subsubmenu->name }}
                                                </span>

                                            </a>

                                        </li>
                                    @endforeach

                                </ul>

                            </li>
                        @else
                            {{-- LEVEL 2 TANPA ANAK --}}
                            <li class="sidebar-item">

                                <a href="{{ $submenu->url ? url($submenu->url) : '#' }}" class="sidebar-link">

                                    <span class="d-flex">
                                        <i class="{{ $submenu->icon ?? 'ti ti-circle' }}"></i>
                                    </span>

                                    <span class="hide-menu">
                                        {{ $submenu->name }}
                                    </span>

                                </a>

                            </li>
                        @endif
                    @endforeach
                @endforeach
                {{-- <li>
                    <span class="sidebar-divider lg"></span>
                </li> --}}

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->
