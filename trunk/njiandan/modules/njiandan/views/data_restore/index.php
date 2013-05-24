<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<script type="text/javascript">
function restore_action(action_value) {
    var file_name = $('#datafile_name').val();
    if (!file_name) {
        show_noticemessage('<?php T::_e('Please select the data file.'); ?>');
        return false;
    }

    if (action_value == 'download') {
        var download_message = '<a href="' + '<?php echo url::file(WEBROOT . '/backup/'); ?>' + file_name + '"><?php T::_e('Right click to save file.'); ?></a>';
        show_noticemessage(download_message);
        return false;
    }
    $("input[type='submit']").attr('disabled','disabled');
    $('#datafile_name').attr('disabled', 'disabled');
    $.get('<?php echo url::admin_site('data_restore/ajax_restore'); ?>' + '/' + file_name, 
        function(data) {
            var notice_message = '';
            if (data == 'done') {
                show_noticemessage('<?php T::_e('Restore done.'); ?>');
            } else if (data == 'javascript_restore') {
                document.location = '<?php echo url::admin_site('data_restore/javascript_restore'); ?>/' + file_name
            }

            if (action_value =='delete' && data == 'Delete done.') {
                $('option:selected').remove();
            }
            $("input[type='submit']").attr('disabled','');
            $('#datafile_name').attr('disabled', '');
        }
    );
    return false;
}
</script>
<div id="content_content">
<form id="settings" method="post" onsubmit="return false;">
    <table>
      <tbody>
        <tr>
          <th><?php T::_e('Select a file'); ?></th>
          <td>
          <?php echo form::dropdown('datafile_name', $data_files, '', 'size="10" style="width:80%"'); ?>
    </td>
        </tr>

        <tr class="finalrow">
          <th></th>
          <td>
          <input value="<?php T::_e('Start restore'); ?>" type="submit" onclick="return restore_action('restore');">&nbsp;
          <input value="<?php T::_e('Download backup'); ?>" type="submit" onclick="return restore_action('download');">&nbsp;
<!--          <input value="<?php T::_e('Delete backup'); ?>" type="submit" onclick="return restore_action('delete');"> -->
          </td>
        </tr>

      </tbody>
    </table>
  </form>
</div>
