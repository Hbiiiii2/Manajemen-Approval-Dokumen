{{-- SweetAlert Component --}}
@if(session()->has('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            customClass: {
                popup: 'swal2-toast'
            }
        });
    </script>
@endif

@if(session()->has('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            timer: 4000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            customClass: {
                popup: 'swal2-toast'
            }
        });
    </script>
@endif

@if(session()->has('warning'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: '{{ session('warning') }}',
            timer: 3500,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            customClass: {
                popup: 'swal2-toast'
            }
        });
    </script>
@endif

@if(session()->has('info'))
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Informasi!',
            text: '{{ session('info') }}',
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            customClass: {
                popup: 'swal2-toast'
            }
        });
    </script>
@endif

{{-- SweetAlert Functions --}}
<script>
    // Function untuk SweetAlert Success
    function showSuccess(message, title = 'Berhasil!') {
        Swal.fire({
            icon: 'success',
            title: title,
            text: message,
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
        });
    }

    // Function untuk SweetAlert Error
    function showError(message, title = 'Error!') {
        Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            timer: 4000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
        });
    }

    // Function untuk SweetAlert Warning
    function showWarning(message, title = 'Peringatan!') {
        Swal.fire({
            icon: 'warning',
            title: title,
            text: message,
            timer: 3500,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
        });
    }

    // Function untuk SweetAlert Info
    function showInfo(message, title = 'Informasi!') {
        Swal.fire({
            icon: 'info',
            title: title,
            text: message,
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
        });
    }

    // Function untuk SweetAlert Confirmation
    function showConfirm(message, title = 'Konfirmasi', callback) {
        Swal.fire({
            title: title,
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
        });
    }

    // Function untuk SweetAlert Delete Confirmation
    function showDeleteConfirm(message = 'Apakah Anda yakin ingin menghapus item ini?', callback) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
        });
    }
</script> 