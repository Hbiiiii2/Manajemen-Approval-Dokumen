
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ url('dashboard') }}">
        <img src="../assets/img/logo-ct.png" class="navbar-brand-img h-100" alt="...">
        <span class="ms-3 font-weight-bold">Manajemen Approval</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('dashboard') ? 'active' : '') }}" href="{{ url('dashboard') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-chart-bar-32 text-primary"></i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>

      

      <!-- Menu untuk Staff -->
      @if(auth()->user()->role->name == 'staff')
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('documents/create') ? 'active' : '') }}" href="{{ url('documents/create') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-collection text-success"></i>
          </div>
          <span class="nav-link-text ms-1">Tambah Dokumen</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('documents') ? 'active' : '') }}" href="{{ url('documents') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-single-copy-04 text-info"></i>
          </div>
          <span class="nav-link-text ms-1">Dokumen Saya</span>
        </a>
      </li>
      @endif

      <!-- Menu untuk Manager -->
      @if(auth()->user()->role->name == 'manager')
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('documents/create') ? 'active' : '') }}" href="{{ url('documents/create') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-collection text-success"></i>
          </div>
          <span class="nav-link-text ms-1">Tambah Dokumen</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('documents') ? 'active' : '') }}" href="{{ url('documents') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-single-copy-04 text-info"></i>
          </div>
          <span class="nav-link-text ms-1">Semua Dokumen</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('approvals') ? 'active' : '') }}" href="{{ url('approvals') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-check-bold text-warning"></i>
          </div>
          <span class="nav-link-text ms-1">Approval Dokumen</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('approval-history') ? 'active' : '') }}" href="{{ url('approval-history') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-time-alarm text-info"></i>
          </div>
          <span class="nav-link-text ms-1">History Approval</span>
        </a>
      </li>
      @endif

      <!-- Menu untuk Section Head -->
      @if(auth()->user()->role->name == 'section_head')
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('approvals') ? 'active' : '') }}" href="{{ url('approvals') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-check-bold text-warning"></i>
          </div>
          <span class="nav-link-text ms-1">Approval Dokumen</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('approval-history') ? 'active' : '') }}" href="{{ url('approval-history') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-time-alarm text-info"></i>
          </div>
          <span class="nav-link-text ms-1">History Approval</span>
        </a>
      </li>
      @endif

      <!-- Menu untuk Dept Head -->
      @if(auth()->user()->role->name == 'depthead')
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('documents/create') ? 'active' : '') }}" href="{{ url('documents/create') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-collection text-success"></i>
          </div>
          <span class="nav-link-text ms-1">Tambah Dokumen</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('documents') ? 'active' : '') }}" href="{{ url('documents') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-single-copy-04 text-info"></i>
          </div>
          <span class="nav-link-text ms-1">Semua Dokumen</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('approvals') ? 'active' : '') }}" href="{{ url('approvals') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-check-bold text-warning"></i>
          </div>
          <span class="nav-link-text ms-1">Approval Dokumen</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('approval-history') ? 'active' : '') }}" href="{{ url('approval-history') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-time-alarm text-info"></i>
          </div>
          <span class="nav-link-text ms-1">History Approval</span>
        </a>
      </li>
      @endif

      <!-- Menu untuk Admin -->
      @if(auth()->user()->role->name == 'admin')
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('documents/create') ? 'active' : '') }}" href="{{ url('documents/create') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-collection text-success"></i>
          </div>
          <span class="nav-link-text ms-1">Tambah Dokumen</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('documents') ? 'active' : '') }}" href="{{ url('documents') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-single-copy-04 text-info"></i>
          </div>
          <span class="nav-link-text ms-1">Semua Dokumen</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('approvals') ? 'active' : '') }}" href="{{ url('approvals') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-check-bold text-warning"></i>
          </div>
          <span class="nav-link-text ms-1">Approval Dokumen</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('approval-history') ? 'active' : '') }}" href="{{ url('approval-history') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-time-alarm text-info"></i>
          </div>
          <span class="nav-link-text ms-1">History Approval</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('users') && !Request::is('users/create') ? 'active' : '') }}" href="{{ url('users') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-settings-gear-65 text-secondary"></i>
          </div>
          <span class="nav-link-text ms-1">User Management</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('users/create') ? 'active' : '') }}" href="{{ url('users/create') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-single-02 text-primary"></i>
          </div>
          <span class="nav-link-text ms-1">Tambah User</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('divisions') ? 'active' : '') }}" href="{{ url('divisions') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-building text-warning"></i>
          </div>
          <span class="nav-link-text ms-1">Manajemen Divisi</span>
        </a>
      </li>
      @endif

      <li class="nav-item mt-3">
        <a class="nav-link" href="{{ url('/logout') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-button-power text-danger"></i>
          </div>
          <span class="nav-link-text ms-1">Logout</span>
        </a>
      </li>
    </ul>
  </div>
  
</aside>
