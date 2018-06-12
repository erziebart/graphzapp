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
	eqn.x = <?php echo($x); ?>;
	eqn.y = <?php echo($y); ?>;

	// range
	eqn.tstart = -10;
	eqn.tstop = 10;
</script>
<script type="text/javascript" src="scripts/slider.js"></script>
<script type="text/javascript" src="scripts/grapher.js"></script>
<script type="text/javascript" src="scripts/frontend.js"></script>
<script type="text/javascript" src="scripts/equationrange.js"></script>
