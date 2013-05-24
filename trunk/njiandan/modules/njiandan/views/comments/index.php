<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="content_content">
<?php
if (count($comments)) {
?>
 <form id="list">
    <table>
      <tr>
        <td colspan="4" class="tablebar"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left"></td>
            <td align="right"><?php echo $pagelink; ?></td>
          </tr>

        </table></td>
        </tr>

      <tr>

        <th><?php T::_e('Comment'); ?></th>
        <th><?php T::_e('Action'); ?></th>
      </tr>
<?php
foreach($comments as $comment) {
?>
      <tr class="tr_row">

        <td>
<strong><?php echo $comment->username; ?></strong><br>
<?php
if (!empty($comment->url)) {
    echo $comment->url, ' | ';
}
?>
<?php echo $comment->email; ?> | <?php echo $comment->ip; ?>
<p><?php echo $comment->content; ?></p>

<p><?php T::_e('From'); ?> 
<?php
if ($comment->diagram_id) {
    echo html::anchor($comment->diagram->uri . '#comment_' . $comment->id, $comment->diagram->title, array('target'=>'blank'));
} else if ($comment->post_id) {
    echo html::anchor($comment->post->link . '#comment_' . $comment->id, $comment->post->title, array('target'=>'blank'));
}
?>
 , <?php echo date('Y-m-d H:i:s a', $comment->date); ?></p>
   	
   	</td>


        <td><span class="delete"><?php echo html::admin_anchor('comments/delete/' . $comment->id . '?redirect_uri=' . Router::$complete_uri, T::_('Delete')); ?></span></td>
      </tr>
<?php
}
?>


      <tr>
        <td colspan="4" class="tablebar"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left"></td>

        <td align="right"><?php echo $pagelink; ?></td>
          </tr>
        </table></td>
        </tr>
    </table>
  </form>
<?php
} else {
    echo '<h3>', T::_('You have no comments now.'), '</h3>';
}
?>
</div>
