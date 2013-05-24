<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<p><?php T::_e('Welcome to the famous five minute Njiandan installation process! Just fill in the information below and you&#8217;ll be on your way to using the most extendable and powerful personal publishing platform in the world.'); ?></p>

<p><?php T::_e('Please provide the following information.  Don&#8217;t worry, you can always change these settings later.'); ?></p>
<div class="error_message"><?php echo $error_message; ?></div>
<form id="setup" method="post" action="">
    <table class="form-table">
        <tr>
            <th scope="row"><label for="username"><?php T::_e('Username'); ?></label></th>
            <td><?php echo form::input('username', $username, 'size="25"'); ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="password"><?php T::_e('Password'); ?></label></th>
            <td><?php echo form::password('password', $password, 'size="25"'); ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="password_confirm"><?php T::_e('Password confirm'); ?></label></th>
            <td><?php echo form::password('password_confirm', $password_confirm, 'size="25"'); ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="site_title"><?php T::_e('Site title'); ?></label></th>
            <td><?php echo form::input('site_title', $site_title, 'size="25"'); ?></td>

        </tr>
        <tr>
            <th scope="row"><label for="admin_uri"><?php T::_e('Admin uri'); ?></label></th>
            <td><?php echo form::input('admin_uri', $admin_uri, 'size="25"'); ?><br>
            <?php T::_e('Use this uri for admin login and manage.'); ?>(<?php T::_e('alphabetical characters, numbers, underscores and dashes only'); ?>)</td>
        </tr>
        <tr>
            <th scope="row"><label for="admin_email"><?php T::_e('Your E-mail'); ?></label></th>
            <td><?php echo form::input('admin_email', $admin_email, 'size="25"'); ?><br>
            <?php T::_e('Double-check your email address before continuing.'); ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="initial_data"><?php T::_e('Install theme initial data'); ?></label></th>
            <td><?php echo form::checkbox('initial_data', 'yes', $initial_data); ?>
            <br>
            <?php echo form::label('initial_data', T::_('Initial data can help you view and use the template more easy.')); ?>
            </td>
        </tr>
    </table>
    <p class="step"><input type="submit" value="<?php T::_e('Install Njiandan'); ?>" class="button" /></p>
</form>
