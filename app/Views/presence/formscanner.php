<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Attendance Check</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <style>
    body, html {
      height: 100%;
      margin: 0;
    }
    .container {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    
      text-align: center;
    }
    #result {
      margin-top: 10px;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="container">
    <div><br>
      <h2>Attendance Check</h2> </div>
    
      <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
   <form action="<?= base_url('savepresensidirect'); ?>" method="post" class="mt-3" style="max-width: 300px; width: 100%;">
      <div class="input-group">
        <input type="text" name="guru_id" class="form-control" placeholder="Enter code" required autofocus>
        <button class="btn btn-success" type="submit">Go</button>
      </div>
    </form>
    
    <div id="result">Please scan your ID Card</div>
  </div>

   <table id="guruTable" class="display">
                  <thead>
                      <tr>
                        <th>No.</th>
                        <th>Presensi id</th>
                        <th>Personel Name</th>
                        <th>Date</th>    
                        <th>Time</th>    
                        <th>Divisi</th>
                        <th>Jabatan</th>
                      </tr>
                  </thead>
              </table>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script>
    function submitCode() {
      const code = document.getElementById('codeInput').value.trim();
      if (code) {
        document.getElementById('result').innerText = `Code: ${code}`;
        window.location.href = `<?php echo base_url(); ?>getname?id=${encodeURIComponent(code)}`;
      } else {
        document.getElementById('result').innerText = "Code field cannot be empty.";
      }
    }
  </script>

  <script>
    $(document).ready(function () {
        $('#guruTable').DataTable({
            processing: true,
            serverSide: true,
             order: [[1, 'desc']],
            ajax: {
                url: "<?= base_url('Presensidata/data') ?>",
                type: "POST"
            },
            columns: [
                 {
                    data: null, // no actual field needed
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'presensidata_id' },
                { data: 'guru_nama' },
                { data: 'date_formatted' },
                { data: 'time_formatted' },
                { data: 'semua_divisi'},
                { data: 'semua_jabatan'}

            ]
        });

     
    });
    </script>

</body>
<a href="<?= base_url() ?>" class="btn btn-sm btn-warning" >back</a>
</html>
