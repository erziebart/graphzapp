<!DOCTYPE html>
<html>
<head>
	<title>Parser Test</title>
	<?php  
		include "compiler/report.php";
		include "compiler/lexer.php";
		include "compiler/parser.php";

		$expr = 'cos(t^2)/(pi cos(t)>0.5) ; sin(t^2)';
		GraphzappLexer::init();
		$input_toks = GraphzappLexer::token($expr,0);
		$input_toks[] = array('name'=>'$end', 'start'=>strlen($expr), 'end'=>strlen($expr));
		$result = GraphzappParser::parse($input_toks);

		if($result === false) {
			$report = GraphzappParser::$report;
			$result = "Not Accepted";
		}
	?>
</head>
<body>
	<p> <?php print($result) ?> </p>
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
</body>
</html>