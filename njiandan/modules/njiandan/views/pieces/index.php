<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('.tr_row').mouseover(function(){$(this).addClass('over');}).mouseout(function(){$(this).removeClass('over');});
});
</script>
<div id="content">
<?php
if ($this->user->can('add_piece')) {
?>
<ul class="inlinelist">
    <li class="prominent"><?php echo html::admin_anchor('pieces/add', T::_('Add a piece')); ?></li>
</ul>
<p class="u"> </p>
<?php
}
if (count($pieces)) {
?>
 <form id="list">
    <table>
      <tr>
        <td colspan="4" class="tablebar">&nbsp;&nbsp;</td>
        </tr>
      <tr>
        <th><?php T::_e('Title'); ?></th>
        <th><?php T::_e('Tag'); ?></th>
        <th><?php T::_e('Action'); ?></th>
      </tr>
<?php
foreach($pieces as $piece) {
?>
      <tr class="tr_row">
        <td><?php echo $piece->title; ?></td>
        <td><?php echo $piece->uri; ?></td>
        <td>
<?php
if ($this->user->can('edit_piece')) {
    echo html::admin_anchor("pieces/add/$piece->id?redirect_uri=" . Router::$complete_uri, T::_('Edit'));
}
?>
 
<?php
if ($this->user->can('delete_piece')) {
    echo html::admin_anchor("pieces/delete/$piece->id", T::_('Delete'));
}
?></td>
      </tr>
<?php
}
?>
      <tr>
        <td colspan="4" class="tablebar">&nbsp;&nbsp;</td>
        </tr>
    </table>
  </form>
<?php
}
?>
</div>
