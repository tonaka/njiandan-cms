<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<script type="text/javascript">
$(document).ready(function(){
    show_this_meta();
    $('.type').bind('click', function(){
        show_this_meta();
    });

    $('form').submit(function() {
        var oType = $("input[name='type']:checked").val();
        if (!oType) {
            alert('<?php T::_e('Plase select diagram type.'); ?>');
            return false;
        }
        var title = $('#title').val();
        if (title == '') {
            alert('<?php T::_e('the diagram title is empty.'); ?>');
            $('#title').focus();
            return false;
        }
        var diagram_uri = $('#uri').val();
        if (diagram_uri == '') {
            alert('<?php T::_e('the diagram uri is empty.'); ?>');
            $('#uri').focus();
            return false;
        }
        
        var no_same_field = true;
        var same_field_false_info = '<?php T::_e('Can not use system field.'); ?>';
        $("input[name='customfields[fields][key][]']").each(function() {
            if (oType == 'list') {
                var post_reserve_fields = new Array();
                post_reserve_keys = {'id':1, 'user_id':1, 'user':1, 'diagram_id':1, 'diagram':1, 'title':1, 'excerpt':1, 'content':1, 'password':1, 'allow_ping':1, 'to_ping':1, 'allow_comment':1, 'date':1, 'uri':1, 'is_thumb':1, 'is_star':1, 'view':1, 'status':1, 'attachments':1, 'customvalues':1, 'comments':1};
                if (post_reserve_keys[this.value] == 1) {
                    alert(same_field_false_info);
                    no_same_field = false;
                    this.focus();
                    return false;
                }
            } else if (oType == 'page') {
                diagram_reserve_keys = {'id':1, 'user_id':1, 'user':1, 'type':1, 'title':1, 'content':1, 'parent_id':1, 'parent':1, 'uri':1, 'template':1, 'metavalue':1, 'date':1, 'order':1, 'children':1, 'customvalues':1, 'posts':1, 'customfields':1, 'comments':1};
                if (diagram_reserve_keys[this.value] == 1) {
                    alert(same_field_false_info);
                    no_same_field = false;
                    this.focus();
                    return false;
                }
            }
        });
        return no_same_field;
    });
});

function show_this_meta() {
    current_checked = $("input[class='type']:checked").val();
    $('#page_meta').hide();
    $('#customfields').hide();
    $('#select_template_tr').hide();
    $('#list_meta').hide();
    $('#post_template_tr').hide();
    if (current_checked == 'page') {
        $('#customfields').show();
        $('#page_meta').show();
        $('#select_template_tr').show();
        $('#select_url_tr').show();
    }
    if (current_checked == 'list') {
        $('#customfields').show();
        $('#select_template_tr').show();
        $('#select_url_tr').show();
        $('#list_meta').show();
        $('#post_template_tr').show();
    }
    if (current_checked == 'item') {
        $('#select_template_tr').show();
    }
}

function check_input() {
    oType = $("input[name='type']:checked").val();
    if (!oType) {
        alert('<?php T::_e('Plase select diagram type.'); ?>');
        return false;
    }
    var title = $('#title').val();
    if (title == '') {
        alert('<?php T::_e('the diagram title is empty.'); ?>');
        $('#title').focus();
        return false;
    }
    diagram_uri = $('#uri').val();
    if (diagram_uri == '') {
        alert('<?php T::_e('the diagram uri is empty.'); ?>');
        $('#uri').focus();
        return false;
    }
}

var tr_count = <?php echo count($customfields); ?>;

function add_custom_row() {
    first_row = $('#custom_table_fist_tr');
    first_row.show();
    haha = first_row.html();

    first_row.after('<tr id="tr_' + tr_count + '"><td><input type="text" size="2" maxlength="2" style="width:17px;" name="customfields[fields][order][]" /></td><td><?php echo str_replace("\n", '', form::dropdown('customfields[fields][type][]', $field_types)); ?></td><td><input type="text" size="10" name="customfields[fields][title][]"/></td><td><input type="text" size="10" name="customfields[fields][key][]"/></td><td><input type="text" name="customfields[fields][metavalue][]" value="" size="40" /></td><td><a href="#" onclick="delete_this_row(' + tr_count + '); return false"><?php T::_e('Delete'); ?></a></td></tr>');
    tr_count += 1;
}

function delete_this_row(tr_id) {
    $('#tr_' + tr_id).remove();
    var i = 0;
    $("input[name='customfields[fields][order][]']").each(function() {
        i += 1;
    });
    if (i == 0) {
        $('#custom_table_fist_tr').hide();
    }
}

