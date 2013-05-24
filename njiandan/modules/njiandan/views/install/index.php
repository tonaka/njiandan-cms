<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<form method="post" action="">
<p><?php T::_e('Welcome to Njiandan. You need selected your default language.'); ?></p>

<p>
<?php T::_e('Default language:'); ?> 
<?php echo form::dropdown('language', $languages, $browser_language); ?>
</p>
<p class="step"><input type="submit" value=" <?php T::_e('Next'); ?> > " class="button" /></p>
</form>
