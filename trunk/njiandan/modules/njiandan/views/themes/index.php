<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="content">
<!--
<h2>使用说明</h2>
<p>当你选择一个新模板时,原来的老模板将会被替换,因为新的模板网站架构图设置会和老的模板不一样,因此有两个选择方法:
<ul>
<li>1. 不要原始数据,直接选择使用新模板初始化数据</li>
<li>2. 保留原始数据,然后选择自己进行网站架构图模板的手动修改</li>
</ul></p>
-->
<br class="clear">
<?php
if (empty($current)) {
    
} else {
?>
<h3><?php T::_e('Current Theme'); ?></h3>
<div id="current-theme">
<?php echo html::theme_image("$current[folder]/screenshot.png", array('border'=>0)); ?>
<h4><?php echo $current['name'], ' ', $current['version']; ?> by 
<?php echo !empty($current['author_uri']) ? '<a href="' . $current['author_uri'] . '" title="Visit author homepage" target="_blank">' . $current['author'] . '</a>' : $current['author']; ?></h4>
<p class="description"><?php echo $current['description']; ?></p>
	<p><?php printf(T::_('All of this theme’s files are located in %s .'), '<code>/themes/' . $current['folder'] . '</code>'); ?></p>
<p><?php T::_e('Tags'); ?>: <?php echo $current['tags']; ?></p>

</div>
<?php
}
if (count($themes)) {
?>
<br class="clear">
<div class="clear"></div>
<h3><?php T::_e('Available Themes'); ?></h3>
<div class="clear"></div>


<table id="availablethemes" cellpadding="0" cellspacing="0">
<tbody>
<?php

$count = count($themes);
for($i = 0; $i < $count; $i++) {
    $first_row_left_td_class = ($i == 0) ? ' top ' : '';
    $first_row_left_td_class .= ($i == ($count-1) or $i == ($count-3)) ? ' bottom ' : '';

    $first_row_middle_td_class = ($i == ($count-1) or $i == ($count-3)) ? ' bottom ' : '';
    $info = array('folder'=>'', 'name'=>'', 'uri'=> '', 'description'=>'', 'version'=>'', 'author'=>'', 'author_uri'=>'', 'tags'=>'');
    $theme = isset($themes[$i]) ? $themes[$i] : $info;
    $i += 1;
?>
<tr>
	<td class="available-theme <?php echo $first_row_left_td_class; ?> left">
<?php
if (!empty($theme['folder'])) {
    $confirm = 'return confirm("' . T::_('Changes theme will clear out all the current data, you should back the data before you change it. Are you sure you want to change theme ?') . '");';
?>
		<?php echo html::admin_anchor("/themes/change_theme/$theme[folder]/", html::theme_image("$theme[folder]/screenshot.png"), array('onclick'=>$confirm)); ?>
		<h3><?php echo html::admin_anchor("/themes/change_theme/$theme[folder]/", $theme['name'], array('onclick'=>$confirm)); ?></h3>
		<p><?php echo $theme['description']; ?></p>
		<p><?php echo !empty($theme['tags']) ? T::_('Tags') . ': ' . $theme['tags'] : ''; ?></p>
<?php
}
?>
	</td>
<?php
$theme = isset($themes[$i]) ? $themes[$i] : $info;
$i += 1;
?>
	<td class="available-theme <?php echo $first_row_middle_td_class; ?> top">
<?php
if (!empty($theme['folder'])) {
?>
		<?php echo html::admin_anchor("/themes/change_theme/$theme[folder]/", html::theme_image("$theme[folder]/screenshot.png"), array('onclick'=>$confirm)); ?>
		<h3><?php echo html::admin_anchor("/themes/change_theme/$theme[folder]/", $theme['name'], array('onclick'=>$confirm)); ?></h3>
		<p><?php echo $theme['description']; ?></p>
		<p><?php echo !empty($theme['tags']) ? T::_('Tags') . ': ' . $theme['tags'] : ''; ?></p>
<?php
}
?>
	</td>
<?php
$theme = isset($themes[$i]) ? $themes[$i] : $info;
?>
	<td class="available-theme <?php echo $first_row_left_td_class; ?> left">
<?php
if (!empty($theme['folder'])) {
?>
		<?php echo html::admin_anchor("/themes/change_theme/$theme[folder]/", html::theme_image("$theme[folder]/screenshot.png"), array('onclick'=>$confirm)); ?>
		<h3><?php echo html::admin_anchor("/themes/change_theme/$theme[folder]/", $theme['name'], array('onclick'=>$confirm)); ?></h3>
		<p><?php echo $theme['description']; ?></p>
		<p><?php echo !empty($theme['tags']) ? T::_('Tags') . ': ' . $theme['tags'] : ''; ?></p>
<?php
}
?>
	</td>
</tr>
<?php
}
?>

</tbody></table>
<?php
} else {
    echo '<h3>', T::_('Can find no available theme.'), '</h3>';
}
?>
<br class="clear">
<h3><?php T::_e('Get More Themes'); ?></h3>
<p><?php T::_e('You can find additional themes for your site in the njiandan theme directory. To install a theme you generally just need to upload the theme folder into your <code>webroot/themes</code> directory. Once a theme is uploaded, you should see it on this page.'); ?></p>
</div>
