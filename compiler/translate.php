<?php
	include "report.php";
	include "lexer.php";
	include "imports.php";
	include "mapper.php";

	// undefined for all inputs
	const UNDEF = "[function(n,t){return NaN;}]";

	function translate($expr,&$report,&$result) {
		// lexing
		$tok = GraphzappLexer::token($expr);
		if ($tok === false) {
			$report = GraphzappLexer::$report;
			return -1;
		}

		// imports
		if (!GraphzappImports::import($tok)) {
			$report = GraphzappImports::$report;
			return -2;
		}

		// mapping
		$result = GraphzappMapper::convert($tok);
		if ($result === false) {
			$report = GraphzappMapper::$report;
			return -3; 
		}

		return 0;
	}

	// initialize outputs
	//$input_x = $input_y = "";
	$x = $y = UNDEF;
	$err_x = $err_y = 0;
	$report_x = $report_y = NULL;
	$imports = array();

	if(isset($_GET["x-value"],$_GET["y-value"])) {
		// get user inputs
		$input_x = $_GET["x-value"];
		$input_y = $_GET["y-value"];

		// check that input was enterred
		if ($input_x || $input_y) {
			// fill in other empty input as appropriate
			if (!$input_x) {$input_x = "n";}
			if (!$input_y) {$input_y = "n";}

			// initialize the static classes
			GraphzappLexer::init();
			GraphzappImports::init();

			// translate both
			$err_x = translate($input_x, $report_x, $x_res);
			$err_y = translate($input_y, $report_y, $y_res);

			if (!$err_x && !$err_y) {
				// get imports
				$imports = GraphzappImports::get();

				// set the resulting functions
				$x = $x_res;
				$y = $y_res;
			}
		}
	} else {
		$input_x = $input_y = "";
	}
?>

<script type="text/javascript">
	// error handling
	var _err_x = <?php echo($err_x); ?>;
	var _reason_x = "<?php echo (is_null($report_x)?"":$report_x->get_reason());?>";
	var _err_y = <?php echo($err_y); ?>;
	var _reason_y = "<?php echo (is_null($report_y)?"":$report_y->get_reason());?>";

	// imported functions
	<?php
		foreach ($imports as $fname => $val) {
			$argc = $val[0];
			$impl = $val[1];

			$params = array();
			$evals = "";
			for ($i=0; $i < $argc; $i++) { 
				$params[] = "p".$i;
				$evals .= "a".$i."=eval(n,t,p".$i.");";
			}
			printf("function %s(n,t,%s){%s%s}\n\t", $fname, implode(",", $params), $evals, $impl);
		}
	?>

	// the functions
	var _x = <?php echo($x); ?>;
	var _y = <?php echo($y); ?>;

	// log error messages
	if (_err_x) {console.log("x: " + _reason_x);}
	if (_err_y) {console.log("y: " + _reason_y);}
	
</script>