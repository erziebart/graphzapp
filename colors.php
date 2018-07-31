<?php 

	// original
	// $colors = array( 
	// 	'black' => ['#000000', '#E9E9E9'], 
	// 	'white' => ['#FFFFFF', '#8C8C8C'],
	//     'blue' => ['#4D6F96', '#DDE5EE'],
	//     'red' => ['#CC0000', '#FFCCCC'],
	//     'green' => ['#1AFF1A', '#CCFFCC'],
	//     'purple' => ['#660066', '#FF80FF'],
	//     'gray' => ['#999999', '#F7F7F7']
	// );

	// classic
	// $colors = array(
	// 	'black' => ['#000000FF', '#00000022'], 
	// 	'white' => ['#FFFFFFFF', '#FFFFFF88'],
	//  'blue' => ['#4D6F96FF', '#4D6F9677'],
	//  'red' => ['#CC0000FF', '#CC000066'],
	//  'green' => ['#1AFF1AFF', '#1AFF1A66'],
	//  'purple' => ['#660066FF', '#66006688'],
	//  'gray' => ['#999999FF', '#99999988']
	// );

	// neon
	// $colors = array(
	// 	'black' => ['#000000FF', '#00000022'], 
	// 	'white' => ['#FFFFFFFF', '#FFFFFF88'],
	//     'blue' => ['#04d9ffff', '#04d9ff88'],
	//     'green' => ['#0cff0cff', '#0cff0c88'],
	//     'pink' => ['#fe019aff', '#fe019a88'],
	//     'purple' => ['#bc13feff', '#bc13fe88'],
	//     'red' => ['#ff073aff', '#ff073a88'],
	//     'yellow' => ['#cfff04ff', '#cfff0499']
	// );

	// pastel
	$colors = array(
		'black' => ['#000000FF', '#00000022'], 
		'white' => ['#FFFFFFFF', '#FFFFFF88'],
	    'blue' => ['#a2bffeff', '#a2bffe99'],
	    'green' => ['#b0ff9dff', '#b0ff9d99'],
	    'orange' => ['#ff964fff', '#ff964f99'],
	    'pink' => ['#ffbacdff', '#ffbacd99'],
	    'purple' => ['#caa0ffff', '#caa0ff99'],
	    'red' => ['#db5856ff', '#db585699'],
	    'yellow' => ['#fffe71ff', '#fffe7199']
	);

	// digital
	// $colors = array(
	// 	'black' => ['#000000FF', '#00000022'], 
	// 	'white' => ['#FFFFFFFF', '#FFFFFF88'],
	//     'blue' => ['#0000ffff', '#0000ff88'],
	//     'green' => ['#00ff00ff', '#00ff0088'],
	//     'red' => ['#ff0000ff', '#ff000088'],
	//     'cyan' => ['#00ffffff', '#00ffff88'],
	//     'magenta' => ['#ff00ffff', '#ff00ff88'],
	//     'yellow' => ['#ffff00ff', '#ffff88']
	// );

	function generate_dropdown($id, $selected, $onclick) {
		global $colors;
		$drop_options = "";
		foreach ($colors as $name => $codes) {
			$classes = $selected === $name ? "option $name hidden" : "option $name";
			$display = $codes[0];
			$drop_options .= "<div onclick=\"$onclick('$name')\"><div class = \"$classes\" style=\"background-color: $display\"></div></div>";
		}

		$s_code = $colors[$selected][0];
		$current = "<div class=\"selected\" onclick=\"toggleDropdown($id)\"><div class = \"option $selected\" style=\"background-color: $s_code\"></div><span>▼</span></div>";

		$dropdown = "<div class=\"colors_dropdown\" id=\"dropdown$id\">".$current."<div class=\"options\" style=\"display: none\">".$drop_options."</div></div>";

		echo ($dropdown);
	}
?>
<script type="text/javascript">
	var colors = {
		<?php 
			$entries = array();
			foreach ($colors as $name => $codes) {
				$entries[] = "'".$name."':['".$codes[0]."','".$codes[1]."']";
			}
			echo implode(",", $entries);
		?>
	};
</script>