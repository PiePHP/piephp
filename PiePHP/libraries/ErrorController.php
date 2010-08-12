<?php

class ErrorController {

	public $counts = array();

	function index($error_code = '500') {
		die($error_code);
	}

	function handle($level, $message, $file, $line_number, $context) {
		// Ignore certain warnings. 
		if ($level == 2) {
			// We failed to find a class file, but we might have just been checking if a class exists.
			if (isset($context['autoload_file'])) {
				return;
			}
		}
		// Track duplicate errors, and only handle the first.
		$key = $level . $message . $file . $line_number;
		if (!($this->counts[$key]++)) {
			$file = str_replace('\\', '/', $file);
			$source = show_source($file, true);
			preg_match('/<span([^>]+)>/', $source, $match);
			$span = $match[0];
			$source = trim(preg_replace('/(^<code>\\s*<span[^>]*>|<\/span>\\s*<\/code>$)/', '', $source));
			$lines = explode('<br />', $source);
			$new_lines = array();
			$pad = strlen('' . count($lines));
			foreach ($lines as $i => $line) {
				$line = trim($line);
				if (preg_match('/<span([^>]+)>[^<]*$/', $line, $match)) {
					$span = '<span' . $match[1] . '>';
				}
				$line = (strpos($line, '<span') === 0 ? '' : $span) . $line . '</span>';
				$digits = '<i>' . sprintf('%0' . $pad . 's', $i + 1) . '&nbsp;</i>';
				$new_lines[] = '<code' . ($i == $line_number - 1 ? ' class="error"' : '') . '>' . $digits . $line . '</code>';
			}
			$source = join("\n", $new_lines);
			?>
			<div class="error">
				<h3><?php echo $message; ?></h3><br>
				<h4><?php echo $file; ?> : <?php echo $line_number; ?></h4>
				<textarea></textarea>
				<blockquote><?php echo $source; ?></blockquote>
				<?php
				if (count($context)) {
					print_r($context);
				}
				?>
			</div>
			<?php
			if (count($this->counts) == 1) {
				?>
				<link rel="stylesheet" href="/media/base.css" type="text/css">
				<script type="text/javascript" src="/media/jquery-1.4.2.js"></script>
				<script type="text/javascript" src="/media/base.js"></script>
				<script type="text/javascript">
					$('div.error blockquote')
						.each(function(blockIndex, block) {

							var blockQuery = $(block);
							var textareaQuery = blockQuery.prev();
							var textareaHasFocus = 0;
							var textareaSubmitting = 0;

							block.scrollTop = blockQuery.find('code.error').position().top - blockQuery.height() / 4;
							blockQuery.add(textareaQuery)
								.hover(function() {
									var cloneQuery = blockQuery.clone();
									cloneQuery.find('i').remove();
									var code = cloneQuery.text();
									delete cloneQuery;
									textareaQuery.val(code).show()[0].scrollTop = block.scrollTop;
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
									var code = textareaQuery.val();
									$.post('<?php echo HTTP_ROOT; ?>error/rewrite', {file: '<?php echo $file; ?>', code: code}, function() {
										textareaQuery.removeClass('submitting');
									});
								});
						});
				</script>
				<?php
			}
		}
	}

	function rewrite() {
		file_put_contents('rewrite.log', $_POST['code']);
		file_put_contents($_POST['file'] . 5, $_POST['code']);
		if (isset($GLOBALS['REFRESHER_FILE'])) {
			touch($GLOBALS['REFRESHER_FILE']);
		}
	}

}