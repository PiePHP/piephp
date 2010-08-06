(function() {

	var base = location.protocol + '//' + location.host;
	var currentPath = '/';
	var loadCount = 0;

	var getPath = function(href) {
		return href.substring(base.length).replace(/^\/#/, '');
	};

	var noIndex = function(href) {
		return href.replace(/\/index\.php/, '');
	};

	var loadUrl = function(href) {
		var bodySectionQuery = $('#body .section');
		bodySectionQuery.css({opacity: 0.25});
		$('#body>div').addClass('loading');
		var path = getPath(href);
		var currentLoad = ++loadCount;
		$.get(href, {}, function(html) {
			if (currentLoad == loadCount) {
				var load = function() {
					loadContent(path, html);
				};
				if (bodySectionQuery.html()) {
					load()
				}
				else {
					$(load);
				}
			}
		});
	};

	var loadContent = function(path, html) {
		var bodySectionQuery = $('#body .section');
		currentPath = path;
		$('#body>div').removeClass('loading');
		bodySectionQuery.html(html);
		document.location = base + '/#' + path;
		document.title = $('#body var').text();
		$('#loading').remove();
		bodySectionQuery.css({opacity: 1});
		$('body')[0].id = (noIndex(path) == '/' ? 'home' : '');
		lightTab();
	};

	var checkUrl = function() {
		var path = getPath(location.href);
		if (path != currentPath) {
			loadUrl(location.href.replace(/\/#/, ''));
		}
	};

	var lightTab = function() {
		$('#head li.on').removeClass('on');
		$('#head li a').each(function(linkIndex, link) {
			var light = 0;
			var linkPath = getPath(link.href);
      console.log('linkPath' + noIndex(linkPath));
      console.log('currentPath' + noIndex(currentPath));
			if (noIndex(linkPath) == '/') {
				if (noIndex(currentPath) == '/') {
					light = 1;
				}
			}
			else if (currentPath.substring(0, linkPath.length) == linkPath) {
				light = 1;
			}
			if (light) {
				$(link).parent().addClass('on');
			}
		});
	};

	checkUrl();

	$('body')
		.delegate('a', 'click', function() {
			var link = this;
			var href = link.href;
			if (href.charAt(0) == '/') {
				href = base + href;
			}
			if (href.substring(0, base.length) == base) {
				loadUrl(href);
				$(link).blur();
				return false;
			}
		})
		.delegate('fieldset', 'focus', function() {
			$(this).addClass('on');
		})
		.delegate('fieldset', 'blur', function() {
			$(this).removeClass('on');
		})
		.delegate('input,select,textarea,submit', 'click', function(event) {
			console.log('input click');
			event.stopImmediatePropagation();
		})
		.delegate('fieldset', 'click', function(event) {
			console.log('fieldset click');
			$(this).find('input,select,textarea,submit').eq(0).focus();
		});

	setInterval(checkUrl, 100);

	$(lightTab);

})();
