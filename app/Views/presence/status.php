
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
      body { margin: 0; padding: 0; }
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
        <div class="col-sm-12">
          <!-- test -->
            <div class="form-control">
            <a class="btn btn-warning mt-3 float-end" href="<?php echo base_url(); ?>showform">Back</a><br><br>
            <h2 style="text-align:center;" class="mt-3">Attendance Check</h2>
            <form method="POST" action="<?= base_url() ?>savepresensi">
            <h4 class="mt-3"><input class="form-control" name="nama" value="<?php echo $_GET['id'] ?>" readonly></h4>
              
              <select class="form-select mt-3" name="status" id="status" required>
                <option value="1">Hadir</option>
                <option value="2">Ijin</option>
                <option value="3">Sakit</option>
                <option value="4">WFA</option>
              </select>
              <div class="row">
                <div class="col">
                  <input type="text" class="form-control mt-3" id="longitude" name="longitude" readonly>  
                </div>
                <div class="col">
                  <input type="text" class="form-control mt-3" id="latitude" name="latitude" readonly>  
                </div>
              </div>

              <div id="map" class="form-control mt-3"></div>
              <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
            </div>
              
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
  mapboxgl.accessToken = 'pk.eyJ1IjoiYXJpZmFuZGlkZW5pIiwiYSI6ImNsMzZvNXZxejEzbHAzY3FzcmpuNzNrbm0ifQ.-XX0gvG2ooyVnJvZZHg9Hg'; // Replace with your actual token

  const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [0, 0], // Temporary center before geolocation
    zoom: 14
  });

  // Get user location and center map
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(position => {
      const userLng = position.coords.longitude;
      const userLat = position.coords.latitude;
      
      document.getElementById('longitude').value = userLng;
      document.getElementById('latitude').value = userLat;

      // Center the map
      map.setCenter([userLng, userLat]);

      // Add a marker
      new mapboxgl.Marker()
        .setLngLat([userLng, userLat])
        .addTo(map);
    }, error => {
      alert('Location access denied or not available.');
      console.error(error);
    });
  } else {
    alert('Geolocation not supported by your browser.');
  }
</script>


  </body>
</html>
