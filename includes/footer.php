<?php
/**
 * File: footer.php
 * Deskripsi: Template bagian bawah (penutup body, inisialisasi JS dan Library) yang digunakan di seluruh halaman.
 * Author: @faiqyr
 * Tanggal Dibuat: 2026-08-10
 * Versi: 1.0.0
 */
?>

        </div> <!-- End Main Content -->
    </div> <!-- End Row -->
</div> <!-- End Container -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('.datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            }
        });

        // SweetAlert Delete Confirmation Setup
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });
</script>

</body>
</html>
