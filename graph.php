<!DOCTYPE html>
<html>
<head>
	<title>Graphzapp</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="icon" href="http://graphzapp.com/favicon.png">
</head>
<body onload="init()" onresize="adjustForResize()">
	<?php include "compiler/translate.php";?>
	<?php include "header.php"; ?>
	<div class="body_container">
	<div class="left_col">
		<div class="well">
		<form id="xy_input" action="graph.php" method="get">
			<div class="line <?php if ($err_x != 0) {echo "tooltip";} ?>">
				<span>x(t) = </span>
				<input type="text" name="x-value" class="equation_input large" value="<?php echo($input_x);?>" onfocus="showTooltip('tooltip1')" onfocusout="hideTooltip('tooltip1')">
				<div class="tooltip_wrapper">
					<div class="tooltip_text" id="tooltip1">
						<?php echo(is_null($report_x)?"":$report_x->get_reason()) ?>
					</div>
				</div>
			</div>
			<div class="line <?php if ($err_y != 0) {echo "tooltip";} ?>">
				<span>y(t) =</span>
				<input type="text" name="y-value" class="equation_input large" value="<?php echo($input_y);?>" onfocus="showTooltip('tooltip2')" onfocusout="hideTooltip('tooltip2')">
				<div class="tooltip_wrapper">
					<div class="tooltip_text" id="tooltip2">
						<?php echo(is_null($report_y)?"":$report_y->get_reason()) ?>
					</div>
				</div>
			</div>
			<div id="t_range">
				<span id="t_min_container">
					<span class="small">t from </span>
					<input id="tmin" class="small_input" type="text" name="t-min" value="<?php echo($input_tmin);?>">
				</span>
				<span id="t_max_container">
					<span class="small"> to </span>
					<input id="tmax" class="small_input" type="text" name="t-max" value="<?php echo($input_tmax);?>">
				</span>
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
