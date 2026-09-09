<?php if (session()->getFlashdata('success')): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '<?= esc(session()->getFlashdata('success')) ?>',
    timer: 2000,
    showConfirmButton: false
});
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '<?= esc(session()->getFlashdata('error')) ?>'
});
</script>
<?php endif; ?>
