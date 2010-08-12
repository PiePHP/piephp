<?php

class ErrorController {

	public $counts = array();

	function handle($level, $message, $file, $line_number, $context) {
		// Ignore certain warnings. 
		if ($level == 2) {
			// We failed to find a class file, but we might have just been checking if a class exists.
			if (isset($context['autoload_file'])) {
				return;
			}
		}
		// Track duplicate errors, and only show the first.
		$key = $level . $message . $file . $line_number;
		if (!($this->counts[$key]++)) {
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
			$source = '<blockquote>' . join("\n", $new_lines) . '</blockquote>';
			?>
			<div class="error">
				<h3><?php echo $message; ?></h3><br>
				<h4><?php echo $file; ?> : <?php echo $line_number; ?></h4>
				<?php echo $source; ?>
				</form><form action="<?php echo HTTP_ROOT; ?>correction/">
				<input type="hidden" name="file" value="<?php echo htmlentities($file); ?>">
				<input type="hidden" name="start" value="<?php echo $start; ?>">
				<input type="hidden" name="end" value="<?php echo $end; ?>">
				</form>
				<?php
				if (count($context)) {
					print_r($context);
				}
				?>
			</div>
			<?php
			if (count($this->counts) == 1) {
				?>
				<script type="text/javascript">
					$('div.error blockquote').each(function(blockIndex, block) {
						block.scrollTop = $(block).find('code.error').position().top - $(block).height() / 4;
					});
				</script>
				<?php
			}
		}
	}

}