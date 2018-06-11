<!DOCTYPE html>
<html>
<head>
	<title>Graphzapp</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body onload="init()" onresize="adjustForResize()">
	<?php include "compiler/translate.php";?>
	<?php include "header.php"; ?>
	<div class="body_container">
	<div class="left_col">
		<div class="well">
		<form id="xy_input" action="graph.php" method="get">
			<div class="line <?php if ($err_x != 0) {echo "error";} ?>">
				<span>x(t) = </span>
				<input type="text" name="x-value" class="equation_input large" value="<?php echo($input_x);?>">
				<?php
					if ($err_x != 0) { echo "
						<span class=\"tooltip\">❌
							<span class=\"tooltip_text\">".(is_null($report_x)?"":$report_x->get_reason())."</span>
						</span>"
					;}
				?>
			</div>
			<div class="line <?php if ($err_y != 0) {echo "error";} ?>">
				<span>y(t) =</span>
				<input type="text" name="y-value" class="equation_input large" value="<?php echo($input_y);?>">
				<?php
					if ($err_y != 0) { echo "
						<span class=\"tooltip\">❌
							<span class=\"tooltip_text\">".(is_null($report_y)?"":$report_y->get_reason())."</span>
						</span>"
					;}
				?>
			</div>
		</form>
	</div>
	<div class="well" id="sliders">
		<div class="line">
		<div class="badge small">
			k = 
			<div id="k_value"></div>
		</div>
		<div class="slider_container">
			<input id="k_slider" class="slider" oninput="adjustValue()" type="range" min="0" max="1000" value="0">
			<div id="k_range">
				<div id="k_min_container">
					<span class="small">min:</span>
					<input id="kmin" class="small_input" type="text" name="k-min" value="0.0">
				</div>
				<div id="k_max_container">
					<span class="small">max:</span>
					<input id="kmax" class="small_input" type="text" name="k-max" value="10.0">
				</div>
				<input class="gray_gradient hover range" type="submit" value="Adjust Range" onclick="adjustRange()">
			</div>
		</div>
	  </div>
	</div>
	</div>
	<div class="center_col">
			<input class="gradient center_button hover" id="go_button" form="xy_input" type="submit" value="▶">
	</div>
	<div class="right_col">
		<div class="well">
    		<canvas id="canvas" width="500" height="500"></canvas>
		</div>
	</div>
</div>

</div>
<script src="scripts/styling.js"></script>
<?php include "scripts/scripts.php" ?>
</body>
</html>