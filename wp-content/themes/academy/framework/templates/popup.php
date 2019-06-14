<?php
$file_path=explode('wp-content', __FILE__ );
$wp_path=$file_path[0];
require_once($wp_path.'/wp-load.php');
?>
<html>
<head>
	<style type="text/css">#TB_window {width:530px!important;}</style>
</head>
<body>
	<div class="themex-shortcode">
		<form method="POST" action="" id="themex_shortcode">
			<?php echo ThemexShortcode::renderSettings(); ?>			
		</form>
	</div>
</body>
</html>