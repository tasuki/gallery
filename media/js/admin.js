function update(url) {
	$.getJSON(url, function(data) {
		$('body').append(data['view']);

		if (data['status'] !== 'finished') {
			update(url);
		}
	}).error(function() {
		$('body').append('<p class="fatal">fatal: ajax call failed</p>');
	});
}
