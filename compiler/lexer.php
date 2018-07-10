<?php
	// Referenced: 
	// http://nitschinger.at/Writing-a-simple-lexer-in-PHP/

	// this is a class for lexing the input expression into 
	// tokens.
	class GraphzappLexer {
		// Handy regular expressions
		const ID = "[A-Za-z][\w]*";
		const JSLIT = "((\d+(\.\d*)?)|(\.\d+))((E|e)(\+|\-)?\d+)?";

		protected static $terminals;

		////////////////token generation actions/////////////////
		protected static function g_ignore($name, $matches, $offset) {
			return true;
		}

		protected static function g_generic($name, $matches, $offset) {
			$match = $matches[1];
			return array(
				"name" => $name,
				"match" => $match);
		}

		// for T_CALL
		protected static function g_call($name, $matches, $offset) {
			$id = $matches[2];

			return array(
				"name" => $name,
				"match" => $id);
		}
		/////////////////////////////////////////////////////////

		// error reporting
		static $report;

		// prepare regexes
		public static function init($vars) {
			$regex = "(".implode("|",$vars).")(?!\w)";

			static::$terminals = array(
				/* format is
					"<TOKEN_TYPE>"
					"<REGEX>"
					"<GENERATOR>" 
				*/

				array(
					'$white', 
					"\s+",
					"g_ignore"
				),
				array(
					'$var',
					$regex,
					"g_generic"
				),
				array(
					'$fid',
					"(".self::ID.")\(",
					"g_call"
				),
				array(
					'$const', 
					self::ID,
					"g_generic"
				),
				array(
					'$binop0', 
					";|&|\||==|!=|<=|>=|<|>",
					"g_generic"
				),
				array(
					'$plus', 
					"\+",
					"g_generic"
				),
				array(
					'$minus', 
					"\-",
					"g_generic"
				),
				array(
					'$binop2', 
					"\*|\/",
					"g_generic"
				),
				array(
					'$not', 
					"!",
					"g_generic"
				),
				array(
					'$power', 
					"\^",
					"g_generic"
				),
				array(
					'$lit', 
					"(?>".self::JSLIT.")(?![\.])",
					"g_generic"
				),
				array(
					'$lparen', 
					"\(",
					"g_generic"
				),
				array(
					'$rparen', 
					"\)",
					"g_generic"
				),
				array(
					'$comma',
					",",
					"g_generic"
				),
			);

			for ($i = 0; $i < count(static::$terminals); $i++) {
				$regex = static::$terminals[$i][1];
				static::$terminals[$i][1] = "/(".$regex.")/xA";
			}
		}

		// generate an array of tokens from a string
		public static function token($expr, $pos) {
			$tokens = array();
			$offset = 0;

			while ($offset < strlen($expr)) {
				$remaining = substr($expr, $offset);
				$start = $pos + $offset;
				$next_token = static::find_next($remaining, $pos, $offset);

				if($next_token === false) {
					// cannot parse this expression
					return false;
				}

				if ($next_token === true) {
					// ignoring it
					continue;
				}

				// set bounds and add to the token list
				$end = $pos + $offset - 1;
				$next_token['start'] = $start;
				$next_token['end'] = $end;
				$tokens[] = $next_token;
			}

			return $tokens;
		}

		// returns the next token or false if no match possible
		protected static function find_next($str, $pos, &$offset) {
			foreach (static::$terminals as $token_type) {
				$name = $token_type[0];
				$regex = $token_type[1];
				$generator = $token_type[2];

				if (preg_match($regex, $str, $matches)) {
					$tok = self::$generator($name, $matches, $pos + $offset);
					$offset += strlen($matches[1]);
					return $tok;
				}
			}

			// does not match any token types
			static::$report = new Report("invalid token", $pos + $offset);
			return false;
		}
	}
?>