<!DOCTYPE html>
<html>
<head>
	<title>Test Expressions</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php 
		include "compiler/report.php";
		include "compiler/lexer.php";
		include "compiler/imports.php";
		include "compiler/mapper.php";

		$expr = "[function(t,k){return NaN;}]";
		$err_test = 0;
		$report_test = null;
		$tok = array();
		$imports = array();

		if (isset($_GET['test_expr'])) {
			// get input
			$input_expr = $_GET['test_expr'];

			if($input_expr) {
				// initialize the static classes
				GraphzappLexer::init();
				GraphzappImports::init();

				// lexing
				$tok = GraphzappLexer::token($input_expr, 1);
				if ($tok === false) {
					$tok = array();
					$report_test = GraphzappLexer::$report;
					$err_test = -1;
					goto end;
				}

				// imports
				if (!GraphzappImports::import($tok)) {
					$report_test = GraphzappImports::$report;
					$err_test = -2;
					goto end;
				}

				// mapping
				$test_result = GraphzappMapper::convert($tok);
				if ($test_result === false) {
					$report_test = GraphzappMapper::$report;
					$err_test = -3; 
					goto end;
				}

				end:

				if (!$err_test) {
					// get imports
					$imports = GraphzappImports::get();

					// set the resulting functions
					$expr = $test_result;
				}
			}
		} else {
			$input_expr = "";
		}
	?>
	<?php include "header.php"; ?>
	<div class="well">
		<form id="expr_input" action="test.php" method="get">
			<div class="line <?php if ($err_test != 0) {echo "error";} ?>">
				<span>expr = </span>
				<input type="text" name="test_expr" class="equation_input large" value="<?php echo($input_expr);?>">
				<?php
					if ($err_test != 0) { echo "
						<span class=\"tooltip\">❌
							<span class=\"tooltip_text\">".(is_null($report_test)?"":$report_test->get_reason())."</span>
						</span>"
					;}
				?>
			</div>
			<input type="submit" value="GO!">
		</form>
	</div>
	<p> <?php 
		echo "tokens: <br>"; 
		foreach ($tok as $t) {
			print_r($t); 
			echo "<br>";
		} 
	?> </p>
	<br>
	<p> <?php
		echo "imports: ";
		foreach ($imports as $id => $func) {
			print_r($id); echo " ";
		}
	?> </p>
	<br>
	<p> <?php
		echo "function: ".$expr;
	?> </p>
</body>
</html>