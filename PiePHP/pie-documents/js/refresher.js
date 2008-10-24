var Refresher = {
	
	files: [],
	
	setup: function(time, files) {
		var refresher = Refresher;
		refresher.time = time;
		refresher.files.add(files);
		
		$('img').each(function(i, img) {
			refresher.files.push(img.href);
		});
		setTimeout(function() {
			refresher.poll(0);
		}, 100);
	},
	
	poll: function(count) {
		$.get('/refresher?time=' + Refresher.time + '&files=' + escape(Refresher.files) + '&count=' + (++count), {
			ok: function(response) {
				if (response.responseText == 'true') {
					window.location.reload(true);
				}
				setTimeout(function() {
					Refresher.poll(++count);
				}, 500);
			}
		});
	}
	
};
