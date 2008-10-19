var Refresher = {
	
	files: [],
	
	setup: function(time, files) {
		var refresher = Refresher;
		refresher.time = time;
		refresher.files.add(files);
		
		$('img').each(function(i, img) {
			refresher.files.push(img.href);
		});
		setTimeout(refresher.poll, 100);
	},
	
	poll: function() {
		$.get('/refresher?Time=' + Refresher.time + '&Files=' + escape(Refresher.files), {
			ok: function(response) {
				//alert(response.responseText);
				if (response.responseText == 'true') {
					var loc = window.location;
					loc.href = '' + loc.href;
				}
				setTimeout(Refresher.poll, 500);
			}
		});
	}
	
};
