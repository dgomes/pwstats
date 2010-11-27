<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>

	<head>
		<?php include_once("header.php") ?>

		<?php if(isset($header)) echo $header; ?>
	</head>

<body>
	<div id="header">
		<div id="logo"></div>
		<div id="menu">
			<?php include_once("menu.php") ?>
		</div>
		 <div class="cleaner"></div>
	</div>
	<div id="banner_wrapper">
		<div id="banner">
			<div id="banner_content">
			<div id="banner_title">banner</div>
			<div id="banner_text">
			</div>
	    
			<div class="cleaner"></div>
			</div> <!-- end of banner content -->
			<div class="cleaner"></div>   
		</div> <!-- end of banner -->
	</div> <!-- end of banner wrapper -->

	<div id="content_top_wrapper">
		<div id="content_top">
    
			<div class="header_01">Alerts</div>
			<div id="alert">
				<?php if(isset($alert)) echo $alert; ?>
			</div>
			<div class="margin_bottom_10"></div>
			<div class="cleaner"></div>
	    	</div> <!-- end of content top -->
	</div> <!-- end of content top wrapper -->

	<div id="content_wrapper">
		<div id="content">
			<?php if(isset($content)) echo $content; ?>
		</div>

		<div id="left_panel">
			<?php if(isset($left_panel)) echo $left_panel; ?>
		</div>

		<div id="right_panel">
			<?php if(isset($right_panel)) echo $right_panel; ?>
		</div>
	</div>
	<div id="footer_wrapper">
    		<div id="footer">
			<?php include_once("footer.php") ?>
		</div>
	</div>
</body>
</html>
