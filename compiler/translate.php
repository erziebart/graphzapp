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
	function input_func(&$eqn) {
		if(isset($eqn['input_y']) && $eqn['input_y']) {
			// initialize the lexer
			GraphzappLexer::init("x",["k"]);

			// translate
			$eqn['err'] = translate($eqn['input_y'], $eqn['report'], $res);

			if (!$eqn['err']) {
				// set equation
				$eqn['y'] = $res;
			}
		} else {
			// default values
			$eqn['y'] = UNDEF;
			$eqn['err'] = 0;
			$eqn['report'] = NULL;
			$eqn['input_y'] = "";
		}
	}

	// creates a parametric equation
	function input_parametric(&$eqn) {
		if(isset($eqn['input_x'],$eqn['input_y'])) {
			// check that input was enterred
			if ($eqn['input_x'] || $eqn['input_y']) {
				// fill in other empty input as appropriate
				if (!$eqn['input_x']) {$eqn['input_x'] = "t";}
				if (!$eqn['input_y']) {$eqn['input_y'] = "t";}

				// initialize the lexer
				GraphzappLexer::init("t",["k"]);

				// translate both
				$eqn['err_x'] = translate($eqn['input_x'], $eqn['report_x'], $x_res);
				$eqn['err_y'] = translate($eqn['input_y'], $eqn['report_y'], $y_res);

				if (!$eqn['err_x'] && !$eqn['err_y']) {
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
	function input_polar(&$eqn) {
		if(isset($eqn['input_r']) && $eqn['input_r']) {
			// initialize the lexer
			GraphzappLexer::init("t",["k"]);

			// translate
			$eqn['err'] = translate($eqn['input_r'], $eqn['report'], $r_res);

			if (!$eqn['err']) {
				// set equations
				$eqn['r'] = $r_res;
			}
		}

		input_range($eqn, "theta_min", "theta_max", 0, 360);
		$eqn['theta_range'] = array('min' => $eqn['theta_min'], 'max' => $eqn['theta_max']);
	}

	// initialize equation outputs
	if (isset($_GET['eqn'])) {
		$eqn = json_decode($_GET['eqn'], true, 3);
	} else {
		$eqn = array('mode' => 'functional');
	}
	GraphzappImports::init();

	// initialize slider outputs
	if (isset($_GET['slider'])) {
		$k_range = json_decode($_GET['slider'], true, 3);
		input_range($k_range, "min", "max", -10, 10);
	} else {
		$k_range = array("min" => -10, "max" => 10);
	}

	// initialize graph options
	if (isset($_GET['graph'])) {
		$options = json_decode($_GET['graph'], true, 3);
	} else {
		$options = array('grids' => true, 'axes' => true, 'labels' => true,
						'curvecolor' => blue, 'bgcolor' => white, 'axescolor' => black);
	}
?>
