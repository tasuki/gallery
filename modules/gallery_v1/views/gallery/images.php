<div id="images">
	<?php
	// show all images in the current directory
	foreach ($images as $image) {
		$link = HTML::anchor($image['link'], HTML::image($image['url']), array(
			'title' => $image['title'],
			'class' => 'fancybox',
			'rel'   => 'x',
		));

		echo "<div class='pic'>$link</div>";
	}
	?>
	<div class="clear"></div>
</div>
