<?php
	include "report.php";
	include "lexer.php";
	include "parser.php";
	include "imports.php";
	include "codegen.php";

	// undefined for all inputs
	const UNDEF = "[function(t,k){return NaN;}]";

	function translate($expr,&$report,&$result) {
		// lexing
		$tok = GraphzappLexer::token($expr,1);
		if ($tok === false) {
			$report = GraphzappLexer::$report;
			return -1;
		}

		$tok[] = array('name'=>'$end', 'start'=>strlen($expr)+1, 'end'=>strlen($expr)+1);

		// parsing
		GraphzappParser::init();
		$ast = GraphzappParser::parse($tok);
		if($ast === false) {
			$report = GraphzappParser::$report;
			return -2;
		}

		// imports
		if (!GraphzappImports::import($ast)) {
			$report = GraphzappImports::$report;
			return -3;
		}

		// mapping
		$result = GraphzappCodegen::codegen($ast);

		return 0;
	}

	// initialize outputs
	//$input_x = $input_y = "";
	$x = $y = UNDEF;
	$err_x = $err_y = 0;
	$report_x = $report_y = NULL;
	$mode = 'functional';
	$imports = array();

	if(isset($_GET["mode"])) {
		$mode = $_GET["mode"];
	}

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
			GraphzappLexer::init(["t","k"]);
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

	if(isset($_GET["k-min"],$_GET["k-max"])) {
		// get t values
		$input_kmin = $_GET["k-min"];
		$input_kmax = $_GET["k-max"];

		// make sure both are numbers
		if (is_numeric($input_kmin) || is_numeric($input_kmax)) {
			if(!is_numeric($input_kmin)) { $input_kmin = ((float) $input_kmax) - 20; }
			if(!is_numeric($input_kmax)) { $input_kmax = ((float) $input_kmin) + 20; }
		} else {
			$input_kmin = "-10.0";
			$input_kmax = "10.0";
		}

	} else {
		$input_kmin = "-10.0";
		$input_kmax = "10.0";
	}
?>
