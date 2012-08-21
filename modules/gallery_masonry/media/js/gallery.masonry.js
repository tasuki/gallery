$(document).ready(function() {
	$('#images').imagesLoaded(function() {
		this.masonry({
			itemSelector : '.pic',
			columnWidth : 10,
			isAnimated : true,
			layoutPriorities : {
				shelfOrder : 2
			}
		});
	});
});
