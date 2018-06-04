 <!DOCTYPE html>
<html>
<head>
	<title>Graphzapp Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php include "header.php"; ?>
	<div class="image_banner_wrapper">
		<div class="image_banner">
			<div class="overlay">
				<div class="overlay_text">
					<h2>Welcome to GraphZapp</h2>
					<p>An interactive graphing tool that lets you experiment, play and learn. Get started with our fun examples, or create a brand new graph from a function of your own!</p>
				</div>
				<a href="http://www.graphzapp.com/graph.php">
					<div class="center_button gradient">
						Get Started ▶
					</div>
				</a>
			</div>
		</div>
	</div>
	<div class="body_container">
		<div class="well blue_links">
			<h2 style="font-weight: normal">Get started with these fun graphs</h2>
			<hr>
			<div class="samples_container">
			<div class="sample">
				<a href="http://www.graphzapp.com/graph.php?x-value=t&y-value=k*t%5E2">
					<img src="Images/parabola.png">
					Parabola
				</a>
			</div>
			<div class="sample">
				<a href="http://www.graphzapp.com/graph.php?x-value=3*%28.5%2Bcos%283t%29%29cos%28t%29%28cos%28k%29%5E2+%2B+.5%29&y-value=3*%28.5%2Bcos%283t%29%29sin%28t%29%28sin%28k%29%5E2%29">
					<img src="Images/butterfly.png">
					Butterfly
				</a>
			</div>
			<div class="sample">
				<a href="http://www.graphzapp.com/graph.php?x-value=cos%28t%29%285+%2B+5cos%28t%2Bk%29%29&y-value=sin%28t%29%285+%2B+5cos%28t-k%29%29">
					<img src="Images/swirl.png">
					Swirling Heart
				</a>
			</div>
			<div class="sample">
				<a href="http://www.graphzapp.com/graph.php?x-value=%283-1*sin%28k%29%5E2%29*sin%28t%29%281+-+1*cos%28t%29%29&y-value=%283-1*sin%28k%29%5E2%29*cos%28t%29%281+-+1*cos%28t%29%29+%2B+5">
					<img src="Images/heartbeat.png">
					Heartbeat
				</a>
			</div>
			<div class="sample">
				<a href="http://www.graphzapp.com/graph.php?x-value=t&y-value=sin%28t+%2B+k%29+%2B+sin%28t*k%29">
					<img src="Images/waves.png">
					Funky Waves
				</a>
			</div>
			<div class="sample">
				<a href="http://www.graphzapp.com/graph.php?x-value=10*%28cos%286t%29%29cos%28t%29cos%28k%29++-+10*%28cos%286t%29%29sin%28t%29sin%28k%29&y-value=5*%28cos%286t%29%29sin%28t%29cos%28k%29++%2B+5*%28cos%286t%29%29sin%28t%29cos%28k%29">
					<img src="Images/flower.png">
					Flower Power
				</a>
			</div>
		</div>
	</div>
</div>
</body>