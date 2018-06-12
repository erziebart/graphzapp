<?php
	include "report.php";
	include "lexer.php";
	include "imports.php";
	include "mapper.php";

	// undefined for all inputs
	const UNDEF = "[function(t,k){return NaN;}]";

	function translate($expr,&$report,&$result) {
		// lexing
		$tok = GraphzappLexer::token($expr,1);
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
			if (!$input_x) {$input_x = "t";}
			if (!$input_y) {$input_y = "t";}

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

	if(isset($_GET["t-min"],$_GET["t-max"])) {
		// get t values
		$input_tmin = $_GET["t-min"];
		$input_tmax = $_GET["t-max"];

		// make sure both are numbers
		if (is_numeric($input_tmin) || is_numeric($input_tmax)) {
			if(!is_numeric($input_tmin)) { $input_tmin = ((float) $input_tmax) - 20; }
			if(!is_numeric($input_tmax)) { $input_tmax = ((float) $input_tmin) + 20; }
		} else {
			$input_tmin = "-10.0";
			$input_tmax = "10.0";
		}

	} else {
		$input_tmin = "-10.0";
		$input_tmax = "10.0";
	}
?>
