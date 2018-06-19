<!DOCTYPE html>
<html>
<head>
	<title>Parser Test</title>
	<?php  
		include "compiler/report.php";
		include "compiler/lexer.php";
		include "compiler/parser.php";
		include "compiler/imports.php";
		include "compiler/codegen.php";

		$expr = '2cos(t;1,0)';
		GraphzappLexer::init();
		$input_toks = GraphzappLexer::token($expr,0);
		$input_toks[] = array('name'=>'$end', 'start'=>strlen($expr), 'end'=>strlen($expr));
		$ast = GraphzappParser::parse($input_toks);

		if($ast === false) {
			$report = GraphzappParser::$report;
			$result = "Not Accepted";
		}

		GraphzappImports::init();
		$success = GraphzappImports::import($ast);
		if($success == false) {
			$report = GraphzappParser::$report;
			$result = "Not Accepted";
		}

		$result = GraphzappCodegen::codegen($ast);
	?>
</head>
<body>
	<p> <?php print("f(t,k)=".$expr); ?> </p>
	<?php  

		if(isset($report)) {
			$reason = $report->get_reason();
			echo "<p> $reason </p>";
		}
	?>
	<p> <?php 
		echo "tokens: <br>"; 
		foreach ($input_toks as $t) {
			print_r($t); 
			echo "<br>";
		} 
	?> </p>
	<p> <?php 
		echo "ast: <br>";
		print_r($ast); 
	?> </p>
	<p> <?php 
		echo "result: <br>";
		print_r($result); 
	?> </p>
</body>
</html>