<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="content_content">
<form id="post_form" name="post_form" method="post" action="" enctype="multipart/form-data" onsubmit="return check_input();">
<table width="100%">
  <tr>
    <td>

<div id="post_meta">
    <table>
      <tr class="finalrow">
        <th><?php T::_e('Page title'); ?></th>
        <td><span class="errorbox-good">
            <?php echo form::input('title', $title, 'style="width:100%"'); ?></span>
        </td>
      </tr>
<?php
echo $customfields;
?>
    </table>
<div id="page_meta">

<?php
Editor::baidu_editor(array('name'=>'content', 'value'=>$content, 'width'=>Kohana::config('njiandan.editor_width'), 'height'=>Kohana::config('njiandan.editor_height')));
?>

</div>
<table>
      <tr class="finalrow">
        <th></th>
        <td align="right">
<input style="font-weight: bold;" tabindex="5" accesskey="p" value="<?php T::_e('Edit'); ?>" type="submit">&nbsp;&nbsp;
           </td>
      </tr>
</table>

</div>
    </td>
    <td width="250px" valign="top"></td>
  </tr>
</table>
</div>
</form>
</div>
