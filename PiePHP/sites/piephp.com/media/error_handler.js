
if (!window.errorHandling) {
	
	window.errorHandling = 1;

	var setBlockQueryScrollTop = function(blockQuery) {
		blockQuery[0].scrollTop = blockQuery.find('code.line').position().top - blockQuery.height() / 4;
	};

	wire(function(query) {
		query.find('form.code blockquote')
			.each(function(blockIndex, block) {

				var blockQuery = $(block);
				var textareaQuery = blockQuery.prev();
				var textareaHasFocus = 0;
				var textareaSubmitting = 0;

				setBlockQueryScrollTop(blockQuery);
				blockQuery.add(textareaQuery)
					.hover(function() {
						if (!textareaQuery.is(':visible')) {
							var cloneQuery = blockQuery.clone();
							cloneQuery.find('i').remove();
							var code = cloneQuery.text();
							delete cloneQuery;
							textareaQuery.val(code).show()[0].scrollTop = block.scrollTop;
						}
					}, function() {
						if (!textareaHasFocus) {
							textareaQuery.blur();
						}
					});

				textareaQuery
					.width(blockQuery.width())
					.height(blockQuery.height())
					.focus(function() {
						textareaHasFocus = 1;
					})
					.blur(function() {
						if (!textareaSubmitting) {
							textareaHasFocus = 0;
							block.scrollTop = textareaQuery[0].scrollTop;
							textareaQuery.hide();
						}
					})
					.change(function(event) {
						textareaSubmitting = 1;
						textareaQuery
							.show()
							.addClass('submitting');
						var formQuery = textareaQuery.parent();
						formQuery.find('input,textarea').attr('name', '');
						textareaQuery.prev().attr('name', 'file');
						textareaQuery.attr('name', 'code');
						formQuery.submit();
					});
			});
		query.find('div.toggle')
			.click(function() {
				$(this).toggleClass('toggled');
				var blockQuery = $(this).next().next().next().toggle();
				this.toggleCount++;
				if (!(this.toggleCount++)) {
					setBlockQueryScrollTop(blockQuery);
				}
			});
	});
}