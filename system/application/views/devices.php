<div>
<ul>
<?php foreach($devices as $dev) { ?>
	<li><a href="devices/info/<?=$dev['id']?>"><?=$dev['name']?></a></li>
<?php } ?>
</ul>
