<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="content_content">
<h2><?php T::_e('Backup theme data'); ?></h2>

<p><?php echo sprintf(T::_('This is for the theme maker to backup theme data in %s. if this folder is not exists, please create it and set writable first.'), $njiandan_folder); ?></p>
<p class="error">
<?php
echo $errors;
?>
</p>
    <form action="" id="settings" method="post">
        <input name="backup_theme_data" type="hidden" value="1"/>
        <input value="<?php T::_e('Backup theme data'); ?>" type="submit">
    </form>
<p>
</p>
</div>  
