@php
    $user = auth()->user();
    $unreadCount = $user->notifications()->whereNull('read_at')->count();
    $latestNotifications = $user->notifications()->latest()->take(8)->get();
@endphp
<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
    navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                <li class="breadcrumb-item text-sm text-dark active text-capitalize" aria-current="page">
                    {{ str_replace('-', ' ', Request::path()) }}</li>
            </ol>
            <h6 class="font-weight-bolder mb-0 text-capitalize">{{ str_replace('-', ' ', Request::path()) }}</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">

            <ul class="navbar-nav  justify-content-end">
                <!-- Notifikasi Bell -->
                <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    <a class="nav-link position-relative" href="#" id="notifDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell fa-lg"></i>
                        @if ($unreadCount > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                style="font-size:0.7em;">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm p-0" aria-labelledby="notifDropdown"
                        style="min-width:340px;max-width:400px;">
                        <li class="dropdown-header bg-light fw-bold py-2 px-3">Notifikasi</li>
                        @forelse($latestNotifications as $notif)
                            <li>
                                <a href="{{ $notif->link ?? '#' }}"
                                    class="dropdown-item d-flex align-items-start gap-2 {{ $notif->isRead() ? '' : 'fw-bold' }}"
                                    style="white-space:normal;">
                                    <span class="me-2">
                                        @if ($notif->type == 'success')
                                            <i class="fas fa-check-circle text-success"></i>
                                        @endif
                                        @if ($notif->type == 'warning')
                                            <i class="fas fa-exclamation-circle text-warning"></i>
                                        @endif
                                        @if ($notif->type == 'info')
                                            <i class="fas fa-info-circle text-info"></i>
                                        @endif
                                        @if ($notif->type == 'error')
                                            <i class="fas fa-times-circle text-danger"></i>
                                        @endif
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
                <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="profileDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <div class="d-flex align-items-center me-2">
                            @if (auth()->user()->profile_photo)
                                <img src="{{ Storage::url(auth()->user()->profile_photo) }}" alt="Profile"
                                    class="avatar avatar-sm rounded-circle me-2" style="object-fit: cover;">
                            @else
                                <div class="avatar avatar-sm bg-gradient-secondary rounded-circle me-2">
                                    <i class="ni ni-single-02 text-white"></i>
                                </div>
                            @endif
                            <div class="d-flex flex-column">
                                <span class="text-sm font-weight-bold mb-0">{{ auth()->user()->name }}</span>
                                <span
                                    class="text-xs text-secondary">{{ ucfirst(str_replace('_', ' ', auth()->user()->role->name)) }}</span>
                                @if (auth()->user()->division)
                                    <span class="text-xs text-primary">{{ auth()->user()->division->name }}</span>
                                @endif
                            </div>
                            <i class="fa fa-chevron-down ms-2 text-xs"></i>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="profileDropdown">
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="{{ url('profile') }}">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <i class="ni ni-single-02 text-info me-3"></i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">Profile</span>
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            Lihat dan edit profil Anda
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item border-radius-md" href="{{ url('/logout') }}">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <i class="ni ni-button-power text-danger me-3"></i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">Logout</span>
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            Keluar dari sistem
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->

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

    .card,
    .modal {
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
