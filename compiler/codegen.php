<?php 
	class GraphzappCodegen {
		public static function codegen($ast) {
			$type = $ast['type'];

			switch ($type) {
				case 'Expr':
					$expr = self::codegen($ast['tree']);
					return "eval(t,k,[function(t,k){return ".$expr.";}])";

				case 'Binop':
					$left = self::codegen($ast['left']);
					$op = $ast['op'];
					$right = self::codegen($ast['right']);

					switch ($op) {
						case ';':
							return $left.';},function(t,k){return '.$right;
						case '|':
							return $left.'||'.$right;
						case '&':
							return $match = $left.'&&'.$right;
						case '^':
							return "power(".$left.",".$right.")";
						default:
							return $left.$op.$right;
					}

				case 'Unop':
					$operand = self::codegen($ast['operand']);
					$op = $ast['op'];

					switch ($op) {
						case '-':
							return ' -'.$operand;
						
						case '!':
							return '!'.$operand;
					}

				case 'Lit':
					return $ast['value'];

				case 'Var':
					return $ast['value'];

				case 'Const':
					return $ast['value'];

				case 'Func':
					$id = $ast['id'];
					$params = $ast['params'];

					$args = array();
					foreach ($params as $ast) {
						$arg = "[function(t,k){return ".self::codegen($ast).";}]";
						$args[] = $arg;
					}

					return $id."(t,k,".implode(",", $args).")";
				
				default:
					return "0";
			}
		}
	}
?>