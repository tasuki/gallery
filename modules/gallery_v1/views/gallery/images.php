<div id="images">
	<?php
	// show all images in the current directory
	foreach ($images as $image) {
		$img = HTML::image($image['url'], array(
			'alt' => $image['title'],
		));

		$link = HTML::anchor($image['link'], $img, array(
			'title' => $image['title'],
			'data-file' => $image['file'],
		));

		echo "<div class='pic'>$link</div>";
	}
	?>
</div>
