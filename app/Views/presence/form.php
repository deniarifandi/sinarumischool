
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
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
<div><a href="<?php echo base_url(); ?>" class="btn btn-primary">back</a></div>
<h2 style="text-align:center;">Attendance Check</h2>
<div id="reader"></div>
<div id="result">Scan a QR Code</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
  function onScanSuccess(decodedText, decodedResult) {
    document.getElementById('result').innerText = `QR Code Data: ${decodedText}`;
    window.location.href = `<?php echo base_url(); ?>showstatus?id=+${decodedText}`;
    html5QrcodeScanner.clear();
  }

  function onScanFailure(error) {
// Handle scan error if needed
  }

  const html5QrCode = new Html5Qrcode("reader");

  Html5Qrcode.getCameras().then(cameras => {
    if (cameras && cameras.length) {
      html5QrCode.start(
        { facingMode: "environment" }, // back camera on mobile
        {
          fps: 60,    // Scans per second
          qrbox: 350  // Size of the scan box
        },
        onScanSuccess
      );
    }
  }).catch(err => {
    console.error("Camera error: ", err);
  });
</script>
</body>
</html>
