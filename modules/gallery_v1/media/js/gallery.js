$(document).ready(function(){
	$("#images a").fancybox({
		'margin'         : 0,
		'padding'        : 0,
		'changeSpeed'    : 100,
		'overlayShow'    : true,
		'overlayOpacity' : 0.95,
		'titlePosition'  : 'over',
		'transitionIn'   : 'elastic',
		'transitionOut'  : 'elastic'
	});

	var anim = function(ancestor, action) {
		var el = ancestor.find('img');
		if (el.length == 0) { el = ancestor; }
		el[action]('hover', 'fast');
	}

	$("a").hover(function(){
		anim($(this), "addClass");
	}, function(){
		anim($(this), "removeClass");
	});
});
