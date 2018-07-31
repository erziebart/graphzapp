<!DOCTYPE html>
<html>
<head>
	<title>Graphzapp</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<!-- <body onload="init()" onresize="adjustForResize()" onmousedown="disableToolbar()" onmouseup="stopDrag(event);stopZoom();enableToolbar()" > -->
<body onload="init()" onresize="adjustForResize()" onmouseup="stopDrag(event);stopZoom();" >	
	<?php include "compiler/translate.php";?>
	<?php include "colors.php";?>
	<?php include "header.php"; ?>
	<div class="body_container">
	<div class="left_col">
		<div class="well">
		<form id="eqn_input" action="graph.php" method="get">
			<div class="mode_dropdown_wrapper">
				<span class="label"></span>
				<select id="mode_dropdown" class="large" name="mode" onchange="changeMode(this.value)">
  					<option value="functional" <?php if ($eqn['mode'] == "functional"){echo "selected";} ?>>Functional</option>
  					<option value="parametric" <?php if ($eqn['mode'] == "parametric"){echo "selected";}; ?>>Parametric</option>
  					<option value="polar" <?php if ($eqn['mode'] == "polar"){echo "selected";}; ?>>Polar</option>
				</select>
			</div>
			<div id="changeable_form"></div>
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
						<input id="kmin" class="small_input" type="text" name="k-min" value="<?php echo($k_range['min']);?>">
					</div>
					<div id="k_max_container">
						<span class="small">max:</span>
						<input id="kmax" class="small_input" type="text" name="k-max" value="<?php echo($k_range['max']);?>">
					</div>
					<!-- <input class="gray_gradient hover range" type="submit" value="Adjust Range" onclick="adjustRange()"> -->
				</div>
			</div>
		  </div>
		</form>
		</div>
	</div>
	<div class="center_col">
		<input class="gradient center_button hover" id="go_button" type="button" value="▶" onclick="submitForms()">
	</div>
	<div class="right_col">
		<div class="well">
			<div class="graph_wrapper">
    			<canvas id="canvas" width="500" height="500" onmousedown="startDrag(event)" onmousemove="doDrag(event)" onmouseenter="enterCanvas(event)" onmouseleave="leaveCanvas(event)"></canvas>
				<div class="toolbar_overlay">
    				<ul>
    					<li><img src="Images/zoom-in.png" onmousedown="onPressPlus(event);" onmouseleave="stopZoom();"></li>
    					<li><img src="Images/delete-searching.png" onmousedown="onPressMinus(event);" onmouseleave="stopZoom();"></li>
    					<li><img src="Images/gun-pointer.png" onmousedown="toOrigin()"></li>
    					<!-- <li><img src="Images/icon.png"></li> -->
    				</ul>
    			</div>
    		</div>
    		<div class="graph_options">
    		<div class="graph_options_header">
    			<span>Graph Options</span>
    			<span class="small">▼</span>
    		</div>
    		<form id="graph_options" class="graph_options_body" action="graph.php" method="post">
    			<div class="checkbox_wrapper">
    				<span class="small"><input id="grid_checkbox" type="checkbox" name="grids" onclick="toggleShowGrids()" checked>
    				<label for="grid_checkbox">Draw grid</label></span>
    				<span class="small"><input id="axes_checkbox" type="checkbox" name="axes" onclick="toggleShowAxes()" checked>
    				<label for="axes_checkbox">Draw axes</label></span>
    				<span class="small"><input id="numbers_checkbox" type="checkbox" name="labels" onclick="toggleShowLabels()" checked>
    				<label for="numbers_checkbox">Draw numbers</label></span>
    			</div>
    			<div class="dropdowns_flex">
    			<div class="dropdown_wrapper">
    				<p class="small">Curve color:</p>
    				<?php generate_dropdown(3,$curvecolor,"changeCurveColor"); ?>
    				<input id="input3" type="hidden" name="curvecolor" value="<?php echo($curvecolor); ?>">
    			</div>
    			<div class="dropdown_wrapper">
    				<p class="small">Background color:</p>
    				<?php generate_dropdown(1,$bgcolor,"changeBackground"); ?>
    				<input id="input1" type="hidden" name="bgcolor" value="<?php echo($bgcolor); ?>">
    			</div>
    			<div class="dropdown_wrapper">
    				<p class="small">Axes color:</p>
    				<?php generate_dropdown(2,$axescolor,"changeAxesColor"); ?>
    				<input id="input2" type="hidden" name="axescolor" value="<?php echo($axescolor); ?>">
    			</div>
    		</form>
    		</div>
		</div>
		<div class="credit"><span>Icons made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></span></div>
	</div>
	</div>
</div>
<script src="scripts/styling.js"></script>
<?php include "scripts/scripts.php" ?>
</body>
</html>
