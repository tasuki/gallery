$(document).ready(function() {
	$('#images').imagesLoaded(function() {
		$('#images').masonry({
			itemSelector : '.pic',
			columnWidth : 10,
			isAnimated : true
		});
	});
});
