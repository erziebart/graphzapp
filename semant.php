<?php
	// performs semantic checks on the tokens from the lexer
	// namely checking that the id's are known and that function
	// calls pass the right number of arguments
	class GraphzappSemant {
		protected static $function_list = array(
			// format is
			// <name> => [<argc>, <implementation>]
		);

		protected static $constant_list = array(

		);
	}
?>