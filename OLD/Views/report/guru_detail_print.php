<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Guru - <?= $guru->guru_nama; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: #f9fafc;
      padding: 40px;
    }
    .profile-card {
      background: white;
      border: 1px solid #dee2e6;
      border-radius: 12px;
      max-width: 800px;
      margin: auto;
      padding: 30px 40px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #0d6efd;
    }
    table {
      width: 100%;
    }
    th {
      width: 35%;
      background: #f8f9fa;
      padding: 8px 12px;
      font-weight: 600;
      vertical-align: top;
    }
    td {
      padding: 8px 12px;
    }
    @media print {
      body {
        background: white;
        padding: 0;
      }
      .btn-print {
        display: none;
      }
      .profile-card {
        border: none;
        box-shadow: none;
      }
    }
  </style>
</head>

<body>
  <div class="profile-card">
   
  	<div class="d-flex justify-content-between align-items-center p-3 border-bottom bg-light">
	  <img src="<?= base_url() ?>/ykbm.jpg" alt="Left Logo" class="rounded-circle" style="width:60px; height:60px; object-fit:cover;">
	  <h2 class="fw-bold mb-0 text-center flex-grow-1">Recruitment Data Form</h2>
	  <img src="<?= base_url() ?>/ycpb.png" alt="Right Logo" class="rounded-circle" style="width:60px; height:60px; object-fit:cover;">
	</div>

    <table class="table table-bordered">

        <tr>
		  <th colspan="2" style="text-align:center; font-size:22px">Identitas</th>
		</tr>
      
      <!-- <tr><th>ID</th><td><?= $guru->guru_id; ?></td></tr> -->
      <tr><th>Nama</th><td><?= $guru->guru_nama ?: '-'; ?></td></tr>
      <tr><th>NIP</th><td><?= $guru->nip ?: '-'; ?></td></tr>
      <tr><th>NIK</th><td><?= $guru->nik ?: '-'; ?></td></tr>
      <tr><th>No. Telepon</th><td><?= $guru->phone ?: '-'; ?></td></tr>
      <tr><th>Alamat</th><td><?= $guru->address ?: '-'; ?></td></tr>

      <tr><th>No KKB</th><td><?= $guru->kkbnomor ?: '-'; ?></td></tr>
      <tr><th>Mulai KKB</th><td><?= $guru->kkbstart ?: '-'; ?></td></tr>

      <tr><th>BPJS Kesehatan</th><td><?= $guru->bpjskesehatan ?: '-'; ?></td></tr>
      <tr><th>BPJS Ketenagakerjaan</th><td><?= $guru->bpjsketenagakerjaan ?: '-'; ?></td></tr>

      <tr><th>Rekening BCA</th><td><?= $guru->bca ?: '-'; ?></td></tr>

      <tr><th>Tempat Lahir</th><td><?= $guru->placebirth ?: '-'; ?></td></tr>
      <tr><th>Tanggal Lahir</th><td><?= ($guru->datebirth && $guru->datebirth != '0000-00-00') ? date('d M Y', strtotime($guru->datebirth)) : '-'; ?></td></tr>
      <tr><th>Jenis Kelamin</th><td><?= $guru->gender ?: '-'; ?></td></tr>
      <tr><th>Agama</th><td><?= $guru->religion ?: '-'; ?></td></tr>
      <tr><th>Status Pernikahan</th><td><?= $guru->marital ?: '-'; ?></td></tr>

       <tr>
		  <th colspan="2" style="text-align:center; font-size:22px">Pendidikan</th>
		</tr>
      

      <tr><th>Pendidikan Terakhir</th><td><?= $guru->lasteducation ?: '-'; ?></td></tr>

        <tr>
		  <th colspan="2" style="text-align:center; font-size:22px">Training</th>
		</tr>
      <tr><th>Periode Pelatihan</th><td><?= $guru->trainingperiod ?: '-'; ?></td></tr>
      <tr><th>Tanggal Mulai Pelatihan</th><td><?= $guru->trainingstart ?: '-'; ?></td></tr>
      <tr><th>Divisi Pelatihan</th><td><?= $guru->trainingdivisi ?: '-'; ?></td></tr>
      <tr><th>Posisi Pelatihan</th><td><?= $guru->trainingposition ?: '-'; ?></td></tr>
      <tr><th>Pelatih</th><td><?= $guru->trainingtrainer ?: '-'; ?></td></tr>
      <tr><th>Diketahui Oleh</th><td><?= $guru->trainingmengetahui ?: '-'; ?></td></tr>
     
      <!-- <tr><th>Arsip</th><td><?= $guru->arsip ?: '-'; ?></td></tr> -->
     
      <tr><th>Tanggal Dibuat</th><td><?= ($guru->created_at && $guru->created_at != '0000-00-00 00:00:00') ? $guru->created_at : '-'; ?></td></tr>
      <tr><th>Terakhir Diperbarui</th><td><?= $guru->updated_at ?: '-'; ?></td></tr>
      <tr>
	    <th>Pasfoto</th>
	    <td>
	      <?php if (!empty($guru->pasfoto)): ?>
	        <img src="<?= base_url('uploads/' . $guru->pasfoto); ?>" 
	             alt="Pasfoto <?= $guru->guru_nama; ?>" 
	             style="width:120px; height:auto; border-radius:8px;">
	      <?php else: ?>
	        <span>-</span>
	      <?php endif; ?>
	    </td>
	  </tr>
    </table>

    <div class="text-center mt-3">
      <button class="btn btn-primary btn-print" onclick="window.print()">🖨️ Cetak Profil</button>
    </div>
  </div>
</body>
</html>
