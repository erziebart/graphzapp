<!DOCTYPE html>
<html>
<head>
	<title>Graphzapp Guide</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php include "header.php"; ?>
	<div class="body_container">
		<div class="guide_col">
			<p> How to write equations in the Graphzapp grapher</p>
			<div class="well card">
				<h3> Intro </h3>
				<p> 
					Define equations for x and y in terms of the variable t in order to draw a curve. Include the variable k to allow animation of the curve by sliding the k slider. Graphzapp has several built-in functions including logarithms and trigonometric functions.
				</p>
				<p>
					Example:  In the x= and y= boxes, write equations such as <nobr>"cos(t + k)"</nobr> or <nobr>"log(2, t)"</nobr>
				</p>
			</div>
			<div class="well card">
				<h3> Arithmetic </h3>
				<p> 
					Use (+) for addition, (-) for subtraction, (*) for multiplication, (/) for division, and (^) for powers. Use parentheses to group parts of the expressions.
				</p>
				<p>
					Example: Graph a parabola by typing <nobr>"(1/2)*t^2 + 3*t - 1.5"</nobr>. We can also simply write <nobr>"1/2t^2 + 3t - 1.5"</nobr> to graph the same parabola.
				</p>
			</div>
			<div class="well card">
				<h3> Comparisons </h3>
				<p>
					Each comparison evaulates to 1 if true and 0 if false. The comparisons are (==) equal to, (!=) not equal to, (<) less than, (>) greater than, (<=) less than or equal to, and (>=) greater than or equal to.
				</p>
				<p>
					Example: <nobr>(7 != 6)</nobr> has value 1. <nobr>(5 < 4)</nobr> has value 0. <nobr>(t >= 0)</nobr> has value 1 or 0, depending on the value of t.
				</p>
			</div>
			<div class="well card">
				<h3> Boolean Algebra </h3>
				<ul>
					<li>Logical AND (&) - returns 1 if both operands are true and 0 otherwise</li>
					<li>Logical OR (|) - returns 1 if either operand is true and 0 otherwise</li>
					<li>Logical NOT (!) - returns 1 when the operand is false and 0 when it is true</li>
				</ul>
				<p>
					When evaluating, anything non-zero stands for true, and only 0 stands for false.
				</p>
				<p>
					Example: <nobr>(6 > 1 & 0 >= 4)</nobr> has value 0. <nobr>(1 == 0 | 5)</nobr> has value 1. <nobr>(1 == 0 | 0)</nobr> has value 0.
				</p>
			</div>
			<div class="well card">
				<h3> Function Domains </h3>
				<p> 
					Graphzapp will not graph any points where the x or y expression is undefined. The expression is undefined if it contains a division by 0, an indeterminate form, or an imaginary answer. Use divison by a boolean expression to limit the domain to only points where the expression is true. 
				</p>
				<p>
					Example: <nobr>"t^0.5"</nobr> will only graph where t is at least 0. <nobr>"cos(t)/(-1 < t & t < 1)"</nobr> will only graph where t is between -1 and 1.
				</p>
			</div>
			<div class="well card">
				<h3> Piecewise Functions </h3>
				<p>
					The (;) symbol lets the expression be definied piecewise. Each part is a value divided by a domain with semicolons (;) separating the parts. At each point, the grapher will draw the first of these parts which is defined when reading from the left, or draw nothing if all are undefined.
				</p>
				<img src="piecewise.gif" align="middle" alt="f(x) = {-1, x < -1 : 0, -1 <= x <= 1 : 1, x > 1}">
				<p>
					Example: To graph the above piecewise function, we can type <nobr>"-1/(t < -1); 0/(-1 <= t & t <= 1); 1/(t > 1)"</nobr>. An even simpler way would be writing <nobr>"-1/(t < -1); 0/(t <= 1); 1"</nobr>.
				</p>
			</div>
		</div>
	</div>
</body>
</html>