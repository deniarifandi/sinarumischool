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
</head>
<body>

  <h2 style="text-align:center;">QR Code Scanner</h2>
  <div id="reader"></div>
  <div id="result">Scan a QR Code</div>

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

</body>
</html>
