<?php defined('SYSPATH') OR die('No direct access allowed.');
if (!$njiandan or !$apppath or !$config_folder or !$config_file) {
?>
<h1><?php T::_e('Sorry, I can&#8217;t write to these file or folder.'); ?></h1>
<p><?php T::_e('You&#8217;ll have to either change the permissions to writable on this list.'); ?></p>
<ol>
    <li><?php echo url::file(''); ?> <span style="padding-left:50px;">
    <?php T::_e('Current status:'); ?> 
    <?php echo $njiandan ? '<span style="color:#008000;">' . T::_('writable') : '<span style="color:red;">' . T::_('can not write'); ?></span></span></li>

    <li><?php echo url::file(WEBROOT); ?> <span style="padding-left:50px;">
    <?php T::_e('Current status:'); ?> 
    <?php echo $apppath ? '<span style="color:#008000;">' . T::_('writable') : '<span style="color:red;">' . T::_('can not write'); ?></span></span></li>

    <li><?php echo url::file(WEBROOT . '/config'); ?><span style="padding-left:50px;">
    <?php T::_e('Current status:'); ?> 
    <?php echo $config_folder ? '<span style="color:#008000;">' . T::_('writable') : '<span style="color:red;">' . T::_('can not write'); ?></span></span></li>

    <li><?php echo url::file(WEBROOT . '/config/config.php'); ?><span style="padding-left:50px;">
    <?php T::_e('Current status:'); ?> 
    <?php echo $config_file ? '<span style="color:#008000;">' . T::_('writable') : '<span style="color:red;">' . T::_('can not write'); ?></span></span></li>
</ol>
<p class="step"><?php echo html::admin_anchor('install/step1', T::_('Next'), array('class'=>'button')); ?></p>
<?php
} else {
?>
<p><?php T::_e('Welcome to Njiandan. Before getting started, we need some information on the database. You will need to know the following items before proceeding.'); ?></p>
<ol>
    <li><?php T::_e('Database name'); ?></li>

    <li><?php T::_e('Database username'); ?></li>
    <li><?php T::_e('Database password'); ?></li>
    <li><?php T::_e('Database host'); ?></li>
    <li><?php T::_e('Table prefix'); ?> (<?php T::_e('if you want to run more than one Njiandan in a single database'); ?>)</li>
</ol>

<p><?php T::_e('In all likelihood, these items were supplied to you by your ISP. If you do not have this information, then you will need to contact them before you can continue. If you&#8217;re all ready&hellip;'); ?></p>

<p class="step"><?php echo html::admin_anchor('install/step2', T::_('Let&#8217;s go!'), array('class'=>'button')); ?></p>
<?php
}
?>
