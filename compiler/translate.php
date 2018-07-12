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
	function input_range(&$array, $min_name, $max_name, $default_min, $default_max) {
		$default_range = $default_max - $default_min;
		if(isset($array[$min_name],$array[$max_name])) {
			// make sure both are numbers
			if (is_numeric($array[$min_name]) || is_numeric($array[$max_name])) {
				if(!is_numeric($array[$min_name])) { $array[$min_name] = ((float) $array[$max_name]) - $default_range; }
				if(!is_numeric($array[$max_name])) { $array[$max_name] = ((float) $array[$min_name]) + $default_range; }
			} else {
				$array[$min_name] = $default_min;
				$array[$max_name] = $default_max;
			}

		} else {
			$array[$min_name] = $default_min;
			$array[$max_name] = $default_max;
		}
	}

	// creates a functional equation 
	function input_func(&$eqn, &$imports) {
		if(isset($eqn['input_y']) && $eqn['input_y']) {
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
		} else {
			// default values
			$eqn['y'] = UNDEF;
			$eqn['err_y'] = 0;
			$eqn['report_y'] = NULL;
			$eqn['input_y'] = "";
		}
	}

	// creates a parametric equation
	function input_parametric(&$eqn, &$imports) {
		if(isset($eqn['input_x'],$eqn['input_y'])) {
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
			} else {
				// default values
				$eqn['x'] = $eqn['y'] = UNDEF;
				$eqn['err_x'] = $eqn['err_y'] = 0;
				$eqn['report_x'] = $eqn['report_y'] = NULL;
			}
		} else {
			// default values
			$eqn['input_x'] = $eqn['input_y'] = "";
			$eqn['x'] = $eqn['y'] = UNDEF;
			$eqn['err_x'] = $eqn['err_y'] = 0;
			$eqn['report_x'] = $eqn['report_y'] = NULL;
		}

		input_range($eqn, "t_min", "t_max", -10, 10);
		$eqn['t_range'] = array('min' => $eqn['t_min'], 'max' => $eqn['t_max']);
	}

	// creates a polar equation
	function input_polar(&$eqn, &$imports) {
		if(isset($eqn['input_y']) && $eqn['input_y']) {
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

		input_range($eqn, "t_min", "t_max", 0, 360);
		$eqn['t_range'] = array('min' => $eqn['t_min'], 'max' => $eqn['t_max']);
	}

	// initialize equation outputs
	if (isset($_GET['eqn'])) {
		$eqn = json_decode($_GET['eqn'], true, 3);
	} else {
		$eqn = array('mode' => 'functional');
	}
	$imports = array();


	// initialize equation
	// $eqn = array();
	// if (isset($_GET['mode'])) {
	// 	$mode = $eqn['mode'] = $_GET['mode'];
	// } else {
	// 	$mode = $eqn['mode'] = 'functional';
	// }

	// // initialize outputs
	// $imports = array();
	// $eqn['x'] = $eqn['y'] = UNDEF;
	// $eqn['err_x'] = $eqn['err_y'] = 0;
	// $eqn['report_x'] = $eqn['report_y'] = NULL;
	// $eqn['input_x'] = $eqn['input_y'] = $eqn['t_min'] = $eqn['t_max'] = "";
	// $eqn['t_range'] = ['min' => -10.0, 'max' => 10.0];



	// switch ($eqn['mode']) {
	// 	case 'functional':
	// 		input_func('y-value', $imports, $eqn);
	// 		break;

	// 	case 'parametric':
	// 		input_parametric('x-value', 'y-value', $imports, $eqn);
	// 		break;

	// 	case 'polar':
	// 		input_polar('y-value', $imports, $eqn);
	// 		break;
	// }

	// initialize slider outputs
	if (isset($_GET['slider'])) {
		$k_range = json_decode($_GET['slider'], true, 3);
		input_range($k_range, "min", "max", -10, 10);
	} else {
		$k_range = array("min" => -10, "max" => 10);
	}


	//$k_range = input_range("k-min", "k-max", -10, 10);

	// $eqn_json = json_decode($_GET['eqn'], true, 3);
	//var_dump($eqn);
?>
