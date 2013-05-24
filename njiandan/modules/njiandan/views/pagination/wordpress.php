<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Simple pagination style
 * 
 * @preview  « Previous  Next »
 */
?>

<div class="navigation">
	<?php if ($previous_page): ?>
		<div class="alignleft"><a href="<?php echo str_replace('{page}', $previous_page, $url) ?>">&laquo;&nbsp;<?php T::_e('Previous'); ?></a></div>
	<?php endif ?>
	<?php if ($next_page): ?>
		<div class="alignright"><a href="<?php echo str_replace('{page}', $next_page, $url) ?>"><?php T::_e('Next'); ?>&nbsp;&raquo;</a></div>
	<?php endif ?>
</div>
