<?php 
	$colors = array(
		'black' => ['#000000', '#E9E9E9'], 
		'white' => ['#FFFFFF', '#8C8C8C'],
	    'blue' => ['#4D6F96', '#DDE5EE'],
	    'red' => ['#CC0000', '#FFCCCC'],
	    'green' => ['#1AFF1A', '#CCFFCC'],
	    'purple' => ['#660066', '#FF80FF'],
	    'gray' => ['#999999', '#F7F7F7']
	);

	$bgcolor = isset($_POST['bgcolor']) ? $_POST['bgcolor'] : 'white';
	$axescolor = isset($_POST['axescolor']) ? $_POST['axescolor'] : 'black';

	function generate_dropdown($id, $selected, $onclick) {
		global $colors;
		$options = "";
		foreach ($colors as $name => $codes) {
			$classes = $selected === $name ? "option $name hidden" : "option $name";
			$display = $codes[0];
			$options .= "<div onclick=\"$onclick('$name')\"><div class = \"$classes\" style=\"background-color: $display\"></div></div>";
		}

		$s_code = $colors[$selected][0];
		$current = "<div class=\"selected\" onclick=\"toggleDropdown($id)\"><div class = \"option $selected\" style=\"background-color: $s_code\"></div><span>▼</span></div>";

		$dropdown = "<div class=\"colors_dropdown\" id=\"dropdown$id\">".$current."<div class=\"options\" style=\"display: none\">".$options."</div></div>";

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