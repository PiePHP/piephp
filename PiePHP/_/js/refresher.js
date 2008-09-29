var Refresher = {
	
	files: [],
	
	start: function(time, files) {
		var refresher = Refresher;
		refresher.time = time;
		refresher.files = $.merge(refresher.files, files);
		
		$('img').each(function(i, img) {
			refresher.files.push(img.href);
		});
		setTimeout(refresher.poll, 100);
	},
	
	poll: function() {
		var url = '/refresher?Time=' + Refresher.time + '&Files=' + escape(Refresher.files);
		$.get(url, {Time: Refresher.time, Files: '' + Refresher.files}, function(data) {
			if (data == 'true') {
				var loc = window.location;
				loc.href = '' + loc.href;
			}
			setTimeout(Refresher.poll, 100);
		});
	}
	
};