/*var $ = function(selector, context) {
	// Handle HTML strings
	if (typeof selector == "string") {
		// Are we dealing with HTML string or an ID?
		var match = quickExpr.exec( selector );

		// Verify a match, and that no context was specified for #id
		if (match && (match[1] || !context)) {

			// HANDLE: $(html) -> $(array)
			if (match[1])
				selector = jQuery.clean([match[1]], context);

			// HANDLE: $("#id")
			else {
				var elem = document.getElementById(match[3]);

				// Make sure an element was located
				if (elem) {
					// Handle the case where IE and Opera return items
					// by name instead of ID
					if ( elem.id != match[3] )
						return jQuery().find( selector );

					// Otherwise, we inject the element directly into the jQuery object
					return jQuery( elem );
				}
				selector = [];
			}

		// HANDLE: $(expr, [context])
		// (which is just equivalent to: $(content).find(expr)
		} else
			return jQuery( context ).find( selector );

	// HANDLE: $(function)
	// Shortcut for document ready
	} else if ( jQuery.isFunction( selector ) )
		return jQuery( document )[ jQuery.fn.ready ? "ready" : "load" ]( selector );

	return this.setArray(jQuery.makeArray(selector));
};

Object.prototype.set = function(properties) {
  for (var property in properties) {
    this[property] = properties[property];
  }
};

Array.prototype.set({
	each: function(callback) {
		for (var i = 0; i < this.length; i++) {
			callback(this[i], i);
		}
	}
});*/

$A = function(iterable) {
	var array = !iterable ? [] : iterable.toArray ? iterable.toArray() : 0;
	if (!array) {
		var length = iterable.length || 0;
		array = new Array(length);
		while (length--) {
			array[length] = iterable[length];
		}
	}
	return array;
};

//['one', 'two', 'three'].each(alert);