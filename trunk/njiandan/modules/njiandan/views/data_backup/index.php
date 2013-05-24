<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="content_content">
<form id="settings" method="post">
    <table>
      <tbody>
<!--
        <tr>
          <th><?php T::_e('File type'); ?></th>
          <td>
<?php echo form::radio('type', 'none', true, 'id="sql"').form::label('sql', T::_('sql')); ?>
<?php echo form::radio('type', 'gzip', false, 'id="gzip"').form::label('gzip', T::_('gzip')); ?>
<?php echo form::radio('type', 'bzip2', false, 'id="bzip2"').form::label('bzip2', T::_('bzip2')); ?>
-->
        </tr>
        <tr>
          <th><?php T::_e('Action'); ?></th>
          <td>
<?php echo form::radio('action', 'download', true, 'id="download"').form::label('download', T::_('Download')); ?>
<?php echo form::radio('action', 'store', false, 'id="store"').form::label('store', T::_('Store in server')); ?>
<?php echo form::radio('action', 'store_and_download', false, 'id="store_and_download"').form::label('store_and_download', T::_('Store and download')); ?>
          </td>
        </tr>
        <tr class="finalrow">
          <th></th>

          <td><input value="<?php T::_e('Submit'); ?>" type="submit">
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
