  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-5">
    <div class="container">
      <div class="row">
        @if (!auth()->user() || \Request::is('static-sign-up')) 
          <div class="col-lg-8 mx-auto text-center mb-4 mt-2">
              <a href="https://github.com/Hbiiiii2" target="_blank" class="text-secondary me-xl-4 me-4">
                  <span class="text-lg fab fa-github" aria-hidden="true"></span>
              </a>
              <a href="https://instagram.com/hbiiiii2" target="_blank" class="text-secondary me-xl-4 me-4">
                  <span class="text-lg fab fa-instagram" aria-hidden="true"></span>
              </a>
              <a href="https://github.com/Hbiiiii2" target="_blank" class="text-secondary me-xl-4 me-4">
                  <span class="text-lg fas fa-code" aria-hidden="true"></span>
              </a>
              <a href="https://github.com/Hbiiiii2" target="_blank" class="text-secondary me-xl-4 me-4">
                  <span class="text-lg fas fa-user" aria-hidden="true"></span>
              </a>
              <a href="https://github.com/Hbiiiii2" target="_blank" class="text-secondary me-xl-4 me-4">
                  <span class="text-lg fas fa-envelope" aria-hidden="true"></span>
              </a>
          </div>
        @endif
      </div>
      @if (!auth()->user() || \Request::is('static-sign-up')) 
        <div class="row">
          <div class="col-8 mx-auto text-center mt-1">
            <p class="mb-0 text-secondary">
              Copyright © <script>
                document.write(new Date().getFullYear())
              </script> Sistem Manajemen Approval Dokumen by 
              <a style="color: #252f40;" href="https://github.com/Hbiiiii2" class="font-weight-bold ml-1" target="_blank">Hbiiiii2</a>.
            </p>
          </div>
        </div>
      @endif
      <!-- Watermark Section -->
      <div class="row mt-3">
        <div class="col-12 text-center">
          <div class="watermark text-sm text-muted">
            <span>Developed by </span>
            <a href="https://github.com/Hbiiiii2" class="font-weight-bold text-primary" target="_blank" style="text-decoration: none;">
              <i class="fab fa-github me-1"></i>Hbiiiii2
            </a>
            <span class="mx-2">|</span>
            <a href="https://instagram.com/hbiiiii2" class="font-weight-bold text-danger" target="_blank" style="text-decoration: none;">
              <i class="fab fa-instagram me-1"></i>hbiiiii2
            </a>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
