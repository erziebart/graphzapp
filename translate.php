<?php
	// example user inputs
	$input_x = "n";
	$input_y = "(n^2 - t)/(n <= -1) ; (n^2 + t)/(n >= 1)";

	const UNDEF = "[function(n,t){return NaN;}]";

	// initialize outputs
	$err = "";
	$x = UNDEF;
	$y = UNDEF;

	// translate the inputs to get something like the below
	$x = "[function(n,t){return n;}]";
	$y = "[function(n,t){return (n**2-t)/(n<=-1);},function(n,t){return (n**2+t)/(n>=1);}]";
?>

<script type="text/javascript">
	var _err = "<?php echo($err); ?>";
	var _y = <?php echo($y); ?>;
	var _x = <?php echo($x); ?>;

	// will evaluate the value of x or y given the current n and t
	// sample usage: cur_y = eval(0,1,_y);
	function eval(n,t,fn) {
		for (var i = 0; i < fn.length; i++) {
			var val = fn[i](n,t);
			if (isFinite(val) && !isNaN(val)) {
				if (val === true) {
					return 1;
				} else if(val === false) {
					return 0;
				} else {
					return val;
				}
			}
		}
		return NaN;
	}
</script>