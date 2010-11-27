<html>

	<head>
		<?= include_once("header.php") ?>
		<?= $header ?>
	</head>

<body>
	<div id="menu">
		<?= include_once("menu.php") ?>
	</div>
	<div id="alerts">
		<?= $alerts ?>
	</div>

	<div id="contents">
		<?= $contents ?>
	</div>

	<div id="left_panel">
		<?= $left_panel ?>
	</div>

	<div id="right_panel">
		<?= $right_panel ?>
	</div>

	<div id="footer">
		<?= include_once("footer.php") ?>
	</div>
</body>
</html>
