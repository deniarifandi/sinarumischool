<!DOCTYPE html>
<html>
<head>
  <title>QR Code Scanner</title>
  <style>
    #reader {
      width: 300px;
      margin: auto;
    }
    #result {
      margin-top: 20px;
      font-weight: bold;
      text-align: center;
    }
  </style>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 style="text-align:center;">QR Code Scanner</h2>
      <div id="reader"></div>
      <div id="result">Scan a QR Code</div>
    </div>
  </div>
</div>
  
  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
  <script>
    function onScanSuccess(decodedText, decodedResult) {
      document.getElementById('result').innerText = `QR Code Data: ${decodedText}`;
      html5QrcodeScanner.clear();
    }

    function onScanFailure(error) {
      // Handle scan error if needed
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
      "reader", { fps: 10, qrbox: 250 }, false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
  

</body>
</html>
