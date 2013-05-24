<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="content">
<form action="" id="settings" method="post">
    <table>
      <tbody>
        <tr class="finalrow">
          <th class="big_title"><?php echo $this->user->username; ?></th>
          <td><span class="adm"> <?php $this->user->is_superuser ? T::_e('Administrator') : ''; ?> </span></td>

        </tr>
        <tr>
          <th><?php T::_e('Password'); ?></th>
<?php
if (!empty($password_error) or (!empty($email_error) and !empty($password))) {
    $password_view = 'style="display: none;"';
    $password_edit = '';
} else {
    $password_view = '';
    $password_edit = 'style="display: none;"';
}
?>
          <td><div id="passView" <?php echo $password_view; ?>>
          <a href="#" onclick="showItem('passEdit'); hideItem('passView'); return false;"><?php T::_e('Change password'); ?></a> </div>
            <div <?php echo $password_edit; ?> id="passEdit" class="hidden">
            <?php echo form::password('password', $password, 'size="10"'); ?>
            &nbsp;&nbsp;  
<?php T::_e('Re-enter Password'); ?> <?php echo form::password('password_confirm', $password_confirm, 'size="10"'); ?> &nbsp;&nbsp; 
<a href="#" onclick="hideItem('passEdit'); showItem('passView'); clear_input_value('password'); clear_input_value('password_confirm'); return false;"><?php T::_e('Cancel'); ?></a> <span class="error"><?php echo $password_error; ?></span>
            </div>            
          </td>
        </tr>
        <tr>
          <th><?php T::_e('Mail'); ?></th>
          <td><?php echo form::input('email', $email, 'size="30"'); ?> <span class="error"><?php echo $email_error; ?></span></td>
        </tr>

        <tr class="finalrow">
          <th></th>
          <td>
<?php
if ($this->user->can('edit_profile')) {
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
