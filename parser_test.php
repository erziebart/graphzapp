<!DOCTYPE html>
<html>
<head>
	<title>Parser Test</title>
	<?php  
		include "compiler/parser.php";

		$input_toks = array(
			array('name'=>'LIT', 'match'=>'1'),
			array('name'=>'PLUS', 'match'=>'+'),
			array('name'=>'LIT', 'match'=>'1'),
			array('name'=>'$end')
		);

		$result = GraphzappParser::parse($input_toks);
		if($result === false) {
			$result = "Not Accepted";
		}
	?>
</head>
<body>
	<p> <?php print($result) ?> </p>
</body>
</html>