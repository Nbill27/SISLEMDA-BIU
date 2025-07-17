<footer class="py-4 bg-dark mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-center small">
            <div class="text-white">© 2025 SISLEMDA, All Rights Reserved</div>
        </div>
    </div>
</footer>
<!-- penutup div content -->
</div>
<!-- penutup div layout -->
</div>
<!-- Bootstrap JS (wajib) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="<?php echo base_url('assets/js/scripts.js'); ?>"></script>
<!-- js ganti peran -->
<script>
    let roleModal = new bootstrap.Modal(document.getElementById('roleModal'));

    function openRoleModal(el) {
        const roleName = el.getAttribute('data-role'); // nama_role untuk ditampilkan
        const kodeRole = el.getAttribute('data-kode-role'); // kode_role untuk proses backend

        // Tampilkan nama role di modal
        document.getElementById('roleName').innerText = roleName;

        // Set URL dengan kode_role
        const baseUrl = "<?= site_url('auth/set_role/') ?>";
        document.getElementById('confirmRoleBtn').setAttribute('href', baseUrl + kodeRole);

        // Tampilkan modal
        roleModal.show();
    }
</script>



</body>

</html>