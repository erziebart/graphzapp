<?php
	// performs semantic checks on the tokens from the lexer
	// namely checking that the id's are known and that function
	// calls pass the right number of arguments
	class GraphzappImports {
		protected static $function_list = array(
			// format is
			// <id> => [<argc>, <implementation>]
			"sqrt" => [1, "return Math.sqrt(a0);"],
			"exp" => [1, "return Math.exp(a0);"],
			"ln" => [1, "return Math.log(a0);"],
			"log" => [2, "return Math.log(a1)/Math.log(a0);"],
			"sin" => [1, "return Math.sin(a0);"],
			"cos" => [1, "return Math.cos(a0);"],
			"tan" => [1, "return Math.tan(a0);"],
			"cot" => [1, "return 1/Math.tan(a0);"],
			"sec" => [1, "return 1/Math.cos(a0);"],
			"csc" => [1, "return 1/Math.sin(a0);"],
			"arcsin" => [1, "return Math.asin(a0);"],
			"arccos" => [1, "return Math.acos(a0);"],
			"arctan" => [1, "return Math.atan(a0);"],
			"arccot" => [1, "return Math.atan(1/a0);"],
			"arcsec" => [1, "return Math.acos(1/a0);"],
			"arccsc" => [1, "return Math.asin(1/a0);"]
		);

		protected static $constant_list = array(
			// format is
			// <id> => <value>
			"e" => "Math.E",
			"pi" => "Math.PI",
			"tau" => "2*Math.PI"
		);

		protected static $imports;

		// initializes the array of imported functions
		public static function init() {
			static::$imports = array();
		}

		// checks for errors in ids and arguments,
		// imports functions, replaces constants
		public static function import(&$tokens) {
			for ($i = 0; $i < count($tokens); $i++) { 
				$name = $tokens[$i]["name"];

				switch ($name) {
					case "T_ID":
						$match = $tokens[$i]["match"];
						if(array_key_exists($match, static::$constant_list)) {
							$tokens[$i]["match"] = static::$constant_list[$match];
							break;
						}
						return false;

					case "T_CALL":
						$id = $tokens[$i]["id"];
						$argc = count($tokens[$i]["params"]);
						if(array_key_exists($id, static::$function_list)) {
							$func = static::$function_list[$id];
							if($argc === $func[0]) {
								static::$imports[$id] = $func;
								break;
							}
						}
						return false;
					
					default:
						break;
				}
			}
			return true;
		}

		// get the array f imported functions
		public static function get() {
			return static::$imports;
		}
	}
?>