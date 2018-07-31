<script>
<?php 
	printf("var y_func = '%s';\n", $eqn['mode'] == 'functional' ? $eqn['input_y'] : '');

	printf("var x_para = '%s';\n", $eqn['mode'] == 'parametric' ? $eqn['input_x'] : '');
	printf("var y_para = '%s';\n", $eqn['mode'] == 'parametric' ? $eqn['input_y'] : '');
	printf("var para_t_min = %s;\n", $eqn['mode'] == 'parametric' ? $eqn['t_min'] : -10.0);
	printf("var para_t_max = %s;\n", $eqn['mode'] == 'parametric' ? $eqn['t_max'] : 10.0);

	printf("var r_pol = '%s';\n", $eqn['mode'] == 'polar' ? $eqn['input_r'] : '');
	printf("var pol_t_min = %s;\n", $eqn['mode'] == 'polar' ? $eqn['theta_min'] : 0.0);
	printf("var pol_t_max = %s;\n", $eqn['mode'] == 'polar' ? $eqn['theta_max'] : 360.0);
?>

function eqn1Input(name, input, report) { return (
	'<input type="text" name="' + name + '" class="equation_input large" value="' + input + '" onfocus="showTooltip(\'tooltip1\')" onfocusout="hideTooltip(\'tooltip1\')">' +
	'<div class="tooltip_wrapper">' +
		'<div class="tooltip_text" id="tooltip1">' +
			report +
		'</div>' +
	'</div>');}

function eqn2Input(name, input, report) { return (
	'<input type="text" name="' + name + '" class="equation_input large" value="' + input + '" onfocus="showTooltip(\'tooltip2\')" onfocusout="hideTooltip(\'tooltip2\')">' +
	'<div class="tooltip_wrapper">' +
		'<div class="tooltip_text" id="tooltip2">' +
			report +
		'</div>' +
	'</div>');}

function tRange(min, max) { return (
	'<div class="t_range">' +
		'<span class="t_min_container">' +
			'<span class="small">t from </span>' +
			'<input id = "tmin"class="tmin small_input" type="text" name="t_min" value=' + min + '>' + 
		'</span>' +
		'<span id="t_max_container">' +
			'<span class="small"> to </span>' +
			'<input id = "tmax" class="tmax small_input" type="text" name="t_max" value=' + max + '>' +
		'</span>' +
	'</div>');}

function thetaRange(min, max) { return (
	'<div class="t_range">' +
		'<span class="t_min_container">' +
			'<span class="small">t from </span>' +
			'<input id = "tmin"class="tmin small_input" type="text" name="theta_min" value=' + min + '>' + 
		'</span>' +
		'<span id="t_max_container">' +
			'<span class="small"> to </span>' +
			'<input id = "tmax" class="tmax small_input" type="text" name="theta_max" value=' + max + '>' +
			'<span class="small"> deg </span>' +
		'</span>' +
		
	'</div>');}

function functionalForm() { return (
	'<div id="functional">' +
		'<div class="line <?php if ($eqn['mode'] == 'functional' && $eqn['err'] != 0) {echo "tooltip";} ?>">' +
			'<span>y = </span>' + 
			eqn1Input("input_y", y_func, "<?php echo(($eqn['mode'] == 'functional' && !is_null($eqn['report'])) ? $eqn['report']->get_reason() : '') ?>") + 
		'</div>' + 
	'</div>');}

function parametricForm() { return (
	'<div id="parametric">' +
		'<div class="line <?php if ($eqn['mode'] == 'parametric' && $eqn['err_x'] != 0) {echo "tooltip";} ?>">' +
			'<span>x(t) = </span>' +
			eqn1Input("input_x", x_para, "<?php echo(($eqn['mode'] == 'parametric' && !is_null($eqn['report_x'])) ? $eqn['report_x']->get_reason() : '') ?>") + 
		'</div>' + 
		'<div class="line <?php if ($eqn['mode'] == 'parametric' && $eqn['err_y'] != 0) {echo "tooltip";} ?>">' + 
			'<span>y(t) = </span>' + 
			eqn2Input("input_y", y_para, "<?php echo(($eqn['mode'] == 'parametric' && !is_null($eqn['report_y'])) ? $eqn['report_y']->get_reason() : '') ?>") + 
		'</div>' + 
		tRange(para_t_min, para_t_max) +
	'</div>');}

function polarForm() { return (			
	'<div id="polar">' +
		'<div class="line <?php if ($eqn['mode'] == 'polar' && $eqn['err'] != 0) {echo "tooltip";} ?>">' +
			'<span>r(t) = </span>' +
			eqn1Input("input_r", r_pol, "<?php echo(($eqn['mode'] == 'polar' && !is_null($eqn['report'])) ? $eqn['report']->get_reason() : '') ?>") + 
		'</div>' +
		thetaRange(pol_t_min, pol_t_max) +
	'</div>');}

var curMode = document.getElementById('mode_dropdown').value;
var newForm = getNewForm(curMode);
document.getElementById('changeable_form').innerHTML = newForm;

function getNewForm(newMode) {
	// create the new form
	if (newMode == 'functional') {
		return functionalForm();
	}
	else if (newMode == 'parametric') {
		return parametricForm();
	}
	else if (newMode == 'polar'){
		return polarForm();
	}
}

function changeMode(newMode) {
	// get the equation form
	var form = document.getElementById('eqn_input');
	var curForm = formToJSON(form.elements);

	// save changes on old form
	if (curMode == 'functional') {
		y_func = curForm['input_y'];
	} else if (curMode == 'parametric') {
		x_para = curForm['input_x'];
		y_para = curForm['input_y'];
		para_t_min = curForm['t_min'];
		para_t_max = curForm['t_max'];
	} else if (curMode == 'polar') {
		r_pol = curForm['input_r'];
		pol_t_min = curForm['theta_min'];
		pol_t_max = curForm['theta_max'];
	}

	// put the new form in the document
	document.getElementById('changeable_form').innerHTML = getNewForm(newMode);
	curMode = newMode;
}
</script>