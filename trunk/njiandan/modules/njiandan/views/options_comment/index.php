<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="content">
<form action="" id="settings" method="post">
    <table>
      <tbody>
        <tr class="finalrow">
          <th><?php T::_e('Is comment need approve'); ?></th>
          <td><?php echo form::checkbox('approve_status', '1', $approve_status); ?> <span class="u"></span></td>
        </tr>
        <tr class="finalrow">
          <th></th>
          <td>
<?php
if ($this->user->can('edit_options_admin_uri')) {
?>
          <input value="<?php T::_e('Update options'); ?>" type="submit" name="submit">
<?php
}
?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>

</div>
