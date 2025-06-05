<?php 
echo view('layouts/header.php');
echo view('layouts/sidebar.php');
?>

<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0"><?= $title ?></h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      <div class="row">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"> Edit <?= $title ?></h3>
          </div>

          <div class="card-body">

            <?php if (session('errors')) : ?>
              <div class="alert alert-danger">
                <ul>
                  <?php foreach (session('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <form action="<?= site_url("$table/" . $data[$primaryKey]) ?>" method="post" enctype="multipart/form-data">
              <?= csrf_field() ?>
              <input type="hidden" name="_method" value="PUT">

              <?php for ($i = 0; $i < count($field); $i++) :
                $type = $field[$i][0];
                $name = $field[$i][1];
                $value = old($name, $data[$name] ?? '');
                ?>
                <div class="row mb-3">
                  <label for="<?= $name ?>" class="col-sm-2 col-form-label"><?= esc($fieldName[$i]) ?></label>
                  <div class="col-sm-10">

                    <?php if (in_array($type, ['text', 'date', 'email', 'password'])): ?>
                      <input type="<?= $type ?>" class="form-control" id="<?= $name ?>" name="<?= $name ?>" value="<?= esc($value) ?>" />

                    <?php elseif ($type === 'file'): ?>
                      <?php if (!empty($value)): ?>
                        <div class="mb-2">
                          <img src="<?= base_url('uploads/' . $value) ?>" alt="Current File" style="max-height: 100px;">
                        </div>
                      <?php endif; ?>
                      <input type="file" class="form-control" id="<?= $name ?>" name="<?= $name ?>" />

                    <?php elseif ($type === 'textarea'): ?>
                      <textarea class="form-control" id="<?= $name ?>" name="<?= $name ?>"><?= esc($value) ?></textarea>

                    <?php elseif ($type === 'select'): ?>
                      <select class="form-control" id="<?= $name ?>" name="<?= $name ?>">
                        <?php foreach ($fieldOption[$i] as $option): ?>
                          <option value="<?= esc($option[0]) ?>" <?= $value == $option[0] ? 'selected' : '' ?>>
                            <?= esc($option[1]) ?>
                          </option>
                        <?php endforeach ?>
                      </select>
                    <?php endif ?>

                  </div>
                </div>
              <?php endfor ?>

              <a href="<?= site_url($table) ?>" class="btn btn-danger">Cancel</a>
              <button type="submit" class="btn btn-success float-end">Update</button>

            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php 
echo view('layouts/footer.php');
?>
