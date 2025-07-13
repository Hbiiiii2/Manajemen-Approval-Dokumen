@extends('layouts.app')

@section('auth')

    <link rel="stylesheet" href="{{ asset('assets/css/custom-ui.css') }}">

    @if(\Request::is('static-sign-up')) 
        @include('layouts.navbars.guest.nav')
        @yield('content')
        @include('layouts.footers.guest.footer')
    
    @elseif (\Request::is('static-sign-in')) 
        @include('layouts.navbars.guest.nav')
            @yield('content')
        @include('layouts.footers.guest.footer')
    
    @else
        @if (\Request::is('rtl'))  
            @include('layouts.navbars.auth.sidebar-rtl')
            <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg overflow-hidden">
                @include('layouts.navbars.auth.nav-rtl')
                <div class="container-fluid py-4">
                    @yield('content')
                    @include('layouts.footers.auth.footer')
                </div>
            </main>

        @elseif (\Request::is('profile'))  
            @include('layouts.navbars.auth.sidebar')
            <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
                @include('layouts.navbars.auth.nav')
                @yield('content')
            </div>

        @elseif (\Request::is('virtual-reality')) 
            @include('layouts.navbars.auth.nav')
            <div class="border-radius-xl mt-3 mx-3 position-relative" style="background-image: url('../assets/img/vr-bg.jpg') ; background-size: cover;">
                @include('layouts.navbars.auth.sidebar')
                <main class="main-content mt-1 border-radius-lg">
                    @yield('content')
                </main>
            </div>
            @include('layouts.footers.auth.footer')

        @else
            @include('layouts.navbars.auth.sidebar')
            <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg {{ (Request::is('rtl') ? 'overflow-hidden' : '') }}">
                @include('layouts.navbars.auth.nav')
                <div class="container-fluid py-4">
                    @yield('content')
                    @include('layouts.footers.auth.footer')
                </div>
            </main>
        @endif

    @endif

    @php
      $user = auth()->user();
      $unreadCount = $user->notifications()->whereNull('read_at')->count();
      $latestNotifications = $user->notifications()->latest()->take(8)->get();
    @endphp
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <ul class="navbar-nav justify-content-end ms-auto align-items-center">
          <!-- Notifikasi Bell -->
          <li class="nav-item dropdown me-3">
            <a class="nav-link position-relative" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-bell fa-lg"></i>
              @if($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.7em;">{{ $unreadCount }}</span>
              @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm p-0" aria-labelledby="notifDropdown" style="min-width:340px;max-width:400px;">
              <li class="dropdown-header bg-light fw-bold py-2 px-3">Notifikasi</li>
              @forelse($latestNotifications as $notif)
                <li>
                  <a href="{{ $notif->link ?? '#' }}" class="dropdown-item d-flex align-items-start gap-2 {{ $notif->isRead() ? '' : 'fw-bold' }}" style="white-space:normal;">
                    <span class="me-2">
                      @if($notif->type == 'success')<i class="fas fa-check-circle text-success"></i>@endif
                      @if($notif->type == 'warning')<i class="fas fa-exclamation-circle text-warning"></i>@endif
                      @if($notif->type == 'info')<i class="fas fa-info-circle text-info"></i>@endif
                      @if($notif->type == 'error')<i class="fas fa-times-circle text-danger"></i>@endif
                    </span>
                    <span>
                      <div>{{ $notif->title }}</div>
                      <small class="text-muted">{{ $notif->message }}</small>
                      <br>
                      <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                    </span>
                  </a>
                </li>
              @empty
                <li><span class="dropdown-item text-muted">Tidak ada notifikasi</span></li>
              @endforelse
            </ul>
          </li>
          <!-- ... existing user/profile dropdown ... -->
        </ul>
      </div>
    </nav>

    <style>
    .navbar .fa-bell {
      color: #16c7f9;
    }
    .navbar .badge.bg-danger {
      font-size: 0.7em;
      padding: 4px 7px;
    }
    .dropdown-menu-end {
      right: 0;
      left: auto;
      z-index: 1200 !important;
    }
    .dropdown-menu {
      z-index: 1200 !important;
    }
    .card, .modal {
      z-index: 1300 !important;
    }
    </style>
    <script>
    document.addEventListener('click', function(event) {
      var notifDropdown = document.getElementById('notifDropdown');
      var dropdownMenu = notifDropdown?.nextElementSibling;
      if (dropdownMenu && dropdownMenu.classList.contains('show')) {
        if (!notifDropdown.contains(event.target) && !dropdownMenu.contains(event.target)) {
          notifDropdown.click(); // close dropdown
        }
      }
    });
    </script>

@endsection