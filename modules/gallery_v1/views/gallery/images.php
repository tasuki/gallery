<div id="images">
	<?php
	// show all images in the current directory
	foreach ($images as $image) {
		// empty class to fix jQuery UI addclass() on first hover
		$img = HTML::image($image['url'], array(
			'alt'   => $image['title'],
			'class' => '',
		));
		$link = HTML::anchor($image['link'], $img, array(
			'title'     => $image['title'],
			'data-file' => $image['file'],
			'class'     => 'fancybox',
			'rel'       => 'section',
		));

		echo "<div class='pic'>$link</div>";
	}
	?>
	<div class="clear"></div>
</div>
