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
			"log" => [2, "Math.log(a1)/Math.log(a0);"],
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

		// error reporting
		static $report;

		// initializes the array of imported functions
		public static function init() {
			static::$imports = array();
		}

		// checks for errors in ids and arguments,
		// imports functions, replaces constants
		public static function import(&$ast) {
			$type = $ast['type'];

			switch ($type) {
				case 'Expr':
					return self::import($ast['tree']);

				case 'Binop':
					$left = self::import($ast['left']);
					$right = self::import($ast['right']);
					return $left && $right;

				case 'Unop':
					return self::import($ast['operand']);

				case 'Const':
					$id = $ast['id'];
					if(array_key_exists($id, static::$constant_list)) {
						$ast['value'] = static::$constant_list[$id];
						return true;
					} else {
						// unknown constant
						$reason = "unknown constant ".$id;
						static::$report = new Report($reason, -1);
						return false;
					}

				case 'Func':
					$id = $ast["id"];
					$argv = $ast["params"];
					$argc = count($argv);

					if(array_key_exists($id, static::$function_list)) {
						$func = static::$function_list[$id];
						if($argc === $func[0]) {
							static::$imports[$id] = $func;

							// recurse on parameters
							for ($j = 0; $j < count($argv); $j++) {
								if(self::import($argv[$j]) === false) {
									return false;
								}
								$ast["params"][$j] = $argv[$j];
							}
							return true;

						} else {
							// wrong number of arguments
							$reason = $id."() has wrong number of arguments";
							static::$report = new Report($reason, -1);
							return false;
						}
					} else {
						// unknown function
						$reason = "unknown function ".$id;
						static::$report = new Report($reason, -1);
						return false;
					}
				
				default:
					return true;
			}
		}

		// // checks for errors in ids and arguments,
		// // imports functions, replaces constants
		// public static function import(&$tokens) {
		// 	for ($i = 0; $i < count($tokens); $i++) { 
		// 		$name = $tokens[$i]["name"];

		// 		switch ($name) {
		// 			case "T_ID":
		// 				$match = $tokens[$i]["match"];
		// 				if(array_key_exists($match, static::$constant_list)) {
		// 					$tokens[$i]["match"] = static::$constant_list[$match];
		// 					//echo "<p>".static::$constant_list[$match]."</p>";
		// 					break;
		// 				}

		// 				// unknown constant
		// 				$reason = "unknown constant ".$match;
		// 				$offset = $tokens[$i]["start"];
		// 				static::$report = new Report($reason, $offset);
		// 				return false;

		// 			case "T_CALL":
		// 				$id = $tokens[$i]["id"];
		// 				$argv = $tokens[$i]["params"];
		// 				$argc = count($argv);

		// 				if(array_key_exists($id, static::$function_list)) {
		// 					$func = static::$function_list[$id];
		// 					if($argc === $func[0]) {
		// 						static::$imports[$id] = $func;

		// 						// recurse on parameters
		// 						$success = true;
		// 						for ($j = 0; $j < count($argv); $j++) {
		// 							$success &= self::import($argv[$j]);
		// 							$tokens[$i]["params"][$j] = $argv[$j];
		// 						}
		// 						if($success){break;}

		// 					} else {
		// 						// wrong number of arguments
		// 						$reason = $id."() has wrong number of arguments";
		// 						$offset = $tokens[$i]["start"];
		// 						static::$report = new Report($reason, $offset);
		// 					}
		// 				} else {
		// 					// unknown function
		// 					$reason = "unknown function ".$id;
		// 					$offset = $tokens[$i]["start"];
		// 					static::$report = new Report($reason, $offset);
		// 				}
						
		// 				// something went wrong
		// 				return false;
					
		// 			default:
		// 				break;
		// 		}
		// 	}
		// 	return true;
		// }

		// get the array f imported functions
		public static function get() {
			return static::$imports;
		}
	}
?>