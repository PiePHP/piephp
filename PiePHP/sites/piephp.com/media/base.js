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

	var whitespaceSplit = function(string) {
	  return ('' + string).trim().split(/\s+/);
	};

	if (location.href.has('is_ajax')) {
		return;
	}

	var log = window.log = function(message, value) {
		if (window.console) {
			console.log(message + (typeof value != 'undefined' ? ': ' + value : ''));
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
		var bodySectionQuery = $('#body .section');
		bodySectionQuery.children().css({opacity: 0.25});
		$('#body>div').addClass('loading');
		var path = getPath(href);
		var currentLoad = ++loadCount;
		var isHome = noIndex(path) == '/';
		if ($.support.opacity && loadedInitialPath) {
			$('#logo').stop().animate({width: isHome ? 234 : 117, height: isHome ? 60 : 30, top: isHome ? 12 : 6});
			$('#head ul').stop().animate({top: isHome ? 56 : 16});
			$('#head').stop().animate({height: isHome ? 79 : 39});
		}

		if (!isForm) {
			isLoading = 1;
			$.get(href, {is_ajax: 1}, function(html) {
				if (currentLoad == loadCount) {
					isLoading = 0;
					var load = function() {
						loadContent(path, html, isHome);
					};
					if (bodySectionQuery.html()) {
						load();
					}
					else {
						$(load);
					}
				}
			});
		}
	};

	var loadContent = function(path, html, isHome) {
		window.scrollTo(0, 0);
		var bodySectionQuery = $('#body .section');
		currentPath = path;
		$('#body>div').removeClass('loading');
		wire(bodySectionQuery.html(html));
		document.location = base + '/#' + path;
		document.title = $('#body var').text();
		$('#loading').remove();
		$('body')[0].id = isHome ? 'home' : '';
		lightTab();
	};

	var checkUrl = function() {
		var path = getPath(location.href);
		if (path != currentPath && !isLoading) {
			loadUrl(location.href.replace(/\/#/, ''));
		}
	};

	var lightTab = function() {
		$('#head li.on').removeClass('on').stop().animate({opacity: 0});
		$('#head ul.on a').each(function(linkIndex, link) {
			var light = 0;
			var linkPath = getPath(link.href);
			if (noIndex(linkPath) == '/') {
				if (noIndex(currentPath) == '/') {
					light = 1;
				}
			}
			else if (currentPath.substring(0, linkPath.length) == linkPath) {
				light = 1;
			}
			if (light) {
				$(link).parent().addClass('on').stop().animate({opacity: 1});
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
		username: {test: /^[a-zA-Z0-9_\.]+$/},
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
				log('validate', field.name);
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
					return isValid;
				}
			})
			.delegate(formFieldsSelector, 'change', function() {
				$(this).addClass('changed');
			})
			.delegate('fieldset>div', 'click blur mouseup keyup change', validateFieldDiv);
	});

	$(document)
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
		.delegate('form', 'submit', function(event) {
			// Mimic AJAX posting by submitting the form through the "submitter" iframe
			log('submitting');
			if ($('#submitter').size()) {
				var form = this;
				var action = form.action;
				if (action.charAt(0) == '/') {
					action = base + action;
				}
				if (action.substring(0, base.length) == base) {
					var target = form.target;
					form.target = 'submitter';
					form.action += (action.has('?') ? '&' : '?') + 'is_ajax=1';
					log('target', form.target);
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
			var focusableQuery = $(this).find(focusableSelector);
			if (!focusableQuery.filter('.focused').size()) {
				focusableQuery.eq(0).focus();
			}
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
			
		});

	$().ajaxError(function(event, request, options, error) {
		log('event', event);
		log('request', request);
		log('options', options);
		log('error', error);
	});

	$(function() {
		$('<iframe name="submitter" id="submitter" style="display:none"/>')
			.appendTo('body')
			.load(function() {
				var doc = this.contentWindow.document;
				var path = getPath(doc.location.href).replace(/[\?&]is_ajax=[01]/, '');
				loadContent(path, doc.body.innerHTML);
			});
		checkUrl();
		var headSectionQuery = $('#head .section');
		var navQuery = headSectionQuery.find('ul');
		navQuery.clone().addClass('hover').appendTo(headSectionQuery).find('li').css({opacity: 0});
		navQuery.clone().addClass('on').appendTo(headSectionQuery).find('li').css({opacity: 0});
		var animateHover = function(event) {
			var isEnter = event.type.has('over');
			headSectionQuery.find('ul.hover li').eq($(this).index())
				.stop()
				.animate({opacity: isEnter ? 1 : 0}, isEnter ? 'fast' : 'slow');
		};
		headSectionQuery
			.delegate('li', 'mouseenter', animateHover)
			.delegate('li', 'mouseleave', animateHover);
		loadedInitialPath = 1;
		lightTab();
		wire(document);
	});

})(window);
