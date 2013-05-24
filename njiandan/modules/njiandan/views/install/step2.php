<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<form method="post" action="">
    <p><?php T::_e('Below you should enter your database connection details. If you&#8217;re not sure about these, contact your host.'); ?></p>
<?php
if (!empty($error_message)) {
?>
<script type="text/javascript">
function input_again() {
    document.getElementById('error_div').style.display='none';
    document.getElementById('input_table').style.display='';
}
</script>
<div id="error_div">
    <div class="error_message"><?php echo $error_message; ?></div>
    <p class="step"><a href="#" class="button" onclick="input_again(); return false;"> <?php T::_e('Input again'); ?> </a>
    <span style="padding-left:50px;"><input type="submit" name="run_auto_create" value="<?php T::_e('Run auto create'); ?>" class="button" /></span></p>
</div>
<?php
}
?>
<div id="input_table" <?php echo empty($error_message) ? '' : 'style="display:none"'; ?>>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="dbname"><?php T::_e('Database name'); ?></label></th>
            <td><?php echo form::input('dbname', $dbname, 'size="25"'); ?></td>
            <td><?php T::_e('The name of the database you want to run Njiandan in.'); ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="dbusername"><?php T::_e('Database username'); ?></label></th>

            <td><?php echo form::input('dbusername', $dbusername, 'size="25"'); ?></td>
            <td><?php T::_e('Your MySQL username'); ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="dbpassword"><?php T::_e('Database password'); ?></label></th>
            <td><?php echo form::input('dbpassword', $dbpassword, 'size="25"'); ?></td>
            <td><?php T::_e('...and MySQL password.'); ?></td>

        </tr>
        <tr>
            <th scope="row"><label for="dbhost"><?php T::_e('Database host'); ?></label></th>
            <td><?php echo form::input('dbhost', $dbhost, 'size="25"'); ?></td>
            <td><?php T::_e('99% chance you won&#8217;t need to change this value.'); ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="tbprefix"><?php T::_e('Table prefix'); ?></label></th>
            <td><?php echo form::input('tbprefix', $tbprefix, 'size="25"'); ?></td>
            <td><?php T::_e('If you want to run multiple Njiandan installations in a single database, change this.'); ?></td>
        </tr>
    </table>
    <p class="step"><input type="submit" value="<?php T::_e('Submit'); ?>" class="button" /></p>
</div>
</form>
