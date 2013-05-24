<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<script type="text/javascript">
var post_id = <?php echo $post_id; ?>;
$(document).ready(function(){
    $('#diagram_id').bind('change', function(e) {
        get_diagram_customfields(this.value);
    });
});

function get_diagram_customfields(diagram_id) {
    $.get('<?php echo url::admin_site('post_new/custom_fields');?>/' + diagram_id + '/' + post_id +'?time=' + time_now(), function (data) {
        $('.finalrow_customfields').remove();
        if (data != '') {
            $('#post_slug_tr').after(data);
        }
    });
}
var Categories = new Array();
Categories = <?php echo $category_list; ?>;
function check_form() {
    var diagram_id = $('#diagram_id').val();
    if (Categories[diagram_id] == undefined) {
        alert('<?php T::_e('Just can add post in category.'); ?>');
        $('#diagram_id').focus();
        return false;
    }
}

function delete_thumb() {
/*
    $.get('<?php echo url::admin_site("post_new/delete_thumb/{$post_id}/ajax"); ?>' + '?time=' + time_now(), function (data) {
        if (data == 'done') {
            $('#thumbnail_view').html('<?php echo form::upload(array('name'=>'thumbnail', 'size'=>60));?>');
        }
    });
    
*/
    $('#thumbnail_view').hide();
    $('#thumbnail_upload').show();
    $('#delete_thumbnail').val(1);
}

function cancel_delete_thumbnail() {
    $('#thumbnail_view').show();
    $('#thumbnail_upload').hide();
    $('#delete_thumbnail').val(0);
}
</script>
<div id="content_content">
<form id="post_form" name="post_form" method="post" action="" enctype="multipart/form-data" onsubmit="return check_form();">
<table width="100%">
  <tr>
    <td>
<div id="post_meta">
    <table>
      <tr class="finalrow">

        <th><?php T::_e('Category'); ?></th>
        <td>
        <select name="diagram_id" id="diagram_id" style="width: 100%;">
        <?php echo $select_options; ?>
        </select>
        </td>
      </tr>

      <tr class="finalrow">
        <th><?php T::_e('Title'); ?></th>
        <td><span class="errorbox-good">
        <?php echo form::input('title', $title, 'style="width: 100%;"'); ?>         </span> </td>
      </tr>

    </table>
</div>

<?php
Editor::baidu_editor(array('name'=>'content', 'value'=>$content, 'width'=>Kohana::config('njiandan.editor_width'), 'height'=>Kohana::config('njiandan.editor_height')));
?>

<div id="post_meta">
<table>
      <tr class="finalrow">
        <th></th>
        <td align="right">
<!--        
<span >
<input name="save" id="save" type="button" value="Save and Continue Editing" onclick="save_post(); return false;" > </span>
-->
<?php
if ($this->user->can('post_new')) {
?>
<input name="publish" id="publish" style="font-weight: bold;" tabindex="5" accesskey="p" value="<?php T::_e('Publish'); ?>" type="submit">&nbsp;&nbsp;
<?php
}
?>
    </td>
      </tr>

</table>


<table>
    <tr class="finalrow">
    <th><?php T::_e('Thumbnail'); ?></th>
    <td>
<div id="thumbnail_view">
<?php
if ($thumb) {
    $thumbnail_upload_style = 'style="display:none;"';
    echo html::image($thumb, array('height'=>100, 'width'=>100));
?>
&nbsp; &nbsp;<?php echo html::admin_anchor("/post_new/delete_thumb/{$post_id}/web?redirect_uri=" . Router::$complete_uri, T::_('Delete and upload again'), array('onclick'=>'delete_thumb();return false;')); ?>
<?php
} else {
    $thumbnail_upload_style = '';
}
?>
</div>
<div id="thumbnail_upload" <?php echo $thumbnail_upload_style; ?>>
<?php
echo form::upload(array('name'=>'thumbnail', 'size'=>60));
if ($thumb) {
    echo ' <a href="#" onclick="cancel_delete_thumbnail();return false;">' . T::_('Cancel delete') . '</a>';
}
?>
</div>
<input type="hidden" value="0" name="delete_thumbnail" id="delete_thumbnail"/>
</td>
    </tr>
<!--
      <tr class="finalrow">
        <th><?php T::_e('Permission'); ?></th>
        <td>
<?php echo form::checkbox('allow_comment', 'allow_comment', TRUE) . form::label('allow_comment', T::_('Allow comments')); ?>
<?php echo form::checkbox('allow_ping', 'allow_ping', TRUE) . form::label('allow_ping', T::_('Allow pings')); ?>
<?php echo form::checkbox('use_password', 'use_password', TRUE) . form::label('use_password', T::_('Read password')); ?>
        </td>
      </tr>
-->
      <tr class="finalrow">
        <th><?php T::_e('Post date'); ?></th>
        <td><?php echo form::input('date', $date); ?> <span class="u"> <?php T::_e('You can reserve it ex'); ?>: <?php echo date('Y-m-d H:i:s'); ?></span></td>
      </tr>

      <tr class="finalrow" id="post_slug_tr">
        <th><?php T::_e('Post uri'); ?></th>
        <td><?php echo form::input('uri', $uri); ?></td>
      </tr>

<?php
    echo $customfields;
?>

          </table>
</div>
    </td>

    <td width="250px" valign="top"></td>
  </tr>
</table>

</form>
</div>
