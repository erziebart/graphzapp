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

	// // lex both inputs to generate tokens
	// GraphzappLexer::init();
	// $x_tok = GraphzappLexer::token($input_x);
	// $y_tok = GraphzappLexer::token($input_y);

	// // check for lexing error
	// if ($x_tok === false || $y_tok === false) {
	// 	$err = "Lexing error";
	// 	$report = GraphzappLexer::$report;
	// 	goto end;
	// }

	// // import functions and constants
	// GraphzappImports::init();
	// $sucess = GraphzappImports::import($x_tok) 
	// 		&& GraphzappImports::import($y_tok);

	// // check for a semantic error
	// if($sucess === false) {
	// 	$err = "Semantic error";
	// 	$report = GraphzappImports::$report;
	// 	goto end;
	// }

	// // capture the imported function in a list
	// $imports = GraphzappImports::get();

	// // map these tokens to javascript expressions
	// $x_res = GraphzappMapper::convert($x_tok);
	// $y_res = GraphzappMapper::convert($y_tok);

	// // check for mapping error
	// if ($x_res === false || $y_res === false) {
	// 	$err = "Mapping error";
	// 	$report = GraphzappMapper::$report;
	// 	goto end;
	// }

	// // assign valid results to output variables
	// $x = $x_res;
	// $y = $y_res;

	// end:
	// 	// echo($err."<br>");
	// 	// echo($x."<br>");
	// 	// echo($y."<br>");
	// 	// print_r($imports);
	// 	// echo("<br>");
	// 	// echo $report->get_reason();
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