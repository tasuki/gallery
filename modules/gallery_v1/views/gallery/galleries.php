<div id="galleries">
	<?php
	// show all subgalleries of the current gallery
	foreach ($galleries as $url => $title) {
		echo HTML::anchor($url, $title, array('class' => 'box'));
	}
	?>
</div>
