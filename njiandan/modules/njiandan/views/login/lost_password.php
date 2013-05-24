<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php
if ($status == 'success') {
?>
<p style="padding-bottom:70px;">&nbsp;</p>
<?php
} else {
?>
<form action="" method="post">
<p>
	<label><?php T::_e('Username or E-mail'); ?>:<br>
	<?php echo form::input('user_login', '', 'size="20" class="input" id="user_login" tabindex="10"'); ?>
</p>

    <p class="submit">
        <input id="loginsubmit" value="<?php T::_e('Get New Password'); ?>" tabindex="100" type="submit">
    </p>
</form>
<?php
}
?>
<ul>
    <li><?php echo html::anchor('/', T::_('Back to site'), array('title'=>T::_('Back to the homepage'))); ?></li>
    <li><?php echo html::admin_anchor('/login', T::_('Back to login'), array('title'=>T::_('Login to njiandan admin'))); ?></li>
</ul>
