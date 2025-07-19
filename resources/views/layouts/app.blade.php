<!--
=========================================================
* Soft UI Dashboard - v1.0.3
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>

@if (\Request::is('rtl'))
    <html dir="rtl" lang="ar">
@else
    <html lang="en">
@endif

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @if (env('IS_DEMO'))
        <x-demo-metas></x-demo-metas>
    @endif

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <title>
        Manajemen Approval Dokumen
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.3') }}" rel="stylesheet" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom CSS for Profile Dropdown -->
    <style>
        .profile-dropdown .dropdown-menu {
            min-width: 280px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(-10px);
        }

        .profile-dropdown .dropdown-menu.show {
            opacity: 1;
            transform: translateY(0);
        }

        .profile-dropdown .dropdown-item {
            border-radius: 10px;
            margin: 2px 8px;
            transition: all 0.3s ease;
        }

        .profile-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        .profile-dropdown .dropdown-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .profile-dropdown .nav-link {
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .profile-dropdown .nav-link:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .profile-dropdown .fa-chevron-down {
            transition: transform 0.3s ease;
        }

        .profile-dropdown.show .fa-chevron-down {
            transform: rotate(180deg);
        }
        .card, .modal {
            z-index: 1300 !important;
        }
        /* Fix dropdown always on top of dashboard content */
        .navbar .dropdown-menu {
            z-index: 3000 !important;
        }
        .main-content, .container-fluid {
            overflow: visible !important;
            z-index: auto !important;
        }
    </style>
</head>

<body
    class="g-sidenav-show  bg-gray-100 {{ \Request::is('rtl') ? 'rtl' : (Request::is('virtual-reality') ? 'virtual-reality' : '') }} ">
    @auth
        @yield('auth')
    @endauth
    @guest
        @yield('guest')
    @endguest

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SweetAlert Component -->
    @include('components.sweet-alert')
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    @stack('rtl')
    @stack('dashboard')
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.0.3') }}"></script>

    <!-- Modal Inspect PDF (global) -->
    <div class="modal fade" id="inspectModal" tabindex="-1" aria-labelledby="inspectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-md-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inspectModalLabel">Preview File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="inspectModalBody"
                    style="min-height:70vh;display:flex;align-items:center;justify-content:center;">
                    <div class="text-center text-muted">Memuat preview dokumen...</div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showInspectModal(fileUrl, fileName) {
            var modal = new bootstrap.Modal(document.getElementById('inspectModal'));
            var body = document.getElementById('inspectModalBody');
            var title = document.getElementById('inspectModalLabel');
            title.innerText = 'Preview File: ' + fileName;
            body.innerHTML =
                `<iframe src="${fileUrl}#toolbar=1&navpanes=1&scrollbar=1" width="100%" height="600px" style="border:none;max-width:100%;"></iframe>`;
            modal.show();
        }
    </script>
    <!-- Custom JavaScript for Profile Dropdown -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile dropdown animation
            const profileDropdown = document.getElementById('profileDropdown');
            const dropdownMenu = profileDropdown?.nextElementSibling;

            if (profileDropdown && dropdownMenu) {
                profileDropdown.addEventListener('click', function() {
                    const isOpen = dropdownMenu.classList.contains('show');

                    if (!isOpen) {
                        // Add show class with animation
                        dropdownMenu.style.display = 'block';
                        dropdownMenu.style.opacity = '0';
                        dropdownMenu.style.transform = 'translateY(-10px)';

                        setTimeout(() => {
                            dropdownMenu.classList.add('show');
                            dropdownMenu.style.opacity = '1';
                            dropdownMenu.style.transform = 'translateY(0)';
                        }, 10);
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!profileDropdown.contains(event.target) && !dropdownMenu.contains(event.target)) {
                        dropdownMenu.classList.remove('show');
                        dropdownMenu.style.opacity = '0';
                        dropdownMenu.style.transform = 'translateY(-10px)';

                        setTimeout(() => {
                            dropdownMenu.style.display = 'none';
                        }, 200);
                    }
                });
            }
        });
    </script>
</body>

</html>
