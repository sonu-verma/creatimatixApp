 <!--begin::Sidebar-->
 <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
      <!--begin::Brand Link-->
      <a href="./index.html" class="brand-link">
        <!--begin::Brand Image-->
        <img
          src="{{ asset('admin/assets/img/AdminLTELogo.png') }}"
          alt="Sport Logo"
          class="brand-image opacity-75 shadow"
        />
        <!--end::Brand Image-->
        <!--begin::Brand Text-->
        <span class="brand-text fw-light">Sport Mania</span>
        <!--end::Brand Text-->
      </a>
      <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
      <nav class="mt-2">
        <!--begin::Sidebar Menu-->
        <ul
          class="nav sidebar-menu flex-column"
          data-lte-toggle="treeview"
          role="menu"
          data-accordion="false"
        >
          <li class="nav-item menu-open">
            <a href="{{ route('dashboard')}} " class="nav-link active">
              <i class="nav-icon bi bi-speedometer"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          @php
            $currentRoute = request()->route()->getName();
            $isTurfActive = str_contains($currentRoute, 'turf');
          @endphp

          <li class="nav-item {{ $isTurfActive ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ $isTurfActive ? 'active' : '' }}">
              <i class="nav-icon bi bi-ui-checks-grid"></i>
              <p>
                Turfs
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('turfs')}}" class="nav-link {{ $currentRoute === 'turfs' ? 'active' : '' }}">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Turf Lists</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
        <!--end::Sidebar Menu-->
      </nav>
    </div>
    <!--end::Sidebar Wrapper-->
  </aside>
  <!--end::Sidebar-->