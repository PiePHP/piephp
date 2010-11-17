/**
 * This base javascript library currently does many things (too many things).
 *  - AJAX-based persistent page framework
 *  - JavaScript console logging
 *  - Helpful prototype methods
 *  - Form validation
 *  - Veil dialogs
 *  - Navigation highlighting
 *
 * This needs to be broken up into multiple libraries.  The best time for this
 * reorganization effort will be when when automated JS file merging and
 * compression is implemented, using Closure Compiler.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

/**
 * The base URL is used for comparison against link HRefs and form actions to determine
 * whether they're pointing to the same host and are therefore AJAXable.
 */
var baseUrl = location.protocol + '//' + location.host;

/**
 * Hopefully, mod_rewrite has enabled us to use "/" as a dispatcher.
 * If not, the urlRoot can be set in the default template.
 */
var urlRoot = window.urlRoot || '/';

/**
 * The path of the current page helps us to track whether the user has put something
 * new into the address bar, necessitating an AJAX get.
 */
var currentPath = '/';

/**
 * When we begin loading new AJAX content, we can use a counter for comparison upon
 * receiving a response to determine whether it is stale (and should be discarded).
 */
var loadCount = 0;

/**
 * If we are loading new AJAX content, we can expect a URL change and know to ignore
 * it because it was not a user-entered URL.
 */
var isLoading = 0;

/**
 * jQuery selector for form fields that we will use.
 */
var formFieldsSelector = 'input,select,textarea,button';

/**
 * jQuery selector for some types of elements that can take focus.
 */
var focusableSelector = formFieldsSelector + ',a';

/**
 * "Extend" an object by applying new properties to it (and overwriting any existing ones).
 */
var extend = function(object, properties) {
	for (var name in properties) {
		object[name] = properties[name];
	}
};

/**
 * String.has(substring) is true if the string has at least one instance of the substring.
 */
extend(String.prototype, {
	has: function(text) {
		return this.indexOf(text) > -1;
	}
});

/**
 * Array.each helps us walk through an array with a callback function.
 */
extend(Array.prototype, {
	each: function(callback) {
		for (var i = 0; i < this.length; i++) {
			callback(i, this[i]);
		}
	}
});

/**
 * Break up a string by its whitespace.
 */
var whitespaceSplit = function(string) {
	return ('' + string).trim().split(/\s+/);
};

/**
 * The log prints messages to the console in browsers that support it.
 * It exposes itself to other scripts via the window object.
 */
var log = window.log = function(message, value) {
	if (window.console) {
		console.log(typeof(value) == 'undefined' ? message : message + ': ' + value);
	}
};

/**
 * Get the current time for time measurement tasks.
 */
var time = function() {
	return (new Date()).getTime();
};

/**
 * Record the time when JavaScript on this page started processing.
 */
var startTime = time();

// TODO: Continue commenting this library (and ideally break it into smaller pieces as well.
var getPath = function(href) {
	return href.substring(baseUrl.length).replace(/^\/#/, '');
};

var noIndex = function(href) {
	return href.replace(/\/index\.php/, '');
};

var wireTasks = [function(query) {
	query.find('[title]').not('.gAd')
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
	currentPath = path;
	document.location = baseUrl + '/#' + path;
	var bodyQuery = $('#body');
	$('#body>div').removeClass('loading');
	var htmlQuery = $(html);
	if (htmlQuery.find('#body').size()) {
		htmlQuery = htmlQuery.find('#body');
	}
	wire(bodyQuery.html(htmlQuery));
	document.title = $('#title').text();
	$('#loading').remove();
	lightTab();
	trackPageview(baseUrl + path);
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
				log(data.name + '.' + ruleName, 'function');
				return rule(value, data);
			}
			if (type == 'object') {
				for (var r in rule) {
					if (!validationRules.eval(r, value, rule[r])) {
						log(data.name + '.' + ruleName, 'object');
						return false;
					}
				}
			}
		}
		catch (e) {
			log(data.name + '.' + ruleName, 'caught');
			log('Could not evaluate validation rule', e);
		}
		return true;
	},
	required: function(v, d) { return v.trim() && !$(d).hasClass('hint'); },
	empty: function(v) { return !v.trim(); },
	is: function(v, d) { return v == d; },
	isNot: function(v, d) { return v != d; },
	isNotEmpty: function(v, d) { return $('#' + d).val().trim(); },
	min: function(v, d) { return v >= parseFloat(d); },
	max: function(v, d) { return v <= parseFloat(d); },
	nonNegative: {min: 0},
	positive: {min: 1},
	minlength: function(v, d) { return v.length >= d || v.length < 1; },
	maxlength: function(v, d) { return v.length <= d; },
	test: function(v, d) { return d.test(v); },
	username: {test: /^[a-zA-Z0-9_\.-]+$/},
	password: {minlength: 4},
	confirmPassword: function(v, d) { return d.form[d.name.replace(/confirm/, 'new')].value == v; },
	currentPassword: function(v, d) { return d.form[d.name.replace(/current/, 'new')].value.trim() ? v.length > 4 : 1; },
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

	//log(fieldsQuery);

	// If the form has been submitted, or if all fields in this fieldDiv have changed, we can validate.
	if (formQuery.hasClass('submitted') || !fieldsQuery.not('.changed').size()) {

		var adviceQuery = fieldDivQuery.find('.advice');
		if (!adviceQuery.size()) {
			adviceQuery = $('<div class="advice"/>').appendTo(fieldDivQuery);
		}
		if (!adviceQuery.text()) {
			adviceQuery.text('Please enter your information.');
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
		});
		adviceQuery
			[isValid ? 'removeClass' : 'addClass']('error')
			[isValid ? 'fadeOut' : 'fadeIn']();
	}
};

