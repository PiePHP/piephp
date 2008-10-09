var $ = function(selector, context) {
	var element;
	if (typeof selector == 'string') {
		if (/^#[^,]+$/.test(selector) || (/^[-_a-z0-9]+$/.test(selector) && !/^(a|abbr|acronym|address|area|b|base|bdo|big|blockquote|body|br|button|caption|cite|code|col|colgroup|dd|del|dfn|div|dl|dt|em|fieldset|form|h1|h2|h3|h4|h5|h6|head|hr|html|i|img|input|ins|kbd|label|legend|li|link|map|meta|noscript|object|ol|optgroup|option|p|param|pre|q|samp|script|select|small|span|strong|style|sub|sup|table|tbody|td|textarea|tfoot|th|thead|title|tr|tt|ul|var)$/.test(selector))) {
			element = document.getElementById(selector.replace(/#/, ''));
			$.element(element);
			return element
		}
		var elements = [];
		var selectors = selector.split(',');
		
		selector.split(/, ?/).each(function(single) {
			if (/^[a-z]+$/.test(single)) {
				$.array(document.getElementsByTagName(single)).each(function(tagElement) {
					$.element(tagElement);
					elements.push(tagElement);
				});
			}
		});
		return elements;
	}
};

$.set = function(instance, properties) {
	if (!instance) instance = {};
	if (properties) {
		for (var property in properties) {
			instance[property] = properties[property];
		}
	}
	return instance;
};

$.set($, {
	dual: function(get, type, set) {
		return new Function('v', "return typeof v=='" + (type ? type : 'string') + "'?(this." + (set ? set : get) + "=v):this." + get);
	},
	array: function(iterable) {
		var array = !iterable ? [] : iterable.toArray ? iterable.toArray() : 0;
		if (!array) {
			var length = iterable.length || 0;
			array = new Array(length);
			while (length--) {
				array[length] = iterable[length];
			}
		}
		return array;
	},
	element: function(instance) {
		$.set(instance, {
			html: $.dual('innerHTML'),
			display: $.dual('style.display'),
			show: function() {
				this.display('');
			},
			hide: function() {
				this.display('none');
			}
		});
		return instance;
	},
	ajax: function(method, url, options) {
		if (typeof options == 'function') {
			options = {ok: options};
		}
		var request = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
		request.onreadystatechange = function() {
			if (request.readyState == 4) {
				var status = 0;
				try {
					status = request.status;
				}
				catch (e) {
				}
				if ((status < 300 || status == 304) && options.ok) options.ok(request);
				if (status > 299 && status < 400) alert('HTTP ' + status);
				if (status > 399 && options.failed) options.failed(request);
				if (status > 0 && options.done) options.done(request);
			}
		}
		request.open(method, url, true);
		
		var headers = {
			'X-Requested-With': 'XMLHttpRequest',
			'Accept': 'text/javascript, text/html, application/xml, text/xml, */*'
		};
		
		var data = null;
		if (options.data) {
			if (typeof options.data == 'string') {
				data = options.data;
			}
			else {
				options.data.each(function(name, value) {
					data = (data ? data + '&' : '') + name + '=' + escape(value);
				});
			}
			headers['Content-length'] = data.length;
			headers['Content-type'] = 'application/x-www-form-urlencoded';
		}
		
		$.set(headers, options.headers);
		
		headers.Connection = 'close';
		
		headers.each(function(name, value) {
			request.setRequestHeader(name, value);
		});
		request.send(data);
	},
	get: function(url, options) {
		$.ajax('GET', url, options);
	},
	post: function(url, options) {
		$.ajax('POST', url, options);
	}
});

$.set(Object.prototype, {
	
	each: function(callback) {
		var message = '';
		for (var property in this) {
			if (Object.prototype[property]) continue;
			callback(property, this[property]);
		}
	},
	
	echo: function(level, maxLevel) {
		level = level ? level : 0;
		var message = '';
		for (var property in this) {
			message += property + ': ' + this[property] + "\n";
		}
		return level ? message : alert(message); 
	}
	
});

$.set(Array.prototype, {
	
	each: function(callback) {
		for (var i = 0; i < this.length; i++) {
			callback(this[i], i);
		}
	},
	
	first: function(value) {
		return this[0];
	},
	
	last: function(value) {
		return this[this.length - 1];
	},
	
	add: function(value) {
		if (typeof value == 'array') {
			for (var i in value) {
				this.push(value[i]);
			}
		}
		else this.push(value);
	}
	
});