function use_custom_template() {
    var custom_input_html = '<?php echo form::input(array('name'=>'template', 'size'=>20)); ?>';
    $('#select_template').html(custom_input_html);
    $('#use_input_description').hide();
    $('#custom_input').show();
}

function cancel_custom_input() {
    $('#custom_input').hide();
    $('#use_input_description').show();
    var diagram_type = $("input[name='type']:checked").val(); 
}
</script>
<style type="text/css">
#select_template {
    display:inline;
    padding-right:10px;
}
</style>

<div id="content_content">
<form id="post_form" name="post_form" method="post" action="">
<table width="100%">
  <tr>
    <td>
<div id="post_meta">
    <table>
      <tr class="finalrow">
        <th><?php T::_e('Diagram type'); ?></th>
        <td>
        <?php echo form::radio('type', 'page', $type_page, 'id="page" class="type"').form::label('page', T::_('Page')); ?>
<?php echo form::radio('type', 'list', $type_list, 'id="list" class="type"').form::label('list', T::_('List')); ?>
<?php echo form::radio('type', 'item', $type_item, 'id="item" class="type"').form::label('item', T::_('Item')); ?>
<?php echo form::radio('type', 'url', $type_url, 'id="url" class="type"').form::label('url', T::_('Url')); ?>
        </td>
      </tr>
      <tr class="finalrow">
        <th><?php T::_e('Parent'); ?></th>
        <td>
        <select name="parent_id" id="diagram_parent" style="width: 100%;">
        <?php echo $select_options; ?>
        </select>
        </td>
      </tr>

      <tr class="finalrow">
        <th><?php T::_e('Diagram title'); ?></th>
        <td><?php echo form::input(array('name'=>'title', 'style'=>'width:100%', 'value'=>$title)); ?></td>
      </tr>
      <tr class="finalrow" id="select_url_tr">
        <th><?php T::_e('Diagram uri'); ?></th>
        <td><?php echo form::input(array('name'=>'uri', 'style'=>'width:100%', 'value'=>$uri)); ?></td>
      </tr>

      <tr class="finalrow" id="select_template_tr">
        <th><?php T::_e('Template'); ?></th>
        <td>
        <?php echo form::dropdown('template', $templates, $template); ?>
        </td>
      </tr>

      <tr class="finalrow" id="post_template_tr">
        <th><?php T::_e('Post template'); ?></th>
        <td>
        <?php echo form::dropdown('post_template', $post_templates, $post_template); ?>
        </td>
      </tr>

      <tr class="finalrow" id="customfields">
        <th><?php T::_e('Customize field'); ?></th>
        <td>
        <a href="#" onclick="add_custom_row();return false;"><?php T::_e('+ Add a field'); ?></a>
<table id="custom_table">
<?php
if (count($customfields)) {
    $customfield_menu_style = '';
} else {
    $customfield_menu_style = 'style="display:none;"';
}
?>
    <tr id="custom_table_fist_tr" <?php echo $customfield_menu_style; ?>>
    <th><?php T::_e('Order'); ?></th><th><?php T::_e('Type'); ?></th><th><?php T::_e('Name'); ?></th><th><?php T::_e('Tag'); ?></th><th><?php T::_e('Attributes'); ?></th><th><?php T::_e('Action'); ?></th>
    </tr>
<?php
foreach($customfields as $key => $field) {
    echo '<tr id="tr_',$key, '"><td><input type="text" size="2" maxlength="2" style="width:17px;" name="customfields[fields][order][]" value="',$field->order,'"/></td><td>', form::dropdown('customfields[fields][type][]', $field_types, $field->type), '</td><td><input type="text" size="10" name="customfields[fields][title][]" value="',$field->title,'"/></td><td><input type="text" size="10" name="customfields[fields][key][]" value="',$field->key,'"/></td><td>', form::input('customfields[fields][metavalue][]', $field->metavalue, 'size="40"'), '</td><td><a href="#" onclick="delete_this_row(',$key,'); return false">', T::_('Delete') , '</a></td></tr>';
}
?>
</table>
        </td>
      </tr>
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
<input id="publish" style="font-weight: bold;" tabindex="5" accesskey="p" value="<?php T::_e($buttom); ?>" type="submit">&nbsp;&nbsp;
           </td>
      </tr>
</table>
</div>
    </td>
    <td valign="top"></td>
  </tr>
</table>
</div>
</form>
</div>
