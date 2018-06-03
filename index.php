<!DOCTYPE html>
<html>
<head>
	<title>Graphzapp</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body onload="init()">
	<?php include "compiler/translate.php";?>
	<div class="gradient" id="header">
		<h1 id="logo">GraphZapp</h1>
	</div>
	<div class="body_container">
	<div class="left_col">
		<div class="well">
		<form id="xy_input" action="index.php" method="get">
			<div class="line">
				<span>x = </span>
				<input type="text" name="x-value" class="equation_input large" value="<?php echo($input_x);?>">
			</div>
			<div class="line">
				<span>y = </span>
				<input type="text" name="y-value" class="equation_input large" value="<?php echo($input_y);?>">
			</div>
		</form>
	</div>
	<div class="well">
		<div class="line">
		<div class="badge small">
			t = 
			<div id="t_value"></div>
		</div>
		<div class="slider_container">
			<input id="t_slider" class="slider" oninput="adjustT()" type="range" min="0" max="1000" value="0">
			<div id="t_range">
				<div id="t_min_container">
					<span class="small">min:</span>
					<input id="tmin" class="small_input" type="text" name="t-min" value="0.0">
				</div>
				<div id="t_max_container">
					<span class="small">max:</span>
					<input id="tmax" class="small_input" type="text" name="t-max" value="10.0">
				</div>
				<input class="gray_gradient hover range" type="submit" value="Adjust Range" onclick="adjustRange()">
			</div>
		</div>
		</div>
		<hr>
				<p style="text-align: left;">Also try playing with these fun graphs:</p>
		<div class="flex">
			<ul>
			<a href="http://www.graphzapp.com/index.php?x-value=n&y-value=t*n%5E2">
				<li>Parabola</li>
			</a></li>
			<a href="http://www.graphzapp.com/index.php?x-value=3*%28.5%2Bcos%283n%29%29%28cos%28n%29%29%28cos%28t%29%5E2+%2B+.5%29&y-value=3*%28.5%2Bcos%283n%29%29%28sin%28n%29%29%28sin%28t%29%5E2%29">
				<li>Butterfly</li>
			</a>
			<a href="http://www.graphzapp.com/index.php?x-value=cos%28n%29%285+%2B+5cos%28n%2Bt%29%29&y-value=sin%28n%29%285+%2B+5cos%28n-t%29%29">
				<li>Swirling Heart</li>
			</a>
			</ul>
			<ul>
			<a href="http://www.graphzapp.com/index.php?x-value=%283-1*sin%28t%29%5E2%29%28sin%28n%29%29%281+-+1*cos%28n%29%29&y-value=%283-1*sin%28t%29%5E2%29%28cos%28n%29%29%281+-+1*cos%28n%29%29+%2B+5">
				<li>Heartbeat</li>
			</a>
			<a href="http://www.graphzapp.com/index.php?x-value=n&y-value=sin%28n+%2B+t%29+%2B+sin%28n*t%29">
				<li>Funky Waves</li>
			</a>
			<a href="http://www.graphzapp.com/index.php?x-value=10*%28cos%286n%29%29cos%28n%29cos%28t%29++-+10*%28cos%286n%29%29sin%28n%29sin%28t%29&y-value=5*%28cos%286n%29%29sin%28n%29cos%28t%29++%2B+5*%28cos%286n%29%29sin%28n%29cos%28t%29">
				<li>Flower Power</li>
			</a>
		</ul>
		</div>
	</div>
	</div>
	<div class="center_col">
			<input class="gradient center_button hover" form="xy_input" type="submit" value="▶" onsubmit="draw()">
	</div>
	<div class="right_col">
		<div class="well">
    		<canvas id="canvas" width="500" height="500"></canvas>
		</div>
	</div>
</div>

</div>
	<script type="text/javascript" src="grapher.js"></script>
  	<script type="text/javascript" src="frontend.js"></script>
</body>
</html>
