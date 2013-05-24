<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="content">
<form action="" id="settings" method="post">
    <table>
      <tbody>
        <tr class="finalrow">
          <th><?php T::_e('Admin uri'); ?></th>
          <td><?php echo form::input('admin_uri', $admin_uri, 'size="30"'); ?> <span class="error"><?php echo $uri_error; ?></span> <span class="u"><?php echo T::_('All characters except: ?, #, %, ^, &, *, ", \', <, >, \\'); ?></span></td>
        </tr>
        <tr class="finalrow">
          <th></th>
          <td>
<?php
if ($this->user->can('edit_options_admin_uri')) {
?>
          <input value="<?php T::_e('Update profile'); ?>" type="submit">
<?php
}
?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>

</div>
