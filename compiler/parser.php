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

		// parses the token list to generate the final expression
		public static function parse($tokens) {
			return "PARSER-GENERATED STRING";
		}
	}
?>