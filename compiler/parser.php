<?php
	/* LALR(0) parser for the graphzapp equations */

	class GraphzappParser {
		/* context-free grammar being parsed */
		/*
		 * eqn -> eqn BINOP0 expr | expr
		 * expr -> expr PLUS term | expr MINUS term | term
		 * term -> term BINOP2 factor | term noneg_factor | factor
		 * factor -> value POWER factor | MINUS factor | NOT factor | value
		 * noneg_factor -> value POWER factor | NOT factor | value
		 * value -> LIT | VAR | call | LPAREN eqn RPAREN
		 * call -> CONST | FID RPAREN | FID actuals_list RPAREN
		 * actuals_list -> eqn | actual_list COMMA eqn
		 */

		/* Here is the same grammar with things relabeled for compactness */
		/*
		 * S -> S ; E | E
		 * E -> E + T | E - T | T
		 * T -> T * F | T N | F
		 * F -> V ^ F | - F | ! F | V
		 * N -> V ^ F | ! F | V
		 * V -> 1 | x | C | ( S )
		 * C -> c | [ ) | [ A )
		 * A -> S | A , S
		 */

		/* 
		Productions are labelled starting with their generating nonterminal.
		Productions with the same generating nonterminal are lettered alphabetically
		from left to right as they appear above. So, for example, the production
		T -> T * F is labelled Ta, the production T -> T N is labelled Tb, and so on.
		*/

		// token stack
		protected static $stack = array(["state"=>"START"]);

		// pops n elements from the stack
		protected static function pop_n($n) {
			$ret = array();
			for($i = 0; $i < $n; $i++) {
				$ret[] = array_pop(static::$stack);
			}
			return array_reverse($ret);
		}


		//////////////// REDUCERS FOR PROD RULES /////////////

		protected static function Ea() {
			$toks = static::pop_n(3);
			return array("name"=>"E", "match"=>$toks[0]['match']."+".$toks[2]['match']);
		}

		protected static function Eb() {
			$toks = static::pop_n(1);
			return array("name"=>"E", "match"=>$toks[0]['match']);
		}

		protected static function Ta() {
			$toks = static::pop_n(1);
			return array("name"=>"T", "match"=>$toks[0]['match']);
		}

		/////////////////////////////////////////////////////////////


		// 

		/* parse table */
		protected static $parse_table = array(

		// 	"START" => ["LIT"=>"s_Ta1", "E"=>"END_Ea1", "T"=>"Eb1"],
		// 	"END_Ea1" => ['$end'=>"a_", "PLUS"=>"s_Ea2"]
		// );
			/* 
			state names: For each production rule that has been progressed
			through by at least 1 token, the state name contains the production 
			label followed by an integer indicating how much the parser has 
			progressed through the rule. If there are multiple productions, then
			the state name has mutiple parts separated by underscores.

			The special names START and END represent 0 and 1 token of progress 
			through the top production rule S' -> S $end
			*/

			/* format is:
				<state_name> => [<token_name> => <action>, ... ]
				where the action is one of: shift, reduce, goto, accept, error

				shift: "s_<next_state>"

				reduce: "r_<production_label>"

				goto: "<next_state>"

				accept: "a_"

				error: "e_<handler>"
			*/

			"START" => ["LIT"=>"s_Ta1", "E"=>"END_Ea1", "T"=>"Eb1"],

			"END_Ea1" => ['$end'=>"a_", "PLUS"=>"s_Ea2"],

			"Eb1" => ['$end'=>"r_Eb", "PLUS"=>"r_Eb"],

			"Ta1" => ['$end'=>"r_Ta", "PLUS"=>"r_Ta"],

			"Ea2" => ["LIT"=>"s_Ta1", "T"=>"Ea3"],

			"Ea3" => ['$end'=>"r_Ea", "PLUS"=>"r_Ea"]
		);


		// parses the token list to generate the final expression
		public static function parse($tokens) {
			$tokens = array_reverse($tokens);
			//print_r(static::$parse_table);
			
			while(true) {
				$state = end(static::$stack)['state'];
				//print_r(end(static::$stack));
				$next_tok = end($tokens);
				$name = $next_tok['name'];

				if(isset(static::$parse_table[$state][$name])) {
					$action = static::$parse_table[$state][$name];
					$parts = explode("_", $action, 2);
					$type = $parts[0];
					$target = $parts[1];

					switch($type) {
						default:
						case 's': // shift
							array_pop($tokens);
							static::$stack[] = array("match" => $next_tok['match'], "state" => $target);
							break;

						case 'r': // reduce
							$nonterm = self::$target();
							$name = $nonterm['name'];
							$state = end(static::$stack)['state'];

							if(isset(static::$parse_table[$state][$name])) {
								$target = static::$parse_table[$state][$name];
								static::$stack[] = array("match" => $nonterm['match'], "state" => $target);
							} else {
								//print_r(static::$parse_table);
								//print_r(static::$stack);
								return "no action for ".$name." in state ".$state;
							}

							break;

						case 'a': // accept
							return end(static::$stack)['match'];
					}

				} else {
					//print_r(static::$stack);
					return "no action for ".$name." in state ".$state;
				}
			}
		}
	}
?>