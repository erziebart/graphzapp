<?php
	// Referenced: 
	// http://nitschinger.at/Writing-a-simple-lexer-in-PHP/

	// this is a class for lexing the input expression into 
	// tokens.
	class GraphzappLexer {
		// Handy regular expressions
		const ID = "[A-Za-z][\w]*";
		const ALLOWED = "[\w+\-\*\/\^&\|!=<>;,\.\s]";
		const JSLIT = "((\d+(\.\d*)?)|(\.\d+))((E|e)(\+|\-)?\d+)?";

		protected static $terminals = array(
			/* format is
				"<TOKEN_TYPE>"
				"<REGEX>"
				"<GENERATOR>" 
			*/

			array(
				"T_WHITESPACE", 
				"\s+",
				"g_ignore"
			),
			array(
				"T_VAR",
				"(n|t)(?!\w)",
				"g_generic"
			),
			array(
				"T_CALL", 
				"(".self::ID.")(\((?:(?>".self::ALLOWED."+)|(?3))*\))",
				"g_call"
			),
			array(
				"T_ID", 
				"[A-Za-z][\w]*",
				"g_generic"
			),
			array(
				"T_BINOP", 
				";|&|\||==|!=|<=|>=|<|>|\+|\*|\/|\^",
				"g_generic"
			),
			array(
				"T_NOT", 
				"!",
				"g_generic"
			),
			array(
				"T_MINUS", 
				"\-",
				"g_generic"
			),
			array(
				"T_LITERAL", 
				"(?>".self::JSLIT.")(?![\.])",
				"g_generic"
			),
			array(
				"T_LPAREN", 
				"\(",
				"g_generic"
			),
			array(
				"T_RPAREN", 
				"\)",
				"g_generic"
			)
		);

		////////////////token generation actions/////////////////
		protected static function g_ignore($name, $matches, $offset) {
			return true;
		}

		protected static function g_generic($name, $matches, $offset) {
			return array(
				"name" => $name,
				"offset" => $offset,
				"match" => $matches[1]);
		}

		// for T_CALL
		protected static function g_call($name, $matches, $offset) {
			$id = $matches[2];
			$args = explode(",", substr($matches[3],1,strlen($matches[3]) - 2));
			
			$params = array();
			foreach ($args as $expr) {
				$params[] = self::token($expr);
			}

			return array(
				"name" => $name,
				"offset" => $offset,
				"id" => $id,
				"params" => $params);
		}
		/////////////////////////////////////////////////////////

		// error reporting
		static $report;

		// prepare regexes
		public static function init() {
			for ($i = 0; $i < count(static::$terminals); $i++) {
				$regex = static::$terminals[$i][1];
				static::$terminals[$i][1] = "/(".$regex.")/xA";
			}
		}

		// generate an array of tokens from a string
		public static function token($expr) {
			$tokens = array();
			$offset = 0;

			while ($offset < strlen($expr)) {
				$remaining = substr($expr, $offset);
				$next_token = static::find_next($remaining, $offset);

				if($next_token === false) {
					// cannot parse this expression
					return false;
				}

				if ($next_token !== true) {
					// unless we are ignoring it
					$tokens[] = $next_token;
				}
			}

			return $tokens;
		}

		// returns the next token or false if no match possible
		protected static function find_next($str, &$offset) {
			foreach (static::$terminals as $token_type) {
				$name = $token_type[0];
				$regex = $token_type[1];
				$generator = $token_type[2];

				if (preg_match($regex, $str, $matches)) {
					$offset += strlen($matches[1]);
					return self::$generator($name, $matches, $offset);
				}
			}

			// does not match any token types
			static::$report = new Report("Invalid token", $offset);
			return false;
		}
	}
?>