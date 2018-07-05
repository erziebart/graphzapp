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
	var eqn = new GraphzappEquation();

	// error handling
	eqn.err_x = <?php echo($err_x); ?>;
	eqn.reason_x = "<?php echo (is_null($report_x)?"":$report_x->get_reason());?>";
	eqn.err_y = <?php echo($err_y); ?>;
	eqn.reason_y = "<?php echo (is_null($report_y)?"":$report_y->get_reason());?>";

	// the functions
	eqn.x = function(t,k){return <?php echo($x); ?>;};
	eqn.y = function(t,k){return <?php echo($y); ?>;};

	// range
	eqn.tstart = <?php echo ($input_tmin) ?>;
	eqn.tstop = <?php echo ($input_tmax) ?>;

	// graph options -- still needs review and work!
	if (<?php echo (isset($_GET['options']) ? 'true': 'false'); ?>) {
		// should be something else here
		var grid = <?php echo(!isset($_POST['grids']) ? 'false' : 'true'); ?>;
		var axes = <?php echo(!isset($_POST['axes']) ? 'false' : 'true'); ?>;
		var numbers = <?php echo(!isset($_POST['labels']) ? 'false' : 'true'); ?>;
		var curveColor = "<?php echo($colors[$curvecolor][0]); ?>";
		var gridColor = "<?php echo($colors[$axescolor][1]); ?>";
		var axesColor = "<?php echo($colors[$axescolor][0]); ?>";
		var backgroundColor = "<?php echo($colors[$bgcolor][0]); ?>";
		
	} else {
		var grid = true;
		var axes = true;
		var numbers = true;
		var curveColor = "<?php echo($colors[$curvecolor][0]); ?>";
		var gridColor = "<?php echo($colors[$axescolor][1]); ?>";
		var axesColor = "<?php echo($colors[$axescolor][0]); ?>";
		var backgroundColor = "<?php echo($colors[$bgcolor][0]); ?>";
	}
	

</script>
<script type="text/javascript" src="scripts/slider.js"></script>
<script type="text/javascript" src="scripts/grapher.js"></script>
<script type="text/javascript" src="scripts/frontend.js"></script>
<script type="text/javascript" src="scripts/equationrange.js"></script>
