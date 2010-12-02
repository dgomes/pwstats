<div>
	<div>
	<?php
		foreach($devices as $device)
		{
			echo '<input type="checkbox" name="device" value="'.$device['name'].'" /><br />';
		}
	?>
	</div>
	<div>
		<input type="button" value="Day" onClick="reload_chart(86400);" />
		<input type="button" value="Week" onClick="reload_chart(604800);" />
		<input type="button" value="Month" onClick="reload_chart(2592000);" />
		<input type="button" value="Year" onClick="reload_chart(31104000);" />
		<input type="button" value="All" onClick="reload_chart(0);" />
	</div>
</div>
