<div class="navigation">
<?php
foreach ($neighbors as $type => $neighbor) {
	if ($type == 'prev') { // previous gallery
		$title = "&lsaquo; {$neighbor['title']}";
	} else { // next gallery
		$title = "{$neighbor['title']} &rsaquo;";
	}

	echo HTML::anchor($neighbor['link'], $title, array(
		'id'    => $type,
		'class' => '',
	));
}
?>
	<div class="clear"></div>
</div>
