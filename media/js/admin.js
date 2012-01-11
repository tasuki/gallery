function update(url) {
	$.getJSON(url, function(data) {
		$('body').append(data['view']);

		if (data['reload']) {
			update(url);
		}
	});
}
