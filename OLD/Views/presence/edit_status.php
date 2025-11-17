<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Status Presensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container py-4">
      <a href="<?= base_url() ?>Presensidata" class="btn btn-warning mb-3">Back</a>
      <h2 class="text-center mb-4">Edit Status Presensi</h2>

      <form method="post" action="<?= base_url() ?>presensidata/updatestatus">
        <input type="hidden" name="presensidata_id" value="<?= $data->presensidata_id ?>">

        <div class="mb-3">
          <label class="form-label">Guru ID</label>
          <input type="text" class="form-control bg-light" value="<?= $data->guru_id ?>" readonly>
        </div>

        <div class="mb-3">
          <label class="form-label">Tanggal Presensi</label>
          <input type="text" class="form-control bg-light" value="<?= $data->presensidata_tanggal ?>" readonly>
        </div>

        <div class="mb-3">
          <label class="form-label">Longitude</label>
          <input type="text" class="form-control bg-light" value="<?= $data->longitude ?>" readonly>
        </div>

        <div class="mb-3">
          <label class="form-label">Latitude</label>
          <input type="text" class="form-control bg-light" value="<?= $data->latitude ?>" readonly>
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select class="form-select" name="status" required>
            <option value="1" <?= $data->status == 1 ? 'selected' : '' ?>>Hadir</option>
            <option value="2" <?= $data->status == 2 ? 'selected' : '' ?>>Ijin</option>
            <option value="3" <?= $data->status == 3 ? 'selected' : '' ?>>Sakit</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Update Status</button>
      </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
