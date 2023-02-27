$(document).ready(function() {
	var base = location.href.replace(/#.*/, '');
	var images = $('#images a');

	baguetteBox.run("#images", {
		'overlayBackgroundColor' : 'rgba(100, 100, 100, 0.95)',
		'onChange' : function(index, count) {
			var hash = '#' + $(images[index]).attr('data-file');
			history.replaceState('', '', base + hash);
		},
		'afterHide' : function() {
			history.replaceState('', '', base);
		},
	});

	// load image, if linked
	$('#images a[data-file="' + location.hash.substr(1) + '"] img').click();
});
