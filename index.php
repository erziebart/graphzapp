<!DOCTYPE html>
<html>
<head>
	<title>Graphzapp</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body onload="draw()">
	<div id="header">
		<h1 id="logo">GraphZapp</h1>
	</div>
    <canvas id="canvas" width="500" height="500"></canvas>
	<?php	include "compiler/translate.php";?>
	<div id="right_col">
		<form id="xy_input" action="index.php" method="get">
			<div class="line">
				x=
				<input type="text" name="x-value" value="<?php echo($input_x);?>">
			</div>
			<div class="line">
				y=
				<input type="text" name="y-value" value="<?php echo($input_y);?>">
			</div>
			<input type="submit" value="Go!">
		</form>
		t = 
		<div id="t_value"></div>
		<div class="slider_container">
			<input id="t_slider"class="slider" oninput="adjustT()" type="range" min="0" max="1000" value="0">
			<div id="t_range">
				<div id="t_min_container">
					min:
					<input id="tmin" type="text" name="t-min" value="0.0">
				</div>
				<div id="t_max_container">
					max:
					<input id="tmax" type="text" name="t-max" value="10.0">
				</div>
				<input type="submit" value="Adjust Range" onclick="adjustRange()">
			</div>
		</div>
	</div>
  	<script type="text/javascript" src="frontend.js"></script>
  	<script type="text/javascript" src="grapher.js"></script>
</body>
</html>
