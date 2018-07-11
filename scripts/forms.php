<script>
var xInput = 
	'<input type="text" name="x-value" class="equation_input large" value="<?php echo($eqn['input_x']);?>" onfocus="showTooltip(\'tooltip1\')" onfocusout="hideTooltip(\'tooltip1\')">' +
	'<div class="tooltip_wrapper">' +
		'<div class="tooltip_text" id="tooltip1">' +
			'"<?php echo(is_null($eqn['report_x'])?"":$eqn['report_x']->get_reason()) ?>"' +
		'</div>' +
	'</div>';

var yInput = 
	'<input type="text" name="y-value" class="equation_input large" value="<?php echo($eqn['input_y']);?>" onfocus="showTooltip(\'tooltip2\')" onfocusout="hideTooltip(\'tooltip2\')">' +
	'<div class="tooltip_wrapper">' +
		'<div class="tooltip_text" id="tooltip2">' +
			'"<?php echo(is_null($eqn['report_y'])?"":$eqn['report_y']->get_reason()) ?>"' +
		'</div>' +
	'</div>';

var tRange = 
	'<div class="t_range">' +
		'<span class="t_min_container">' +
			'<span class="small">t from </span>' +
			'<input id = "tmin"class="tmin small_input" type="text" name="t-min" value="<?php echo($eqn['t_range']['min']);?>">' +
		'</span>' +
		'<span id="t_max_container">' +
			'<span class="small"> to </span>' +
			'<input id = "tmax" class="tmax small_input" type="text" name="t-max" value="<?php echo($eqn['t_range']['max']);?>">' +
		'</span>' +
	'</div>';

var functionalForm = 
	'<div id="functional">' +
		'<div class="line <?php if ($eqn['err_y'] != 0) {echo "tooltip";} ?>">' +
			'<span>y = </span>' + 
			yInput + 
		'</div>' + 
	'</div>';

var parametricForm = 
	'<div id="parametric">' +
		'<div class="line <?php if ($eqn['err_x'] != 0) {echo "tooltip";} ?>">' +
			'<span>x(t) = </span>' +
			xInput + 
		'</div>' + 
		'<div class="line <?php if ($eqn['err_y'] != 0) {echo "tooltip";} ?>">' + 
			'<span>y(t) = </span>' + 
			yInput +
		'</div>' + 
		tRange +
	'</div>';

var polarForm = 			
	'<div id="polar">' +
		'<div class="line <?php if ($eqn['err_y'] != 0) {echo "tooltip";} ?>">' +
			'<span>r(t) = </span>' +
			yInput +
		'</div>' +
		tRange + 
	'</div>';

changeMode(document.getElementById('mode_dropdown').value);

function changeMode(newMode){
	var newForm;
	if (newMode == 'functional') {
		newForm = functionalForm;
	}
	else if (newMode == 'parametric') {
		newForm = parametricForm;
	}
	else if (newMode == 'polar'){
		newForm = polarForm;
	}
	document.getElementById('changeable_form').innerHTML = newForm;
}
</script>


