<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('.<?php echo $driver; ?>').show();
});
function change_driver(value) {
    $('.smtp').hide();
    $('.sendmail').hide();
    $('.' + value).show();
    
}
</script>
<style>
.smtp,.sendmail {
    display:none;
}
</style>
<div id="content">
<form action="" id="settings" method="post">
    <table>
      <tbody>
        <tr>
          <th><?php T::_e('Driver'); ?></th>
          <td><?php echo form::radio('driver', 'native', $native, 'id="native" onclick="change_driver(this.value);"').form::label('native', T::_('Default') . ' (' . T::_('Use php mail() function, need server support this module.') . ')'); ?><br>
          <?php echo form::radio('driver', 'smtp', $smtp, 'id="smtp" onclick="change_driver(this.value);"').form::label('smtp', T::_('smtp') . ' (' . T::_('Use my own email, need your email server support smtp.') . ')'); ?><br>
          <?php echo form::radio('driver', 'sendmail', $sendmail, 'id="sendmail" onclick="change_driver(this.value);"').form::label('sendmail', T::_('sendmail') . ' (' . T::_('This way just support linux server and installed sendmail.') . ')'); ?>
          </td>
        </tr>
        <tr class="smtp">
          <th><?php T::_e('Mail (SMTP) Server'); ?></th>
          <td><?php echo form::input('smtp_hostname', $smtp_hostname, 'size="30"'); ?> <span class="error"><?php echo $smtp_hostname_error; ?></span> <span class="u"><?php T::_e('ex'); ?>: smtp.njiandan.com</span></td>
        </tr>
        <tr class="smtp">
          <th><?php T::_e('Port'); ?></th>
          <td><?php echo form::input('smtp_port', $smtp_port, 'size="30"'); ?> <span class="u"><?php T::_e('Default is 25, To use secure connections with SMTP, set "port" to 465 instead of 25.'); ?></span></td>
        </tr>
        <tr class="smtp">
          <th><?php T::_e('Mail'); ?></th>
          <td><?php echo form::input('smtp_mail', $smtp_mail, 'size="30"'); ?> <span class="error"><?php echo $smtp_mail_error; ?></span> </td>
        </tr>
        <tr class="smtp">
          <th><?php T::_e('Password'); ?></th>
          <td><?php echo form::input('smtp_password', $smtp_password, 'size="30"'); ?> <span class="error"><?php echo $smtp_password_error; ?></span> </td>
        </tr>
        <tr class="sendmail">
          <th><?php T::_e('sendmail path'); ?></th>
          <td><?php echo form::input('sendmail_path', $sendmail_path, 'size="30"'); ?> <span class="u"><?php echo T::_e('ex'); ?>: /usr/sbin/sendmail</span></td>
        </tr>
        <tr class="finalrow">
          <th></th>
          <td>
<?php
if ($this->user->can('edit_options_mail')) {
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
