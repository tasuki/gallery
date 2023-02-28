function print_message(type, message) {
	$('body').append($(document.createElement('p'))
		.text(type + ': ' + message)
		.addClass(type));
}

function print_finished(errors) {
	switch (errors) {
		case 0:
			print_message('info',
				'Succesfully finished updating!');
			break;
		case 1:
			print_message('info',
				'There has been one error processing your photos!');
			break;
		default:
			print_message('info',
				'There have been ' + errors
				+ ' errors processing your photos!');
	}
}

var errors = 0;
function update(url) {
	$.getJSON(url, function(data) {
		for (type in data['results']) {
			print_message(type, data['results'][type]);
			if (type == 'error' || type == 'fatal') {
				errors += 1;
			}
		}

		if (data['reload']) {
			update(url);
		} else {
			print_finished(errors);
		}
	}).error(function() {
		errors += 1;
		print_message('fatal', 'ajax call failed');
		print_finished(errors);
	});
}