var showVeil = window.showVeil = function(href) {
	$('#form')
		.fadeIn()
		.removeClass('submitted')
		.html('<div id="veil"/><div id="shadow"/><div id="dialog"><table><tr><td id="cell"/></tr></table></div>');
	$('#veil')
		.css({opacity: 0.8});
	veilLoading();
	$.get(href, {isAjax: 1, isDialog: 1}, loadVeil);
};

var loadVeil = window.loadVeil = function(html) {
	var dialogQuery = $('#dialog');
	$('#cell')
		.html(html).css({opacity: 1})
		.append($('<button id="close" class="main"><b id="x">X</b></button>').click(function() {
			hideVeil();
		}).focus(function() {
			return false;
		}));
	dialogQuery
		.css({marginLeft: -dialogQuery.width() / 2})
		.find('br:last').remove();
	$('#form').attr({method: 'post', action: dialogQuery.find('var[title=action]').text()});
	focusFirst(dialogQuery);
};

var veilLoading = function() {
	loadVeil('<div class="loading">Loading</div>');
};

var hideVeil = window.hideVeil = function() {
	$('#form').fadeOut('fast', function() {
		$(this).empty();
	});
	return false;
};

wire(function() {
	var fieldDivSelector = 'fieldset>div:not(.multi),fieldset>div.multi>div';
	$('form').not('.hasValidation')
		.addClass('hasValidation')
		.each(function(formIndex, form) {
			form.validate = function() {
				var formQuery = $(form);
				formQuery.addClass('submitted');
				formQuery.find('.error').removeClass('error');
				formQuery.find(fieldDivSelector).each(validateFieldDiv);
				return !formQuery.find('.error').size();
			};
		})
		.submit(function(event) {
			if (this.validate) {
				var isValid = this.validate();
				if (!isValid) {
					event.stopImmediatePropagation();
				}
				else if (this.id == 'form') {
					setTimeout(veilLoading, 1);
				}
				return isValid;
			}
		})
		.delegate(formFieldsSelector, 'change', function() {
			$(this).addClass('changed');
		})
		.delegate('.newPassword', 'focus keypress keyup mouseup blur', function(event) {
			var eventType = event.type;
			var passwordQuery = $(this);
			var value = passwordQuery.val();
			var strengthQuery = passwordQuery.next('.passwordStrength');
			strengthQuery.css({display: value ? 'inline-block' : 'none'});

			var characterReplacementScore = function(pattern) {
				var replaced = value.replace(pattern, '');
				var uniques = 0;
				var has = {};
				replaced.split('').each(function(characterIndex, character) {
					if (!has[character]) {
						has[character] = 1;
						uniques++;
					}
				});
				return Math.sqrt(2 * Math.min(uniques, 6)) * 1.2;
			};

			var strength = Math.round(Math.max(1, Math.min(10,
				value.length / 8 +
				characterReplacementScore(/[^a-z]/g) +
				characterReplacementScore(/[^0-9]/g) +
				characterReplacementScore(/[^A-Z]/g) +
				characterReplacementScore(/[a-zA-Z0-9]/g))));

			var rg = ['', 'f0', 'f2', 'f4', 'f6', 'f8', 'ca', '9a', '6a', '3a', '0a'];

			strengthQuery.find('b').html('<div style="height:100%;width:' + strength + '0%;background:#' + rg[strength] + '0"/>');
			strengthQuery.find('var').hide().eq(strength < 6 ? 0 : strength < 8 ? 1 : 2).css({display: 'inline'});
		})
		.delegate(fieldDivSelector, 'click blur mouseup keyup change', validateFieldDiv);
});

var focusFirst = function(selector) {
	var focusableQuery = $(selector).find(focusableSelector).not('#close');
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
				href = baseUrl + href;
			}
			if (href.substring(0, baseUrl.length + 1) == baseUrl + '/') {
				loadUrl(href);
				$(link).blur();
				return false;
			}
		}
	})
	.delegate('form', 'submit', function(event) {
		// Mimic AJAX posting by submitting the form through the "submitter" iframe.
		if ($('#submitter').size()) {
			var form = this;
			var action = form.action;
			if (action.charAt(0) == '/') {
				action = baseUrl + action;
			}
			if (action.substring(0, baseUrl.length) == baseUrl) {
				var target = form.target;
				if (form.isAjax) {
					form.isAjax.value = 1;
				}
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
			var path = getPath(href).replace(/[\?&]is(Ajax|Frame|Dialog)=[01]/g, '');
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
lightTab();

var appendScript = window.appendScript = function(src, delay) {
	setTimeout(function() {
		var scriptQuery = $('<' + 'script type="text/javascript" src="' + src + '"/>');
		scriptQuery[0].async = true;
		$('head').append(scriptQuery);
	}, delay ? delay : 0);
};
