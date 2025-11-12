    <?php 
    echo view('layouts/header.php');

    ?>

    <!--begin::App Main-->
    <main class="app-main">
      <!--begin::App Content Header-->

      <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
          <!-- Info boxes -->
          <div class="row">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Add Subjek ?></h3>
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

            <form action="<?= base_url('unit/save') ?>" method="post" class="p-3 border rounded shadow-sm">

              <!-- Subjek ID -->
              <div class="mb-3">
                <label for="subjek_id" class="form-label">Subjek ID</label>
                <input type="text" class="form-control" id="subjek_id" name="subjek_id"
                       value="<?= esc($_GET['subjek_id'] ?? '') ?>" readonly>
              </div>

              <!-- Unit Nama -->
              <div class="mb-3">
                <label for="unit_nama" class="form-label">Nama Unit</label>
                <input type="text" class="form-control" id="unit_nama" name="unit_nama"
                       placeholder="Masukkan nama unit" required>
              </div>

              <!-- Tingkat -->
              <div class="mb-3">
                <label for="tingkat_id" class="form-label">Tingkat</label>
                <select class="form-select" id="tingkat_id" name="tingkat_id" required>
                  <option value="">-- Pilih Tingkat --</option>
                
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                </select>
              </div>

              <!-- Unit JP -->
              <div class="mb-3">
                <label for="unit_jp" class="form-label">Jumlah JP</label>
                <input type="number" class="form-control" id="unit_jp" name="unit_jp"
                       placeholder="Masukkan jumlah JP" required>
              </div>

              <!-- Submit Button -->
              <button type="submit" class="btn btn-primary">Simpan</button>
            </form>

              <!-- /.card-footer -->
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

<?php 
echo view('layouts/footer.php');
?>

