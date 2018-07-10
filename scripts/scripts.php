<!-- This file contains imports of js files and php generated js code -->
<script type="text/javascript" src="scripts/equation.js"></script>
<script type="text/javascript">
	// imported functions
	<?php
		foreach ($imports as $fname => $val) {
			$argc = $val[0];
			$impl = $val[1];

			$params = array();
			$evals = "";
			for ($i=0; $i < $argc; $i++) {
				$params[] = "p".$i;
				$evals .= "a".$i."=eval(t,k,p".$i.");";
			}
			printf("function %s(t,k,%s){%s%s}\n\t", $fname, implode(",", $params), $evals, $impl);
		}
	?>

	// used to evaluate powers
	function power(base,exp) {
		if(base == 0 && exp == 0) {
			return NaN;
		} else {
			return Math.pow(base, exp);
		}
	}

	// used to evaluate the functions
	function eval(t,k,fn) {
	    for(var i = 0; i < fn.length; i++) {
	        var val = fn[i](t,k);
	        if (isFinite(val) && !isNaN(val)) {
	            if (val === true) { return 1;}
	            else if(val === false) {return 0;}
	            else {return val;}
	        }
	    }
	    return NaN;
	}

	// equation object
	var x_eqn = function(t,k){return <?php echo($x); ?>;};
	var y_eqn = function(t,k){return <?php echo($y); ?>;};
	var t_start = <?php echo ($input_tmin) ?>;
	var t_stop = <?php echo ($input_tmax) ?>;
	//var eqn = new Functional(y_eqn);
	//var eqn = new Parametric(x_eqn, y_eqn, t_start, t_stop);
	var eqn = new Polar(y_eqn, t_start, t_stop);

	// graph options -- still needs review and work!
	if (<?php echo (isset($_GET['options']) ? 'true': 'false'); ?>) {
		// should be something else here
		var options = {
			grid: true,
			axes: true,
			numbers: true,
			curveColor: "#4D6F96",
			gridColor: "#FFFFFF",
			axesColor: "#E9E9E9",
			backgroundColor: "#000000"
		};
	} else {
		var options = {
			grid: true,
			axes: true,
			numbers: true,
			curveColor: "<?php echo($colors[$curvecolor][0]); ?>",
			gridColor: "<?php echo($colors[$axescolor][1]); ?>",
			axesColor: "<?php echo($colors[$axescolor][0]); ?>",
			backgroundColor: "<?php echo($colors[$bgcolor][0]); ?>"
		};
	}
	

</script>
<script type="text/javascript" src="scripts/slider.js"></script>
<script type="text/javascript" src="scripts/grapher.js"></script>
<script type="text/javascript" src="scripts/frontend.js"></script>
<?php include "scripts/forms.php" ?>
