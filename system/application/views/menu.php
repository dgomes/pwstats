<ul id="menu">
	<li><a href="">Home</a></li>
	<li><a href="<?=base_url()?>charts">Charts</a></li>
	<li><a href="<?=base_url()?>simulate">Simulate</a></li>
	<li><a href="<?=base_url()?>devices">Devices</a></li>
	<?php
		if(isset($user_id))
		{
	?>
		<li><a href="<?=base_url()?>auth/logout">Logout</a></li>
	<?php 	}else{ ?>
	<li><a href="<?=base_url()?>auth/login">Login</a></li>
	<?php  	} ?>
</ul>
