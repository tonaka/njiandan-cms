<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="content">
<h2><?php T::_e('Customize Permalink Structure'); ?></h2>

<p><?php T::_e('By default Njiandan uses web URLs which have "index.php" and lots of numbers in them, however njiandan offers you the ability to create a custom URL structure for your permalinks and archives. This can improve the aesthetics, usability, and forward-compatibility of your links. A number of tags are available, and here are some examples to get you started.'); ?></p>
<p>
<span class="error">
<?php
if (!$rewrite) {
    T::_e('Notice: It seems that your webserver it not support mod_rewrite, if you still use this, your website maybe can not visit.');
}
?>
</span>
</p>
<h3><?php T::_e('Common options'); ?>:</h3>

<br>
<form action="" id="settings" method="post">
<p>
<?php echo form::radio('url_model', 'default', $default, 'id="default"').form::label('default', T::_('Default')); ?>
<br>&nbsp;&nbsp;&nbsp;&nbsp;<code><?php echo Kohana::config('njiandan.site_url') . url::base() . 'index.php/p/123'; ?></code>
</p>

<p>
<?php echo form::radio('url_model', 'htaccess', $htaccess, 'id="htaccess"').form::label('htaccess', T::_('Htaccess')); ?>
<br>&nbsp;&nbsp;&nbsp;&nbsp;<code><?php echo Kohana::config('njiandan.site_url') . url::base() . 'p/123'; ?></code>
</p>

<p>
<?php echo form::radio('url_model', 'html', $html, 'id="html"').form::label('html', T::_('Html')); ?>
<br>&nbsp;&nbsp;&nbsp;&nbsp;<code><?php echo Kohana::config('njiandan.site_url') . url::base() . 'p/123.html'; ?></code>
</p>

<p>
<?php echo form::radio('url_model', 'htaccess_and_uri', $htaccess_and_uri, 'id="htaccess_and_uri"').form::label('htaccess_and_uri', T::_('Htaccess and Uri')); ?>
<br>&nbsp;&nbsp;&nbsp;&nbsp;<code><?php echo Kohana::config('njiandan.site_url') . url::base() . 'p/post_uri'; ?></code>
</p>

<p>
<?php echo form::radio('url_model', 'html_and_uri',$html_and_uri, 'id="html_and_uri"').form::label('html_and_uri', T::_('Html and Uri')); ?>
<br>&nbsp;&nbsp;&nbsp;&nbsp;<code><?php echo Kohana::config('njiandan.site_url') . url::base() . 'p/port_uri.html'; ?></code>
</p>
<?php
// check is use have role
if ($this->user->can('edit_options_permalink')) {
?>
<input value="<?php T::_e('Update options'); ?>" type="submit">
<?php
}
?>
</form>
</div>
