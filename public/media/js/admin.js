function type_class(type) {
	switch (type) {
		case "image":
			return "info";
		case "thumb":
			return "info";
		case "copy":
			return "info";
		default:
			return type;
	}
}

function print_message(type, message) {
	$('#content').append($(document.createElement('p'))
		.text(type + ': ' + message)
		.addClass(type_class(type))
	);
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
			if (type == 'error') {
				errors += 1;
			}
		}

		if (data['reload']) {
			update(url);
		} else {
			print_finished(errors);
		}
	}).fail(function() {
		errors += 1;
		print_message('error', 'ajax call failed');
		print_finished(errors);
	});
}

$(document).ready(function(){
	update("/admin/update_file");
});
