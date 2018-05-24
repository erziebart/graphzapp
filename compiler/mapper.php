<?php
	// this is a class for mapping tokens output from the lexer
	// into strings of valid javascript
	class GraphzappMapper {
		protected static $state_map = array(
		/* format is
			<TOKEN_TYPE> => [
				<cur_state> => [<mapper>, <next_state>] , 
				... , 
				<cur_state> => [<mapper>, <next_state>]
			]
			*** a negative next_state indicates error ***
		*/
		"T_CALL" => array(
					["m_call", 2], 
					["m_negate", 2], 
					["m_call_implicit", 2]),
		"T_VAR" => array(
					["m_match", 2],
					["m_negate", 2],
					["m_implicit", 2]),
		"T_ID" => array(
					["m_match", 2], 
					["m_negate", 2], 
					["m_implicit", 2]),
		"T_BINOP" => array(
					["m_binop", -1], 
					["m_binop", -1], 
					["m_binop", 0]),
		"T_NOT" => array(
					["m_match", 0], 
					["m_match", 0], 
					["m_match", 0]),
		"T_MINUS" => array(
					["m_match", 1], 
					["m_padded", 1], 
					["m_match", 1]),
		"T_LITERAL" => array(
					["m_match", 2], 
					["m_negate", 2], 
					["m_match", 2]),
		"T_LPAREN" => array(
					["m_lparen", 0], 
					["m_lparen", 0], 
					["m_lparen_implicit", 0]),
		"T_RPAREN" => array(
					["m_rparen", -1], 
					["m_rparen", -1], 
					["m_rparen", 2]),
		);

		const START = 0;
		const FINISH = 2;

		//////////////////////mappers to strings/////////////////////
		protected static function m_match($token) {
			return $token['match'];
		}

		protected static function m_binop($token) {
			$match = $token['match'];
			switch ($match) {
				case '^':
					return '**';
				case '&':
					return '&&';
				case '|':
					return '||';
				case ';':
					return ';},function(n,t){return ';
				default:
					return $match;
			}
		}

		protected static function m_padded($token) {
			return " ".self::m_match($token);
		}

		protected static function m_implicit($token) {
			return "*".self::m_match($token);
		}

		protected static function m_negate($token) {
			return "1*".self::m_match($token);
		}

		protected static function m_call($token) {
			$id = $token['id'];
			$params = $token['params'];

			$args = array();
			foreach ($params as $tok_ls) {
				$args[] = self::convert($tok_ls);
			}

			return $id."(n,t,".implode(",", $args).")";
		}

		protected static function m_call_implicit($token) {
			return "*".self::m_call($token);
		}

		protected static function m_lparen($token) {
			return "eval(n,t,[function(n,t){return ";
		}

		protected static function m_rparen($token) {
			return ";}])";
		}

		protected static function m_lparen_implicit($token) {
			return "*".self::m_lparen($token);
		}
		/////////////////////////////////////////////////////////////

		// convert the tokens to a string reprentation of a
		// javascript object implementing the function
		public static function convert($tokens) {
			$state = self::START;
			$res = "";
			$parens = 0;

			foreach ($tokens as $current) {
				$name = $current["name"];

				switch ($name) {
					case "T_LPAREN":
						$parens++;
						break;

					case "T_RPAREN":
						$parens--;
						if ($parens < 0) {
							return false;
						}
						break;

					default:
						break;
				}

				$entry = static::$state_map[$name][$state];
				$mapper = $entry[0];
				$next_state = $entry[1];

				if ($next_state >= 0) {
					$state = $next_state;
					$res .= static::$mapper($current);
				} else {
					// parse error
					return false;
				}
			}

			if ($state === self::FINISH) {
				return "[function(n,t){return ".$res.";}]";
			} else {
				return false;
			}
			
		}
	}
?>