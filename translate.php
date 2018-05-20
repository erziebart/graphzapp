<?php
	include "lexer.php";
	include "semant.php";
	include "mapper.php";

	// example user inputs
	$input_x = "5sin(n)";
	$input_y = "5cos(n)";

	// undefined for all inputs
	const UNDEF = "[function(n,t){return NaN;}]";

	// initialize outputs
	$err = "";
	$x = UNDEF;
	$y = UNDEF;

	// lex both inputs to generate tokens
	GraphzappLexer::init();
	$x_tok = GraphzappLexer::token($input_x);
	$y_tok = GraphzappLexer::token($input_y);

	// check for lexing error
	if ($x_tok === false || $y_tok === false) {
		$err = "Lexing Error";
		goto end;
	}

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
		echo($err."<br>");
		echo($x."<br>");
		echo($y."<br>");
?>

<script type="text/javascript">
	var _err = "<?php echo($err); ?>";
	var _x = <?php echo($x); ?>;
	var _y = <?php echo($y); ?>;

	// will evaluate the value of x or y given the current n and t
	// sample usage: cur_y = eval(0,1,_y);
	function eval(n,t,fn) {
		for (var i = 0; i < fn.length; i++) {
			var val = fn[i](n,t);
			if (isFinite(val) && !isNaN(val)) {
				if (val === true) {
					return 1;
				} else if(val === false) {
					return 0;
				} else {
					return val;
				}
			}
		}
		return NaN;
	}
</script>