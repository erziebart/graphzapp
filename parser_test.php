<!DOCTYPE html>
<html>
<head>
	<title>Parser Test</title>
	<?php  
		include "compiler/report.php";
		include "compiler/parser.php";

		$input_toks = array(
			array('name'=>'$lit', 'match'=>'4', 'start'=>0),
			array('name'=>'$plus', 'match'=>'+', 'start'=>1),
			array('name'=>'$lit', 'match'=>'2', 'start'=>2),
			array('name'=>'$minus', 'match'=>'-', 'start'=>3),
			array('name'=>'$lit', 'match'=>'3', 'start'=>4),
			array('name'=>'$binop0', 'match'=>';', 'start'=>5),
			array('name'=>'$lit', 'match'=>'8', 'start'=>6),
			array('name'=>'$binop2', 'match'=>'*', 'start'=>7),
			array('name'=>'$lit', 'match'=>'2', 'start'=>8),
			array('name'=>'$lit', 'match'=>'3', 'start'=>9),
			array('name'=>'$lit', 'match'=>'3', 'start'=>10),
			array('name'=>'$end', 'start'=>11)
		);

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
</body>
</html>