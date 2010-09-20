if (!window.log) (function(window) {

	var document = window.document;

	var location = window.location;

	var base = location.protocol + '//' + location.host;

	var currentPath = '/';

	var loadCount = 0;

	var isLoading = 0;

	var loadedInitialPath = 0;

	var formFieldsSelector = 'input,select,textarea,button';

	var focusableSelector = formFieldsSelector + ',a';

	var extend = function(object, properties) {
		for (var name in properties) {
			object[name] = properties[name];
		}
	};

	extend(String.prototype, {
		has: function(text) {
			return this.indexOf(text) > -1;
		}
	});

	extend(Array.prototype, {
		each: function(callback) {
			for (var i = 0; i < this.length; i++) {
				callback(i, this[i]);
			}
		}
	});

	if (location.href.has('isAjax')) {
		return;
	}

	var whitespaceSplit = function(string) {
	  return ('' + string).trim().split(/\s+/);
	};

	var log = window.log = function(message, value) {
		if (window.console) {
  		console.log(typeof(value) == 'undefined' ? message : message + ': ' + value);
		}
	};

	var time = function() {
		return (new Date()).getTime();
	};

	var getPath = function(href) {
		return href.substring(base.length).replace(/^\/#/, '');
	};

	var noIndex = function(href) {
		return href.replace(/\/index\.php/, '');
	};

	var wireTasks = [function(query) {
		query.find('[title]')
			.each(function(elementIndex, element) {
				element.HINT = element.title;
				element.title = '';
				var hasValue = element.value;
				$(element).addClass(hasValue ? 'hinted' : 'hint');
				if (!hasValue) {
					element.value = element.HINT;
				}
			});
		query.find('form').append('<input type="hidden" name="isAjax"/>');
	}];

	var wire = window.wire = function(selectorOrCallback) {
		if (typeof selectorOrCallback == 'function') {
			wireTasks.push(selectorOrCallback);
		}
		else {
			var query = $(selectorOrCallback);
			wireTasks.each(function(taskIndex, task) {
				task(query);
			});
			return query;
		}
	};

	var loadUrl = function(href, isForm) {
		var bodyQuery = $('#body');
		bodyQuery.children().css({opacity: 0.25});
		$('#body>div').eq(0).addClass('loading');
		var path = getPath(href);
		var currentLoad = ++loadCount;

		if (!isForm) {
			isLoading = 1;
			$.get(href, {isAjax: 1}, function(html) {
				if (currentLoad == loadCount) {
					isLoading = 0;
					var load = function() {
						loadContent(path, html);
					};
					if (bodyQuery.html()) {
						load();
					}
					else {
						$(load);
					}
				}
			});
		}
	};

	var loadContent = function(path, html) {
		hideVeil();
		window.scrollTo(0, 0);
		var bodyQuery = $('#body');
		currentPath = path;
		$('#body>div').removeClass('loading');
    var htmlQuery = $(html);
    if (htmlQuery.find('#body').size()) {
      htmlQuery = htmlQuery.find('#body');
    }
    wire(bodyQuery.html(htmlQuery));
		document.location = base + '/#' + path;
		document.title = $('#title').text();
		$('#loading').remove();
		lightTab();
	};

	var checkUrl = function() {
		var path = getPath(location.href);
		if (path != currentPath && !isLoading) {
			loadUrl(location.href.replace(/\/#/, ''));
		}
	};

	var lightTab = function() {
		$('#nav span.on a').removeClass('on').stop().animate({opacity: 0});
		$('#nav span.on a').each(function(linkIndex, link) {
			var light = 0;
			var linkPath = getPath(link.href);
			if (currentPath.substring(0, linkPath.length) == linkPath) {
				light = 1;
			}
			if (light) {
				$(link).addClass('on').stop().animate({opacity: 1});
			}
		});
	};

	setInterval(checkUrl, 100);

	var stopPropagation = function(event) {
		event.stopPropagation();
		return false;
	};

	var validationRules = {
		eval: function(ruleName, value, data) {
			var rule = validationRules[ruleName];
			var type = typeof(rule);
			try {
				if (type == 'function') {
					return rule(value, data);
				}
				if (type == 'object') {
					for (var r in rule) {
						if (!validationRules.eval(r, value, rule[r])) {
							return false;
						}
					}
				}
			}
			catch (e) {
				log('Could not evaluate validation rule', e);
			}
			return true;
		},
		required: function(v, d) { return v.trim() && !$(d).hasClass('hint'); },
		empty: function(v) { return !v.trim(); },
		is: function(v, d) { return v == d; },
		isNot: function(v, d) { return v != d; },
		same: function(v, d) { return v == $('#' + d).val(); },
		isNotEmpty: function(v, d) { return $('#' + d).val().trim(); },
		min: function(v, d) { return v >= parseFloat(d); },
		max: function(v, d) { return v <= parseFloat(d); },
		nonNegative: {min: 0},
		positive: {min: 1},
		minlength: function(v, d) { return v.length >= d; },
		maxlength: function(v, d) { return v.length <= d; },
		test: function(v, d) { return d.test(v); },
		username: {test: /^[a-zA-Z0-9_\.-]+$/},
		password: {minlength: 4},
		newPassword: {isNotEmpty: 'password2'},
		passwordConfirm: {isNotEmpty: 'password1', same: 'password1'},
		email: {test: /^(?!\.)([a-zA-Z0-9_\.\-#\$%\*\/\?\|\^\{\}`~&\'\+\-=_])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/},
		phone: {test: /^\+?[\d \.x\-]{8,20}$/},
		addressLine: {maxlength: 50},
		postcode: {minlength: 2, maxlength: 10},
		shortDescription: {maxlength: 2500}
	};

	var validateFieldDiv = function() {
		var fieldDiv = this;
		var fieldDivQuery = $(fieldDiv);
		formQuery = fieldDivQuery.closest('form');
		fieldsQuery = fieldDivQuery.find(formFieldsSelector);
		// If the form has been submitted, or if all fields in this fieldDiv have changed, we can validate.
		if (formQuery.hasClass('submitted') || !fieldsQuery.not('.changed').size()) {

			var adviceQuery = fieldDivQuery.find('.advice');
			if (!adviceQuery.size()) {
				adviceQuery = $('<div class="advice"/>').appendTo(fieldDivQuery);
			}
			if (!adviceQuery.text()) {
				adviceQuery.text(fieldDiv.title || 'Please enter your information.');
			}

			var isValid = true;
			fieldsQuery.each(function(fieldIndex, field) {
				var value = $(field).val();
				var className = field.className;
				if (!className.has('optional') || value.trim()) {
					whitespaceSplit(className).each(function(ruleIndex, rule) {
						isValid = isValid && validationRules.eval(rule, value, field);
					});
				}
				/*
				if (isValid && className.has('ajax')) {
					clearTimeout(field.timer);
					field.timer = setTimeout(function() {
						if (value != field.searched) {
							var data = {field: name};
							data[name] = value;
							$.get(contextPath + '/login/register/check/validate', data, function(json) {
								isValid = json.result;
								$('#advice-' + name + '-ajax')
									.text(isValid ? '' : json.messages[0])
									[isValid ? 'removeClass' : 'addClass']('error')
									[isValid ? 'hide' : 'fadeIn']();
							}, 'json');
						}
						field.searched = value;
					}, 200);
				}
				*/
			});
			adviceQuery
				[isValid ? 'removeClass' : 'addClass']('error')
				[isValid ? 'hide' : 'fadeIn']();
		}
	};

	var showVeil = window.showVeil = function(href) {
		$('#veil')
			.fadeIn()
			.removeClass('submitted')
			.html('<div class="veil"/><div id="dialog"/>')
			.find('.veil')
				.css({opacity: 0.8});
		veilLoading();
		$.get(href, {isAjax: 1, isDialog: 1}, loadVeil);
	};

	var loadVeil = window.loadVeil = function(html) {
		var dialogQuery = $('#dialog').html(html).css({opacity: 1});
		dialogQuery
			.prepend($('<div id="dialogClose"/>').click(hideVeil))
			.css({marginLeft: -dialogQuery.width() / 2})
			.find('br:last').remove();
			$('#veil')
				.attr({method: 'post', action: dialogQuery.find('var[title=action]').text()});
		focusFirst(dialogQuery);
	};

	var veilLoading = function() {
		loadVeil('<div class="loading">Loading</div>');
	};

	var hideVeil = window.hideVeil = function() {
		$('#veil').fadeOut('fast', function() {
			$(this).empty();
		});
	};

	wire(function() {
		$('form').not('.hasValidation')
			.addClass('hasValidation')
			.each(function(formIndex, form) {
				form.validate = function() {
					var formQuery = $(form);
					formQuery.addClass('submitted');
					formQuery.find('.error').removeClass('error');
					formQuery.find('fieldset>div').each(validateFieldDiv);
					return !formQuery.find('.error').size();
				};
			})
			.submit(function(event) {
				if (this.validate) {
					var isValid = this.validate();
					if (!isValid) {
						event.stopImmediatePropagation();
					}
					else if (this.id == 'veil') {
						setTimeout(veilLoading, 1);
					}
					return isValid;
				}
			})
			.delegate(formFieldsSelector, 'change', function() {
				$(this).addClass('changed');
			})
			.delegate('fieldset>div', 'click blur mouseup keyup change', validateFieldDiv);
	});

	var focusFirst = function(selector) {
		var focusableQuery = $(selector).find(focusableSelector);
		if (!focusableQuery.filter('.focused').size()) {
			focusableQuery.eq(0).focus();
		}
	};

	$(document)
		.delegate('a', 'click', function() {
			var link = this;
			var href = link.href;
			if ($(this).hasClass('veil')) {
				showVeil(href);
				return false;
			}
			else if ($(this).hasClass('noAjax') || this.id.has('uservoice')) {
				return true;
			}
			else {
				if (href.charAt(0) == '/') {
					href = base + href;
				}
				if (href.substring(0, base.length + 1) == base + '/') {
					loadUrl(href);
					$(link).blur();
					return false;
				}
			}
		})
		.delegate('form', 'submit', function(event) {
			// Mimic AJAX posting by submitting the form through the "submitter" iframe
			if ($('#submitter').size()) {
				var form = this;
				var action = form.action;
				if (action.charAt(0) == '/') {
					action = base + action;
				}
				if (action.substring(0, base.length) == base) {
					var target = form.target;
          form.isAjax.value = 1;
					form.action += (action.has('?') ? '&' : '?') + 'isAjax=1&isFrame=1';
					form.target = 'submitter';
					setTimeout(function() {
						form.action = action;
						form.target = target;
					}, 1);
					loadUrl(action, 1);
				}
			}
		})
		.delegate(focusableSelector, 'focus', function(event) {
			$(this).addClass('focused');
		})
		.delegate(focusableSelector, 'blur', function(event) {
			$(this).removeClass('focused');
		})
		.delegate('fieldset>div', 'focus', function(event) {
			$('fieldset>div.on').removeClass('on');
			$(this).addClass('on');
		})
		.delegate('fieldset>div', 'blur', function(event) {
			$(this).removeClass('on');
		})
		.delegate('fieldset>div', 'click', function(event) {
			focusFirst(this);
		})
		.delegate('.hint', 'focus', function() {
			$(this).removeClass('hint').addClass('hinted').val('');
		})
		.delegate('.hinted', 'blur', function() {
			if (!this.value) {
				$(this).removeClass('hinted').addClass('hint').val(this.HINT);
			}
		})
		.delegate('form', 'submit', function() {
			$(this).find('.hint').val('');
		})
		.delegate('.password.new', 'focus keypress keyup mouseup', function() {

		})
		.keydown(function(event) {
			if (event.keyCode == 27) {
				hideVeil();
			}
		});

	$().ajaxError(function(event, request, options, error) {
		log('ajaxError', arguments);
	});

	$('<iframe name="submitter" id="submitter" style="display:none"/>')
		.appendTo('body')
		.load(function() {
			var doc = this.contentWindow.document;
			var href = doc.location.href;
			if (href != 'about:blank' && !href.has('errors/rewrite')) {
				var path = getPath(href).replace(/[\?&]isAjax=[01]/, '');
				loadContent(path, doc.body.innerHTML);
			}
		});
	checkUrl();
	var navQuery = $('#nav');
	var navSpanQuery = navQuery.find('span');
	navSpanQuery.clone().addClass('hover').appendTo(navQuery).find('a').css({opacity: 0, zIndex: 98});
	navSpanQuery.clone().addClass('on').appendTo(navQuery).find('a').css({opacity: 0, zIndex: 99});
	var animateHover = function(event) {
		var isEnter = event.type.has('r');
		navQuery.find('span.hover a').eq($(this).index())
			.stop()
			.animate({opacity: isEnter ? 1 : 0}, isEnter ? 'fast' : 'slow');
	};
	navQuery
		.delegate('a', 'mouseenter', animateHover)
		.delegate('a', 'mouseleave', animateHover);
	loadedInitialPath = 1;
	lightTab();
	wire(document);

	var appendScript = window.appendScript = function(src) {
		var scriptQuery = $('<' + 'script type="text/javascript" src="' + src + '"/>');
		scriptQuery[0].async = true;
		$('head').append(scriptQuery);
	};


})(window);
