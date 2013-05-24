<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<form action="" method="post">
    <p>
        <?php echo form::label('user_login', T::_('Username')).form::input('user_login', $user_login, 'size="20" class="input" tabindex="10"') ?>
    </p>
    <p>
        <?php echo form::label('user_pass', T::_('Password')).form::password('user_pass', $user_pass, 'size="20" class="input" tabindex="20"') ?>
    </p>
    <p class="forgetmenot"><?php echo form::checkbox('remember_me', 'remember_me', $checked, 'tabindex="30"').form::label('remember_me', T::_('Remember Me')); ?></p>

    <p class="submit">
        <input id="loginsubmit" value="<?php T::_e('Login'); ?>" tabindex="100" type="submit">
    </p>
</form>
<ul>
    <li><?php echo html::anchor('/', T::_('Back to site'), array('title'=>'Back to the site of index')); ?></li>
    <li><?php echo html::admin_anchor('/login/lost_password/', T::_('Lost your password?'), array('title'=>T::_('Password Lost and Found'))); ?></li>
</ul>
