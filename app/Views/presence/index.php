<?php 
echo view('layouts/header.php');
?>

<main class="app-main">
  <div class="app-content">
    <div class="container-fluid">

      <div class="row">

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Presence Data</h3>
          </div>

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

            <table id="guruTable" class="display" style="width:100%;">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Presensi ID</th>
                  <th>Personel Name</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Long</th>
                  <th>Lat</th>
                  <th>Divisi</th>
                  <th>Jabatan</th>
                  <th>Status</th>
                  <?php if (session()->get('guru_id') == 0): ?>
                    <th>Action</th>
                  <?php endif ?>
                </tr>
              </thead>
            </table>

          </div>
        </div>

      </div>

    </div>
  </div>

</main>

<?php 
echo view('layouts/footer.php');
?>


<script>
$(document).ready(function () {

    $('#guruTable').DataTable({
        processing: true,
        serverSide: true,

        // ✅ SORT BY DATE RAW VALUE (index 3)
        order: [[3, 'desc']],

        ajax: {
            url: "<?= base_url('Presensidata/data') ?>",
            type: "POST"
        },

        columns: [

            // No
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },

            { data: 'presensidata_id' },
            { data: 'guru_nama' },

            // ✅ DATE column with raw sorting
            {
                data: 'date_formatted',
                render: function (data, type, row) {
                    return `<span data-order="${row.date_raw}">${row.date_formatted}</span>`;
                }
            },

            { data: 'time_formatted' },
            { data: 'longitude' },
            { data: 'latitude' },
            { data: 'semua_divisi' },
            { data: 'semua_jabatan' },

            {
                data: 'status',
                render: function (data) {
                    if (data == 1) return 'Hadir';
                    if (data == 2) return 'Ijin';
                    if (data == 3) return 'Sakit';
                    return '-';
                }
            },

            <?php if (session()->get('guru_id') == 0): ?>
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `<a href="<?= base_url(); ?>presensidata/editstatus/${row.presensidata_id}" class="btn btn-sm btn-primary">Edit Status</a>`;
                }
            }
            <?php endif ?>

        ]
    });

});
</script>
