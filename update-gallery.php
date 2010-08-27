<html>
<head>
	<title>update gallery</title>
	<script type="text/javascript">

		function results(text) {
			var res = document.getElementById('results');
			res.innerHTML = text;

			if (res.innerHTML.match(/FINISHED/)) {
				return;
			} else {
				var dots = document.getElementById('dots');
				dots.innerHTML = dots.innerHTML + ' ...';

				HttpRequest('<?php echo $conf['basedir'] . 'reload'; ?>');
			}
		}

		function HttpRequest(url){
			var pageRequest = false;
			/*@cc_on
				@if (@_jscript_version >= 5)
					try {
						pageRequest = new ActiveXObject("Msxml2.XMLHTTP");
					} catch (e) {
						try {
							pageRequest = new ActiveXObject("Microsoft.XMLHTTP");
						} catch (e2){
							pageRequest = false;
						}
					}
				@end
			@*/

			if (!pageRequest && typeof XMLHttpRequest != 'undefined')
				pageRequest = new XMLHttpRequest();

			if (pageRequest) {
				pageRequest.open('GET', url, false);
				pageRequest.send(null);
				results(pageRequest.responseText);
			}
		}

	</script>
</head>
<body>
Please wait <span id="dots"></span>
<div id="results" style="border: 5px solid #000; margin: 10px; padding: 10px;"></div>
<script type="text/javascript">
results('results will be displayed here');
</script>
</body>
</html>
