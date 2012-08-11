<div class="navigation">
	<?php
	// show breadcrumbs
	foreach ($crumbs as $url => $title)
		if ($url)
			// empty class to fix jQuery UI addclass() on first hover
			echo HTML::anchor($url, $title, array('class' => '')) . " &raquo; ";
		else
			echo "<span class='crumb'>$title</span>";
	?>
	<div class="clear"></div>
</div>
