<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="content_content">
<style type="text/css">
dl {
  margin:0; 
  padding:0 0 15px 0; 
  width:100%; 
  height:auto; 
  }

dd {
  margin:0; 
  display:block; 
  width:100%; 
  height:2em;
  border-bottom:1px solid #fff;
  font-size:12px;
  text-align:right;
  }

dd b {
  float:right;
  display:block; 
  margin-left:auto; 
  background:#cec; 
  height:2em; 
  line-height:2em; 
  text-align:right;
  font-size:12px; 
  }
</style>

<?php T::_e('Disk space info'); ?> : 
<?php
$space = SpaceInfo::disk_space();
printf(T::_('You are currently using %s(%s%%) of your %s.'), $space->used, $space->percent, $space->total);
$left_percent = 100 - $space->percent;
$color = SpaceInfo::notice_color($space->percent);

?>

<dl>
<dd><b style="width:<?php echo $space->percent; ?>%;background:none;float:left;"><?php echo $space->percent; ?>%</b></dd>
<dd style="background:<?php echo $color; ?>;"><b style="width:<?php echo $left_percent; ?>%"></b></dd>
</dl>

<br>

<?php T::_e('Database info'); ?> : 
<?php
$database = SpaceInfo::database();
printf(T::_('You are currently using %s(%s%%) of your %s.'), $database->used, $database->percent, $database->total);
$left_percent = 100 - $database->percent;
$color = $color = SpaceInfo::notice_color($database->percent);
?>

<dl>
<dd><b style="width:<?php echo $database->percent; ?>%;background:none;float:left;"><?php echo $database->percent; ?>%</b></dd>
<dd style="background:<?php echo $color; ?>;"><b style="width:<?php echo $left_percent; ?>%"></b></dd>
</dl>
</div>
