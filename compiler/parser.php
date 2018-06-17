<?php
	/* LALR(1) parser for the graphzapp equations */

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

		protected static function a() { //0
			$toks = static::pop_n(1);
			$match = "[function(t,k){return ".$toks[0]['match'].";}]";
			return array("name"=>'$accept', "match"=>$match);
		}

		protected static function r_Sa() { //1
			$toks = static::pop_n(3);
			$left = $toks[0]['match'];
			$op = $toks[1]['match'];
			$right = $toks[2]['match'];

			// mapping BINOP0 operators
			switch ($op) {
				case ';':
					$match = $left.';},function(t,k){return '.$right;
					break;
				case '|':
					$match = $left.'||'.$right;
					break;
				case '&':
					$match = $left.'&&'.$right;
					break;
				default:
					$match = $left.$op.$right;
					break;
			}

			return array("name"=>"S", "match"=>$match);
		}

		protected static function r_Sb() { //2
			$toks = static::pop_n(1);
			return array("name"=>"S", "match"=>$toks[0]['match']);
		}

		protected static function r_Ea() { //3
			$toks = static::pop_n(3);
			return array("name"=>"E", "match"=>$toks[0]['match']."+".$toks[2]['match']);
		}

		protected static function r_Eb() { //4
			$toks = static::pop_n(3);
			return array("name"=>"E", "match"=>$toks[0]['match']."-".$toks[2]['match']);
		}

		protected static function r_Ec() { //5
			$toks = static::pop_n(1);
			return array("name"=>"E", "match"=>$toks[0]['match']);
		}

		protected static function r_Ta() { //6
			$toks = static::pop_n(3);
			return array("name"=>"T", "match"=>$toks[0]['match'].$toks[1]['match'].$toks[2]['match']);
		}

		protected static function r_Tb() { //7
			$toks = static::pop_n(2);
			return array("name"=>"T", "match"=>$toks[0]['match']."*".$toks[1]['match']);
		}

		protected static function r_Tc() { //8
			$toks = static::pop_n(1);
			return array("name"=>"T", "match"=>$toks[0]['match']);
		}

		protected static function r_Fa() { //9
			$toks = static::pop_n(3);
			$base = $toks[0]['match'];
			$exp = $toks[2]['match'];
			return array("name"=>"F", "match"=>"Math.pow(".$base.",".$exp.")");
		}

		protected static function r_Fb() { //10
			$toks = static::pop_n(2);
			return array("name"=>"F", "match"=>"-".$toks[1]['match']);
		}

		protected static function r_Fc() { //11
			$toks = static::pop_n(2);
			return array("name"=>"F", "match"=>"!".$toks[1]['match']);
		}

		protected static function r_Fd() { //12
			$toks = static::pop_n(1);
			return array("name"=>"F", "match"=>$toks[0]['match']);
		}

		protected static function r_Na() { //13
			$toks = static::pop_n(3);
			$base = $toks[0]['match'];
			$exp = $toks[2]['match'];
			return array("name"=>"N", "match"=>"Math.pow(".$base.",".$exp.")");
		}

		protected static function r_Nb() { //14
			$toks = static::pop_n(2);
			return array("name"=>"N", "match"=>"!".$toks[1]['match']);
		}

		protected static function r_Nc() { //15
			$toks = static::pop_n(1);
			return array("name"=>"N", "match"=>$toks[0]['match']);
		}

		protected static function r_Va() { //16
			$toks = static::pop_n(1);
			return array("name"=>"V", "match"=>$toks[0]['match']);
		}

		protected static function r_Vb() { //17
			$toks = static::pop_n(1);
			return array("name"=>"V", "match"=>$toks[0]['match']);
		}

		protected static function r_Vc() { //18
			$toks = static::pop_n(1);
			return array("name"=>"V", "match"=>$toks[0]['match']);
		}

		protected static function r_Vd() { //19
			$toks = static::pop_n(3);
			$match = "eval(t,k,[function(t,k){return ".$toks[1]['match'].";}])";
			return array("name"=>"V", "match"=>$match);
		}

		protected static function r_Ca() { //20
			$toks = static::pop_n(1);
			return array("name"=>"C", "match"=>$toks[0]['match']);
		}

		protected static function r_Cb() { //21
			$toks = static::pop_n(2);
			$id = $toks[0]['match'];
			return array("name"=>"C", "match"=>$id."(t,k)");
		}

		protected static function r_Cc() { //22
			$toks = static::pop_n(3);
			$id = $toks[0]['match'];
			$params = $toks[1]['match'];
			return array("name"=>"C", "match"=>$id."(t,k,$params)");
		}

		protected static function r_Aa() { //23
			$toks = static::pop_n(1);
			$match = "[function(t,k){return ".$toks[0]['match'].";}]";
			return array("name"=>"A", "match"=>$match);
		}

		protected static function r_Ab() { //24
			$toks = static::pop_n(3);
			$match = $toks[0]['match'].",[function(t,k){return ".$toks[2]['match'].";}]";
			return array("name"=>"A", "match"=>$match);
		}

		/////////////////////////////////////////////////////////////



		///////////////////// ERROR HANDLING ////////////////////////
		// generate reports of errors when called and passed a next token

		protected static function e_empty($next_tok) {
			return new Report("expression required", -1);
		}

		protected static function e_operand($next_tok) {
			return new Report("operand expected", $next_tok['start']);
		}

		protected static function e_operator($next_tok) {
			return new Report("operator expected", $next_tok['start']);
		}

		protected static function e_lparen($next_tok) {
			$len = count(static::$stack);
			$loc = static::$stack[$len - 2]['start'];
			return new Report("unbalanced (", $loc);
		}

		protected static function e_rparen($next_tok) {
			return new Report("unbalanced )", $next_tok['start']);
		}

		protected static function e_ended($next_tok) {
			return new Report("unexpected end of input", -1);
		}

		protected static function e_unclosed_0($next_tok) {
			$len = count(static::$stack);
			$func = static::$stack[$len - 1];
			$id = $func['match'];
			$loc = $func['start'];
			return new Report("unclosed argument list for $id", $loc);
		} 

		protected static function e_unclosed_1($next_tok) {
			$len = count(static::$stack);
			$func = static::$stack[$len - 2];
			$id = $func['match'];
			$loc = $func['start'];
			return new Report("unclosed argument list for $id", $loc);
		} 

		protected static function e_nolist($next_tok) {
			return new Report("unexpected comma", $next_tok['start']);
		}

		/////////////////////////////////////////////////////////////
		

		/* parse table */
		protected static $parse_table = array(
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

			"START" => ['$end'=>'e_empty', '$plus'=>'e_operand', '$minus'=>'s_Fb1', '$binop2'=>'e_operand', '$not'=>'s_Fc1', '$power'=>'e_operand', '$lit'=>'s_Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'e_rparen', '$fid'=>'s_Cb1_Cc1', '$comma'=>'e_operand', 'S'=>'END_Sa1', 'E'=>'Sb1_Ea1_Eb1', 'T'=>'Ec1_Ta1_Tb1', 'F'=>'Tc1', 'V'=>'Fa1_Fd1', 'C'=>'Vc1'], //0

			"END_Sa1" => ['$end'=>'a_', '$binop0'=>'s_Sa2', '$rparen'=>'e_rparen', '$comma'=>'e_nolist'], //1

			"Sb1_Ea1_Eb1" => ['$end'=>'r_Sb', '$binop0'=>'r_Sb', '$plus'=>'s_Ea2', '$minus'=>'s_Eb2', '$rparen'=>'r_Sb', '$comma'=>'r_Sb'], //2

			"Sa2" => ['$end'=>'e_operand', '$binop0'=>'e_operand', '$plus'=>'e_operand', '$minus'=>'s_Fb1', '$binop2'=>'e_operand', '$not'=>'s_Fc1', '$power'=>'e_operand', '$lit'=>'Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'e_operand', '$fid'=>'s_Cb1_Cc1', '$comma'=>'e_operand', 'E'=>'Sa3_Ea1_Eb1', 'T'=>'Ec1_Ta1_Tb1', 'F'=>'Tc1', 'V'=>'Fa1_Fd1', 'C'=>'Vc1'], //14

			"Sa3_Ea1_Eb1" => ['$end'=>'r_Sa', '$binop0'=>'r_Sa', '$plus'=>'s_Ea2', '$minus'=>'s_Eb2', '$rparen'=>'r_Sa', '$comma'=>'r_Sa'], //28

			"Ea2" => ['$end'=>'e_operand', '$binop0'=>'e_operand', '$plus'=>'e_operand', '$minus'=>'s_Fb1', '$binop2'=>'e_operand', '$not'=>'s_Fc1', '$power'=>'e_operand', '$lit'=>'s_Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'e_operand', '$fid'=>'s_Cb1_Cc1', '$comma'=>'e_operand', 'T'=>'Ea3_Ta1_Tb1', 'F'=>'Tc1', 'V'=>'Fa1_Fd1', 'C'=>'Vc1'], //15

			"Eb2" => ['$end'=>'e_operand', '$binop0'=>'e_operand', '$plus'=>'e_operand', '$minus'=>'s_Fb1', '$binop2'=>'e_operand', '$not'=>'s_Fc1', '$power'=>'e_operand', '$lit'=>'s_Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'e_operand', '$fid'=>'s_Cb1_Cc1', '$comma'=>'e_operand', 'T'=>'Eb3_Ta1_Tb1', 'F'=>'Tc1', 'V'=>'Fa1_Fd1', 'C'=>'Vc1'], //16

			"Fb1" => ['$end'=>'e_operand', '$binop0'=>'e_operand', '$plus'=>'e_operand', '$minus'=>'s_Fb1', '$binop2'=>'e_operand', '$not'=>'s_Fc1', '$power'=>'e_operand', '$lit'=>'s_Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'e_operand', '$fid'=>'s_Cb1_Cc1', '$comma'=>'e_operand', 'F'=>'Fb2', 'V'=>'Fa1_Fd1', 'C'=>'Vc1'], //6

			"Fb2" => ['$end'=>'r_Fb', '$binop0'=>'r_Fb', '$plus'=>'r_Fb', '$minus'=>'r_Fb', '$binop2'=>'r_Fb', '$not'=>'r_Fb', '$lit'=>'r_Fb', '$var'=>'r_Fb', '$const'=>'r_Fb', '$lparen'=>'r_Fb', '$rparen'=>'r_Fb', '$fid'=>'r_Fb', '$comma'=>'r_Fb'], //22

			"Ec1_Ta1_Tb1" => ['$end'=>'r_Ec', '$binop0'=>'r_Ec', '$plus'=>'r_Ec', '$minus'=>'r_Ec', '$binop2'=>'s_Ta2', '$not'=>'s_Nb1', '$lit'=>'s_Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'r_Ec', '$fid'=>'s_Cb1_Cc1', '$comma'=>'r_Ec', 'N'=>'Tb2', 'V'=>'Na1_Nc1', 'C'=>'Vc1'], //3

			"Ea3_Ta1_Tb1" => ['$end'=>'r_Ea', '$binop0'=>'r_Ea', '$plus'=>'r_Ea', '$minus'=>'r_Ea', '$binop2'=>'s_Ta2', '$not'=>'s_Nb1', '$lit'=>'s_Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'r_Ea', '$fid'=>'s_Cb1_Cc1', '$comma'=>'r_Ea', 'N'=>'Tb2', 'V'=>'Na1_Nc1', 'C'=>'Vc1'], //29

			"Eb3_Ta1_Tb1" => ['$end'=>'r_Eb', '$binop0'=>'r_Eb', '$plus'=>'r_Eb', '$minus'=>'r_Eb', '$binop2'=>'s_Ta2', '$not'=>'s_Nb1', '$lit'=>'s_Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'r_Eb', '$fid'=>'s_Cb1_Cc1', '$comma'=>'r_Eb', 'N'=>'Tb2', 'V'=>'Na1_Nc1', 'C'=>'Vc1'], //30

			"Ta2" => ['$end'=>'e_operand', '$binop0'=>'e_operand', '$plus'=>'e_operand', '$minus'=>'s_Fb1', '$not'=>'s_Fc1', '$binop2'=>'e_operand', '$power'=>'e_operand', '$lit'=>'s_Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'e_operand', '$fid'=>'s_Cb1_Cc1', '$comma'=>'e_operand', 'F'=>'Ta3', 'V'=>'Fa1_Fd1', 'C'=>'Vc1'], //17

			"Tc1" => ['$end'=>'r_Tc', '$binop0'=>'r_Tc', '$plus'=>'r_Tc', '$minus'=>'r_Tc', '$binop2'=>'r_Tc', '$not'=>'r_Tc', '$lit'=>'r_Tc', '$var'=>'r_Tc', '$const'=>'r_Tc', '$lparen'=>'r_Tc', '$rparen'=>'r_Tc', '$fid'=>'r_Tc', '$comma'=>'r_Tc'], //4

			"Ta3" => ['$end'=>'r_Ta', '$binop0'=>'r_Ta', '$plus'=>'r_Ta', '$minus'=>'r_Ta', '$binop2'=>'r_Ta', '$not'=>'r_Ta', '$lit'=>'r_Ta', '$var'=>'r_Ta', '$const'=>'r_Ta', '$lparen'=>'r_Ta', '$rparen'=>'r_Ta', '$fid'=>'r_Ta', '$comma'=>'r_Ta'], //31

			"Tb2" => ['$end'=>'r_Tb', '$binop0'=>'r_Tb', '$plus'=>'r_Tb', '$minus'=>'r_Tb', '$binop2'=>'r_Tb', '$not'=>'r_Tb', '$lit'=>'r_Tb', '$var'=>'r_Tb', '$const'=>'r_Tb', '$lparen'=>'r_Tb', '$rparen'=>'r_Tb', '$fid'=>'r_Tb', '$comma'=>'r_Tb'], //18

			"Fc1" => ['$end'=>'e_operand', '$binop0'=>'e_operand', '$plus'=>'e_operand', '$minus'=>'s_Fb1', '$binop2'=>'e_operand', '$not'=>'s_Fc1', '$lit'=>'s_Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'e_operand', '$fid'=>'s_Cb1_Cc1', '$comma'=>'e_operand', 'F'=>'Fc2', 'V'=>'Fa1_Fd1', 'C'=>'Vc1'], //7

			"Nb1" => ['$end'=>'e_operand', '$binop0'=>'e_operand', '$plus'=>'e_operand', '$minus'=>'s_Fb1', '$binop2'=>'e_operand', '$not'=>'s_Fc1', '$lit'=>'s_Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'e_operand', '$fid'=>'s_Cb1_Cc1', '$comma'=>'e_operand', 'F'=>'Nb2', 'V'=>'Fa1_Fd1', 'C'=>'Vc1'], //20

			"Fc2" => ['$end'=>'r_Fc', '$binop0'=>'r_Fc', '$plus'=>'r_Fc', '$minus'=>'r_Fc', '$binop2'=>'r_Fc', '$not'=>'r_Fc', '$lit'=>'r_Fc', '$var'=>'r_Fc', '$const'=>'r_Fc', '$lparen'=>'r_Fc', '$rparen'=>'r_Fc', '$fid'=>'r_Fc', '$comma'=>'r_Fc'], //23

			"Nb2" => ['$end'=>'r_Nb', '$binop0'=>'r_Nb', '$plus'=>'r_Nb', '$minus'=>'r_Nb', '$binop2'=>'r_Nb', '$not'=>'r_Nb', '$lit'=>'r_Nb', '$var'=>'r_Nb', '$const'=>'r_Nb', '$lparen'=>'r_Nb', '$rparen'=>'r_Nb', '$fid'=>'r_Nb', '$comma'=>'r_Nb'], //33

			"Fa1_Fd1" => ['$end'=>'r_Fd', '$binop0'=>'r_Fd', '$plus'=>'r_Fd', '$minus'=>'r_Fd', '$binop2'=>'r_Fd', '$not'=>'r_Fd', '$power'=>'s_Fa2', '$lit'=>'r_Fd', '$var'=>'r_Fd', '$const'=>'r_Fd', '$lparen'=>'r_Fd', '$rparen'=>'r_Fd', '$fid'=>'r_Fd', '$comma'=>'r_Fd'], //5

			"Na1_Nc1" => ['$end'=>'r_Nc', '$binop0'=>'r_Nc', '$plus'=>'r_Nc', '$minus'=>'r_Nc', '$binop2'=>'r_Nc', '$not'=>'r_Nc', '$power'=>'s_Na2', '$lit'=>'r_Nc', '$var'=>'r_Nc', '$const'=>'r_Nc', '$lparen'=>'r_Nc', '$rparen'=>'r_Nc', '$fid'=>'r_Nc', '$comma'=>'r_Nc'], //19

			"Fa2" => ['$end'=>'e_operand', '$binop0'=>'e_operand', '$plus'=>'e_operand', '$minus'=>'s_Fb1', '$binop2'=>'e_operand', '$not'=>'s_Fc1', '$lit'=>'s_Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'e_operand', '$fid'=>'s_Cb1_Cc1', '$comma'=>'e_operand', 'F'=>'Fa3', 'V'=>'Fa1_Fd1', 'C'=>'Vc1'], //21

			"Na2" => ['$end'=>'e_operand', '$binop0'=>'e_operand', '$plus'=>'e_operand', '$minus'=>'s_Fb1', '$binop2'=>'e_operand', '$not'=>'s_Fc1', '$lit'=>'s_Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'e_operand', '$fid'=>'s_Cb1_Cc1', '$comma'=>'e_operand', 'F'=>'Na3', 'V'=>'Fa1_Fd1', 'C'=>'Vc1'], //32

			"Fa3" => ['$end'=>'r_Fa', '$binop0'=>'r_Fa', '$plus'=>'r_Fa', '$minus'=>'r_Fa', '$binop2'=>'r_Fa', '$not'=>'r_Fa', '$lit'=>'r_Fa', '$var'=>'r_Fa', '$const'=>'r_Fa', '$lparen'=>'r_Fa', '$rparen'=>'r_Fa', '$fid'=>'r_Fa', '$comma'=>'r_Fa'], //34

			"Na3" => ['$end'=>'r_Na', '$binop0'=>'r_Na', '$plus'=>'r_Na', '$minus'=>'r_Na', '$binop2'=>'r_Na', '$not'=>'r_Na', '$lit'=>'r_Na', '$var'=>'r_Na', '$const'=>'r_Na', '$lparen'=>'r_Na', '$rparen'=>'r_Na', '$fid'=>'r_Na', '$comma'=>'r_Na'], //38

			"Va1" => ['$end'=>'r_Va', '$binop0'=>'r_Va', '$plus'=>'r_Va', '$minus'=>'r_Va', '$binop2'=>'r_Va', '$not'=>'r_Va', '$power'=>'r_Va', '$lit'=>'r_Va', '$var'=>'r_Va', '$const'=>'r_Va', '$lparen'=>'r_Va', '$rparen'=>'r_Va', '$fid'=>'r_Va', '$comma'=>'r_Va'], //8

			"Vb1" => ['$end'=>'r_Vb', '$binop0'=>'r_Vb', '$plus'=>'r_Vb', '$minus'=>'r_Vb', '$binop2'=>'r_Vb', '$not'=>'r_Vb', '$power'=>'r_Vb', '$lit'=>'r_Vb', '$var'=>'r_Vb', '$const'=>'r_Vb', '$lparen'=>'r_Vb', '$rparen'=>'r_Vb', '$fid'=>'r_Vb', '$comma'=>'r_Vb'], //9

			"Vc1" => ['$end'=>'r_Vc', '$binop0'=>'r_Vc', '$plus'=>'r_Vc', '$minus'=>'r_Vc', '$binop2'=>'r_Vc', '$not'=>'r_Vc', '$power'=>'r_Vc', '$lit'=>'r_Vc', '$var'=>'r_Vc', '$const'=>'r_Vc', '$lparen'=>'r_Vc', '$rparen'=>'r_Vc', '$fid'=>'r_Vc', '$comma'=>'r_Vc'], //10

			"Ca1" => ['$end'=>'r_Ca', '$binop0'=>'r_Ca', '$plus'=>'r_Ca', '$minus'=>'r_Ca', '$binop2'=>'r_Ca', '$not'=>'r_Ca', '$power'=>'r_Ca', '$lit'=>'r_Ca', '$var'=>'r_Ca', '$const'=>'r_Ca', '$lparen'=>'r_Ca', '$rparen'=>'r_Ca', '$fid'=>'r_Ca', '$comma'=>'r_Ca'], //12

			"Vd1" => ['$end'=>'e_ended', '$plus'=>'e_operand', '$minus'=>'s_Fb1', '$binop2'=>'e_operand', '$not'=>'s_Fc1', '$power'=>'e_operand', '$lit'=>'s_Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'e_operand', '$fid'=>'s_Cb1_Cc1', '$comma'=>'e_operand', 'S'=>'Vd2_Sa1', 'E'=>'Sb1_Ea1_Eb1', 'T'=>'Ec1_Ta1_Tb1', 'F'=>'Tc1', 'V'=>'Fa1_Fd1', 'C'=>'Vc1'], //11

			"Vd2_Sa1" => ['$end'=>'e_lparen', '$binop0'=>'s_Sa2', '$rparen'=>'s_Vd3', '$comma'=>'e_nolist'], //24

			"Vd3" => ['$end'=>'r_Vd', '$binop0'=>'r_Vd', '$plus'=>'r_Vd', '$minus'=>'r_Vd', '$binop2'=>'r_Vd', '$not'=>'r_Vd', '$power'=>'r_Vd', '$lit'=>'r_Vd', '$var'=>'r_Vd', '$const'=>'r_Vd', '$lparen'=>'r_Vd', '$fid'=>'r_Vd', '$comma'=>'r_Vd'], //35

			"Cb1_Cc1" => ['$end'=>'e_unclosed_0', '$plus'=>'e_operand', '$minus'=>'s_Fb1', '$binop2'=>'e_operand', '$not'=>'s_Fc1', '$power'=>'e_operand', '$lit'=>'s_Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'s_Cb2', '$fid'=>'s_Cb1_Cc1', '$comma'=>'e_operand', 'S'=>'Aa1_Sa1', 'E'=>'Sb1_Ea1_Eb1', 'T'=>'Ec1_Ta1_Tb1', 'F'=>'Tc1', 'V'=>'Fa1_Fd1', 'C'=>'Vc1', 'A'=>'Cc2_Ab1'], //13

			"Cb2" => ['$end'=>'r_Cb', '$binop0'=>'r_Cb', '$plus'=>'r_Cb', '$minus'=>'r_Cb', '$binop2'=>'r_Cb', '$not'=>'r_Cb', '$power'=>'r_Cb', '$lit'=>'r_Cb', '$var'=>'r_Cb', '$const'=>'r_Cb', '$lparen'=>'r_Cb', '$fid'=>'r_Cb', '$comma'=>'r_Cb'], //25

			"Aa1_Sa1" => ['$end'=>'r_Aa', '$binop0'=>'s_Sa2', '$rparen'=>'r_Aa', '$comma'=>'r_Aa'], //27

			"Cc2_Ab1" => ['$end'=>'e_unclosed_1', '$rparen'=>'s_Cc3', '$comma'=>'s_Ab2'], //26

			"Cc3" => ['$end'=>'r_Cc', '$binop0'=>'r_Cc', '$plus'=>'r_Cc', '$minus'=>'r_Cc', '$binop2'=>'r_Cc', '$not'=>'r_Cc', '$power'=>'r_Cc', '$lit'=>'r_Cc', '$var'=>'r_Cc', '$const'=>'r_Cc', '$lparen'=>'r_Cc', '$fid'=>'r_Cc', '$comma'=>'r_Cc'], //36

			"Ab2" => ['$end'=>'e_ended', '$plus'=>'e_operand', '$minus'=>'s_Fb1', '$binop2'=>'e_operand', '$not'=>'s_Fc1', '$power'=>'e_operand', '$lit'=>'s_Va1', '$var'=>'s_Vb1', '$const'=>'s_Ca1', '$lparen'=>'s_Vd1', '$rparen'=>'e_operand', '$fid'=>'s_Cb1_Cc1', '$comma'=>'e_operand', 'S'=>'Ab3_Sa1', 'E'=>'Sb1_Ea1_Eb1', 'T'=>'Ec1_Ta1_Tb1', 'F'=>'Tc1', 'V'=>'Fa1_Fd1', 'C'=>'Vc1'], //37

			"Ab3_Sa1" => ['$end'=>'r_Ab', '$binop0'=>'s_Sa2', '$rparen'=>'r_Ab', '$comma'=>'r_Ab'] //39
		);


		// error reporting
		static $report;

		// parses the token list to generate the final expression
		public static function parse($tokens) {
			$tokens = array_reverse($tokens);
			//print_r(static::$parse_table);
			
			while(true) {
				$state = end(static::$stack)['state'];
				//print_r(end(static::$stack));
				$next_tok = end($tokens);
				$name = $next_tok['name'];
				$start = $next_tok['start'];
				$end = $next_tok['end'];

				if(isset(static::$parse_table[$state][$name])) {
					$action = static::$parse_table[$state][$name];
					$parts = explode("_", $action, 2);
					$type = $parts[0];

					switch($type) {
						default: // goto
							array_pop($tokens);
							static::$stack[] = array("match" => $next_tok['match'], "state" => $action, "start"=>$start, "end"=>$end);
							break;

						case 's': // shift
							$target = $parts[1];
							array_pop($tokens);
							static::$stack[] = array("match" => $next_tok['match'], "state" => $target, "start"=>$start, "end"=>$end);
							break;

						case 'r': // reduce
							$target = $parts[1];
							$nonterm = self::$action();
							$name = $nonterm['name'];
							$state = end(static::$stack)['state'];

							if(isset(static::$parse_table[$state][$name])) {
								$target = static::$parse_table[$state][$name];
								static::$stack[] = array("match" => $nonterm['match'], "state" => $target);
							} else {
								//print_r(static::$parse_table);
								//print_r(static::$stack);
								static::$report = new Report("no action for ".$name." in state ".$state, $start);
								return false;
							}

							break;

						case 'e': // error
							static::$report = self::$action($next_tok);
							return false;

						case 'a': // accept
							return self::a()['match'];
					}

				} else {
					//print_r(static::$stack);
					static::$report = new Report("no action for ".$name." in state ".$state, $start);
					return false;
				}
			}
		}
	}
?>