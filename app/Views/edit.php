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

              <form action="<?= site_url($table . '/' . $data[$primaryKey]) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">

                <?php for ($i = 0; $i < count($field); $i++) :
                  $type = $field[$i][0];
                  $name = $field[$i][1];
                  $label = $fieldName[$i];
                  $value = old($name, $data[$name] ?? '');
                ?>
                  <div class="mb-3">
                    <label class="form-label"><?= esc($label) ?></label>
                    <input type="<?= esc($type) ?>" class="form-control" name="<?= esc($name) ?>" value="<?= esc($value) ?>">
                  </div>
                <?php endfor; ?>

                <a href="<?= site_url($table) ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary float-end">Update</button>
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
