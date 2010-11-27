<ul>
	<li><a href="">Home</a></li>
	<li><a href="charts">Charts</a></li>
	<li><a href="devices">Devices</a></li>
	<?php
		if(isset($user_id))
		{
	?>
			<li><a href="auth/logout">Logout</a></li>
	<?php 	}else{ ?>
			<li><a href="auth/login">Login</a></li>
	<?php  	} ?>
</ul>
