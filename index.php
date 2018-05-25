<!DOCTYPE html>
<html>
<head><title>Graphzapp</title>
</head>
<body onload="init()">
  <canvas id="canvas" width="500" height="500"></canvas>
  <button onclick="update()">Update T</button>
  <button onclick="run()">Run T</button>
  <?php	include "compiler/translate.php"?>
  <script type="text/javascript" src="grapher.js"></script>
</body>
</html>
