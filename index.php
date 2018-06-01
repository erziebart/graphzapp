<!DOCTYPE html>
<html>
<head>
	<title>Graphzapp</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body onload="init()">
	<div id="header">
		<h1 id="logo">GraphZapp</h1>
	</div>
	<div id="left_col">
		<form id="xy_input" action="index.php" method="get">
			<div class="line">
				x=
				<input type="text" name="x-value" class="equation-input" value="<?php echo($input_x);?>">
			</div>
			<div class="line">
				y=
				<input type="text" name="y-value" class="equation-input" value="<?php echo($input_y);?>">
			</div>
			<input type="submit" value="Go!" onsubmit="draw()">
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
		<h3>Also try playing with these fun graphs:</h3>
		<div class="indented">
			<ul>
			<li><a href="http://www.graphzapp.com/index.php?x-value=n&y-value=t*n%5E2">
				Parabola
			</a></li>
			<li><a href="http://www.graphzapp.com/index.php?x-value=3*%28.5%2Bcos%283n%29%29%28cos%28n%29%29%28cos%28t%29%5E2+%2B+.5%29&y-value=3*%28.5%2Bcos%283n%29%29%28sin%28n%29%29%28sin%28t%29%5E2%29">
				Butterfly
			</a></li>
			<li><a href="http://www.graphzapp.com/index.php?x-value=cos%28n%29%285+%2B+5cos%28n%2Bt%29%29&y-value=sin%28n%29%285+%2B+5cos%28n-t%29%29">
				Swirling Heart
			</a></li>
		</ul>
		<ul>
			<li><a href="http://www.graphzapp.com/index.php?x-value=%283-1*sin%28t%29%5E2%29%28sin%28n%29%29%281+-+1*cos%28n%29%29&y-value=%283-1*sin%28t%29%5E2%29%28cos%28n%29%29%281+-+1*cos%28n%29%29+%2B+5">
				Heartbeat
			</a></li>
			<li><a href="http://www.graphzapp.com/index.php?x-value=n&y-value=sin%28n+%2B+t%29+%2B+sin%28n*t%29">
				Funky Waves
			</a></li>
			<li><a href="http://www.graphzapp.com/index.php?x-value=10*%28cos%286n%29%29cos%28n%29cos%28t%29++-+10*%28cos%286n%29%29sin%28n%29sin%28t%29&y-value=5*%28cos%286n%29%29sin%28n%29cos%28t%29++%2B+5*%28cos%286n%29%29sin%28n%29cos%28t%29">
				Flower Power
			</a></li>
		</ul>
		</div>
	</div>
    <canvas id="canvas" width="500" height="500"></canvas>
	<?php include "compiler/translate.php";?>
	<script type="text/javascript" src="grapher.js"></script>
  	<script type="text/javascript" src="frontend.js"></script>
</body>
</html>
