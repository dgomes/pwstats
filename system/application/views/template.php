<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>

	<head>
		<?php include_once("header.php") ?>

		<?php if(isset($header)) echo $header; ?>
	</head>

<body>
	<div id="header">
		<div id="logo">
			<div id="logo_text">
				<h1><span class="green">Power</span>Stats</h1>
			</div>
		
			<div id="menubar">
				<?php include_once("menu.php") ?>
			</div>
		</div>
	</div>
	<div id="site_content">
		<div id="content">
			<?php if(isset($content)) echo $content; ?>
		</div>
		<?php if(isset($sidebar)){ ?>
		<div id="sidebar">
			<?php echo $sidebar; ?>
		</div>
		<?php } ?>
	</div>
    	<div id="footer">
		<?php include_once("footer.php") ?>
	</div>
</body>
</html>
