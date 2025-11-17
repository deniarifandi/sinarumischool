    <?php 
    echo view('layouts/header.php');
    echo view('layouts/sidebar.php');
    ?>

    <!--begin::App Main-->
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-6"><h3 class="mb-0"></h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"></li>
              </ol>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div>

      <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
          <!-- Info boxes -->
          <div class="row">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Jabatan Manager</h3>
                 <a href="<?php echo base_url() ?>Gurujabatan" class="float-end btn btn-sm btn-primary">Back to List</a>
                <div class="card-tools">
                  <!-- <a href="" class="btn btn-primary">Add Student</a> -->
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">

               <?php if (session('errors')) : ?>
                <div style="color:red;">
                  <ul>
                    <?php foreach (session('errors') as $error) : ?>
                      <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php endif; ?>
              
              <!-- Guru Name (Read Only) -->
              <div class="mb-3">
                <label for="guruNama" class="form-label">Personel Name</label>
                <input 
                class="form-control" 
                type="text" 
                id="guruNama" 
                name="guru_nama" 
                value="<?php echo esc($data[0]->guru_nama); ?>" 
                readonly
                >
              </div>

              <label for="jabatan" class="form-label">Personel Jabatanon</label>
              <div class="mb-3">
                <input type="text" class="form-control" id="searchJabatan" placeholder="Search roles...">
              </div>

<!-- Switch List Container -->
<div style="max-height: 300px; overflow-y: auto;">
  <div class="row" id="jabatanContainer">
    <?php foreach ($jabatanList as $jabatan): ?>
      <div class="col-md-6 jabatan-item">
        <div class="form-check form-switch mb-2">
          <input 
          class="form-check-input" 
          type="checkbox" 
          role="switch" 
          id="switchJabatan<?= $jabatan->jabatan_id; ?>" 
          name="jabatan[]" 
          value="<?= $jabatan->jabatan_id ?>"
            <?= in_array($jabatan->jabatan_id, $assignedJabatan) ? 'checked' : '' ?>
          >
          <label class="form-check-label" for="switchJabatan<?= $jabatan->jabatan_id; ?>">
            <?= esc($jabatan->jabatan_nama); ?>
          </label>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

</div>
<!-- /.card -->

</div>
<!-- /.row -->
<!--end::Container-->
</div>
<!--end::App Content-->
</main>
<!--end::App Main-->
<!--begin::Footer-->
<div id="overlay" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(255,255,255,0.6); z-index:9999;">
    <div class="d-flex justify-content-center align-items-center" style="height:100%;">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
</div>
<?php 
echo view('layouts/footer.php');
?>

<script>
  document.getElementById('searchJabatan').addEventListener('input', function () {
    let keyword = this.value.toLowerCase();
    let items = document.querySelectorAll('.jabatan-item');

    items.forEach(function (item) {
      let label = item.querySelector('label').textContent.toLowerCase();
      if (label.includes(keyword)) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });
  });
</script>

<script>
document.querySelectorAll('.form-check-input').forEach(function(switchInput) {
    switchInput.addEventListener('change', function() {
        const guruId = <?= $data[0]->guru_id ?>;
        const jabatanId = this.value;
        const isChecked = this.checked ? 1 : 0;

        // setSwitchesDisabled(true);
        showOverlay(true);
        fetch('<?= base_url() ?>Gurujabatan/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>' // If using CSRF
            },
            body: JSON.stringify({
                guru_id: guruId,
                jabatan_id: jabatanId,
                status: isChecked
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
            // optionally show a success alert
        })
        .finally(() => showOverlay(false))
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update');
        });
    });
});
</script>

<script type="text/javascript">
 function showOverlay(state) {
    document.getElementById('overlay').style.display = state ? 'block' : 'none';
}
</script>