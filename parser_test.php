<!DOCTYPE html>
<html>
<head>
	<title>Parser Test</title>
	<?php  
		include "compiler/report.php";
		include "compiler/parser.php";

		$input_toks = array(
			array('name'=>'LIT', 'match'=>'1', 'start'=>0),
			array('name'=>'PLUS', 'match'=>'+', 'start'=>1),
			array('name'=>'LIT', 'match'=>'2', 'start'=>2),
			array('name'=>'PLUS', 'match'=>'+', 'start'=>3),
			array('name'=>'LIT', 'match'=>'3', 'start'=>4),
			array('name'=>'$end', 'start'=>5)
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