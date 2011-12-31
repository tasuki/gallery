<div id="breadcrumbs">
	<?php
	// show breadcrumbs
	foreach ($crumbs as $url => $title)
		if ($url)
			echo HTML::anchor($url, $title) . " &raquo; ";
		else
			echo "<span class='crumb'>$title</span>";
	?>
	<div class="clear"></div>
</div>
