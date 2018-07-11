<?php
	include "report.php";
	include "lexer.php";
	include "parser.php";
	include "imports.php";
	include "codegen.php";

	// undefined for all inputs
	const UNDEF = "[function(t,k){return NaN;}]";

	// translates an expression
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

	// inputs a range using min and max values in the form
	function input_range($min_name, $max_name, $default_min, $default_max) {
		$default_range = $default_max - $default_min;
		if(isset($_GET[$min_name],$_GET[$max_name])) {
			// get t values
			$input_min = $_GET[$min_name];
			$input_max = $_GET[$max_name];

			// make sure both are numbers
			if (is_numeric($input_min) || is_numeric($input_max)) {
				if(!is_numeric($input_min)) { $input_min = ((float) $input_max) - $default_range; }
				if(!is_numeric($input_max)) { $input_max = ((float) $input_min) + $default_range; }
			} else {
				$input_min = $default_min;
				$input_max = $default_max;
			}

		} else {
			$input_min = $default_min;
			$input_max = $default_max;
		}

		return array('min' => $input_min, 'max' => $input_max);
	}

	// creates a functional equation 
	function input_func($y_name, &$imports, &$eqn) {
		if(isset($_GET[$y_name])) {
			// get user inputs
			$eqn['input_y'] = $_GET[$y_name];

			// check that input was enterred
			if ($eqn['input_y']) {

				// initialize the static classes
				GraphzappLexer::init("x",["k"]);
				GraphzappImports::init();

				// translate
				$eqn['err_y'] = translate($eqn['input_y'], $eqn['report_y'], $res);

				if (!$eqn['err_y']) {
					// get imports
					$imports = array_merge($imports, GraphzappImports::get());

					// set equation
					$eqn['y'] = $res;
				}
			}
		}
	}

	// creates a parametric equation
	function input_parametric($x_name, $y_name, &$imports, &$eqn) {
		if(isset($_GET[$x_name],$_GET[$y_name])) {
			// get user inputs
			$eqn['input_x'] = $_GET[$x_name];
			$eqn['input_y'] = $_GET[$y_name];

			// check that input was enterred
			if ($eqn['input_x'] || $eqn['input_y']) {
				// fill in other empty input as appropriate
				if (!$eqn['input_x']) {$eqn['input_x'] = "t";}
				if (!$eqn['input_y']) {$eqn['input_y'] = "t";}

				// initialize the static classes
				GraphzappLexer::init("t",["k"]);
				GraphzappImports::init();

				// translate both
				$eqn['err_x'] = translate($eqn['input_x'], $eqn['report_x'], $x_res);
				$eqn['err_y'] = translate($eqn['input_y'], $eqn['report_y'], $y_res);

				if (!$eqn['err_x'] && !$eqn['err_y']) {
					// get imports
					$imports = GraphzappImports::get();

					// set equations
					$eqn['x'] = $x_res;
					$eqn['y'] = $y_res;
				}
			}
		}

		$eqn['t_range'] = input_range("t-min", "t-max", -10, 10);
	}

	// creates a polar equation
	function input_polar($r_name, &$imports, &$eqn) {
		if(isset($_GET[$r_name])) {
			// get user input
			$eqn['input_y'] = $_GET[$r_name];

			// check that input was enterred
			if ($eqn['input_y']) {
				// initialize the static classes
				GraphzappLexer::init("t",["k"]);
				GraphzappImports::init();

				// translate
				$eqn['err_y'] = translate($eqn['input_y'], $eqn['report_y'], $y_res);

				if (!$eqn['err_y']) {
					// get imports
					$imports = GraphzappImports::get();

					// set equations
					$eqn['y'] = $y_res;
				}
			}
		}

		$eqn['t_range'] = input_range("t-min", "t-max", 0, 2*pi());
		// $eqn['t_range']['min'] *= pi()/180;
		// $eqn['t_range']['max'] *= pi()/180;
	}


	// initialize equation
	$eqn = array();
	if (isset($_GET['mode'])) {
		$mode = $eqn['mode'] = $_GET['mode'];
	} else {
		$mode = $eqn['mode'] = 'functional';
	}

	// initialize outputs
	$imports = array();
	$eqn['x'] = $eqn['y'] = UNDEF;
	$eqn['err_x'] = $eqn['err_y'] = 0;
	$eqn['report_x'] = $eqn['report_y'] = NULL;
	$eqn['input_x'] = $eqn['input_y'] = "";
	$eqn['t_range'] = ['min' => -10.0, 'max' => 10.0];

	switch ($eqn['mode']) {
		case 'functional':
			input_func('y-value', $imports, $eqn);
			break;

		case 'parametric':
			input_parametric('x-value', 'y-value', $imports, $eqn);
			break;

		case 'polar':
			input_polar('y-value', $imports, $eqn);
			break;
	}

	$k_range = input_range("k-min", "k-max", -10, 10);
?>
