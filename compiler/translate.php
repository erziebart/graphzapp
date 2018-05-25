<?php
	include "lexer.php";
	include "imports.php";
	include "mapper.php";

	// example user inputs
	$input_x = $_GET["x-value"];
	$input_y = $_GET["y-value"];

	// undefined for all inputs
	const UNDEF = "[function(n,t){return NaN;}]";

	// initialize outputs
	$err = "";
	$x = UNDEF;
	$y = UNDEF;
	$imports = array();

	// lex both inputs to generate tokens
	GraphzappLexer::init();
	$x_tok = GraphzappLexer::token($input_x);
	$y_tok = GraphzappLexer::token($input_y);

	// check for lexing error
	if ($x_tok === false || $y_tok === false) {
		$err = "Lexing Error";
		goto end;
	}

	// import functions and constants
	GraphzappImports::init();
	$sucess = GraphzappImports::import($x_tok) 
			&& GraphzappImports::import($y_tok);

	// check for a semantic error
	if($sucess === false) {
		$err = "Semantic Error";
		goto end;
	}

	// capture the imported function in a list
	$imports = GraphzappImports::get();

	// map these tokens to javascript expressions
	$x_res = GraphzappMapper::convert($x_tok);
	$y_res = GraphzappMapper::convert($y_tok);

	// check for mapping error
	if ($x_res === false || $y_res === false) {
		$err = "Mapping Error";
		goto end;
	}

	// assign valid results to output variables
	$x = $x_res;
	$y = $y_res;

	end:
		// echo($err."<br>");
		// echo($x."<br>");
		// echo($y."<br>");
		// print_r($imports);
		// echo("<br>");
?>

<script type="text/javascript">
	// error handling
	var _err = "<?php echo($err); ?>";

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
	
</script>