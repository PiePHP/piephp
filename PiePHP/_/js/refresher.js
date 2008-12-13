var Refresher = {
	
	setup: function(time, files) {
		$('img').each(function() {
			var src = this.src.replace(/^http[s]?:\/\/[^\/]+/, '');
			files.push(src);
		});
		var refresher = Refresher;
		refresher.time = time;
		var pattern = new RegExp('\/[^\/]+/\.\.\/');
		refresher.files = files;
		setTimeout(function() {
			refresher.poll(0);
		}, 100);
	},
	
	poll: function(count) {
		$.get('/_/refresher', {
			time: Refresher.time,
			files: '' + Refresher.files,
			count: ++count
		}, function(text) {
			if (text == 'true') {
				window.location.reload(true);
			}
			setTimeout(function() {
				Refresher.poll(++count);
			}, 500);
		});
	}
	
};