<html>

	<head>
		<?php include_once("header.php") ?>

		<?php if(isset($header)) echo $header; ?>
	</head>

<body>
	<div id="menu">
		<?php include_once("menu.php") ?>
	</div>
	<div id="alert">
		<?php if(isset($alert)) echo $alert; ?>
	</div>

	<div id="content">
		<?php if(isset($content)) echo $content; ?>
	</div>

	<div id="left_panel">
		<?php if(isset($left_panel)) echo $left_panel; ?>
	</div>

	<div id="right_panel">
		<?php if(isset($right_panel)) echo $right_panel; ?>
	</div>

	<div id="footer">
		<?php include_once("footer.php") ?>
	</div>
</body>
</html>
