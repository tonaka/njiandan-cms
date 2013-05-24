<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php
if (!$hide_input) {
?>
<form action="" method="post">

<p>
	<label><?php T::_e('Password'); ?>:<br>
	<?php echo form::password('password', '', 'size="20" class="input" tabindex="10"'); ?>
</p>
<p>
	<label><?php T::_e('Re-enter Password'); ?>:<br>
	<?php echo form::password('re_password', '', 'size="20" class="input" tabindex="20"'); ?>
</p>
    <p class="submit">
        <input id="loginsubmit" value="<?php T::_e('Set password'); ?>" tabindex="100" type="submit">
    </p>
</form>
<?php
} else {
?>
<p style="padding-bottom:70px;">&nbsp;</p>
<ul>
    <li><?php echo html::anchor('/', T::_('Back to site'), array('title'=>'Back to the site of index')); ?></li>
    <li><?php echo html::admin_anchor('/login/lost_password/', T::_('Request a new one'), array('title'=>T::_('Password Lost and Found'))); ?></li>
</ul>
<?php
}
?>
