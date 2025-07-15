
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
    <style>
      body { margin: 0; padding: 0; background-color: #d9dfeb}
      #map { width: 100%; height: 250px; }
      #coords { position: absolute; top: 10px; left: 10px; background: white; padding: 10px; font-size: 14px; z-index: 1; }
    </style>
    <style>
    body, html {
      height: 100%;
      margin: 0;
    }

    .container {
      display: flex;
      flex-direction: column;
      justify-content: center;  /* Vertical center */
      align-items: center;      /* Horizontal center */
      height: 100vh;            /* Full viewport height */
      text-align: center;
    }

    #reader {
      width: 300px;
      margin-top: 20px;
    }

    #result {
      margin-top: 10px;
      font-weight: bold;
    }
  </style>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-4 offset-md-4">
          <!-- test -->
            
            <div class="card" >
              <div class="card-body" style="padding-top: 30px; padding-bottom: 50px;">

                <?php if ($code == 1): ?>
                
                <img src="<?php echo base_url(); ?>assets/img/tick.PNG" style="max-width: 100px;"><br><br>
                <h4><?= $title ?></h4><br>

                <?php else: ?>

                <img src="<?php echo base_url(); ?>assets/img/cross.PNG" style="max-width: 100px;"><br><br>
                <h4><?= $title ?></h4><br>

                <?php endif ?>

                <?= $result ?>
                <br>
                <a class="btn btn-success mt-3" href="<?php echo base_url(); ?>">Back to Home</a>
              </div>
            </div>

        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

  </body>
</html>
