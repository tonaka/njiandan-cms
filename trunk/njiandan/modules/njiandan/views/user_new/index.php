<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<script type="text/javascript">
function is_a_admin(this_status) {
    if (this_status) {
        $('#admin_manage_roles').hide();
    } else {
        $('#admin_manage_roles').show();
    }
}
</script>
<div id="content">
  <h2><?php T::_e($content_title); ?></h2>
<p></p>

<form action="" id="settings" method="post">
    <table>
      <tbody>
        <tr>
          <th><?php T::_e('Username'); ?></th>
          <td><?php echo form::input('username', $username, 'size="20"'); ?> <span class="error"><?php echo $error_username; ?></span></td>
        </tr>

        <tr>
          <th><?php T::_e('Password'); ?></th>
          <td>
<?php
if (empty($user_id) || !empty($error_password)) {
    $password_view = 'style="display: none;"';
    $password_edit = '';
} else {
    $password_view = '';
    $password_edit = 'style="display: none;"';
}
?>
<div id="passView" <?php echo $password_view; ?>>
          <a href="#" onclick="showItem('passEdit'); hideItem('passView'); return false;"><?php T::_e('Change password'); ?></a> </div>
            <div <?php echo $password_edit; ?> id="passEdit" class="hidden">
            <?php echo form::password('password', $password, 'size="10"'); ?>
            &nbsp;&nbsp;  
<?php T::_e('Re-enter Password'); ?> <?php echo form::password('password_confirm', $password_confirm, 'size="10"'); ?> &nbsp;&nbsp; 
<?php
if (!empty($user_id)) {
?>
<a href="#" onclick="hideItem('passEdit'); showItem('passView'); clear_input_value('password'); clear_input_value('password_confirm'); return false;"><?php T::_e('Cancel'); ?></a>
<?php
}
?>
 <span class="error"><?php echo $error_password; ?></span>
            </div>

           </td>
        </tr>

        <tr>
          <th><?php T::_e('E-mail'); ?></th>
          <td><?php echo form::input('email', $email, 'size="20"'); ?> <span class="error"><?php echo $error_email; ?></span></td>
        </tr>
        <tr>
          <th><?php T::_e('Administrator'); ?></th>
          <td><?php echo form::checkbox('is_admin', 'admin', $is_admin, 'onclick="is_a_admin(this.checked);"'); ?> <span class="u"><?php T::_e('If this user is a Administrator, it will have all njiandan capability.'); ?></span></td>
        </tr>
<!--
      <tr id="manage_role" <?php echo !empty($is_admin) ? 'style="display:none;"' : ''; ?>>
        <th>文章管理权限</th>
        <td>

        <table>
          <tbody><tr class="finalrow">
            <th>All</th>
            <td>
<input name="data[Manage][check_all_own]" id="cap_manage_check_all_own" onclick="mod_all_checkbox('cap_manage_own');" value="all" class="cap_manage_own" type="checkbox"><label for="cap_manage_check_all_own"> 仅发表和管理自己的文章</label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input name="data[Manage][check_all_all]" id="cap_manage_check_all_all" onclick="mod_all_checkbox('cap_manage_all');" value="all" class="cap_manage_all" type="checkbox"><label for="cap_manage_check_all_all"> 还可以管理该分类下其他人的文章</label>
  </td>

          </tr>
          <tr class="finalrow">
            <th>Njiandan皮肤</th>
            <td>
<input name="data[Manage_category][cap_manage_own_11]" id="cap_manage_own_11" value="11" class="cap_manage_own" onclick="change_check_all_status('cap_manage_own', 'cap_manage_check_all_own')" type="checkbox"><label for="cap_manage_own_11"> 仅发表和管理自己的文章</label>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input name="data[Manage_category][cap_manage_all_11]" id="cap_manage_all_11" value="11" class="cap_manage_all" onclick="change_check_all_status('cap_manage_all', 'cap_manage_check_all_all')" type="checkbox"><label for="cap_manage_all_11"> 还可以管理该分类下其他人的文章</label>

</td></tr>
          <tr class="finalrow">
            <th>使用Njiandan制作的网站 </th>
            <td>
<input name="data[Manage_category][cap_manage_own_18]" id="cap_manage_own_18" value="18" class="cap_manage_own" onclick="change_check_all_status('cap_manage_own', 'cap_manage_check_all_own')" type="checkbox"><label for="cap_manage_own_18"> 仅发表和管理自己的文章</label>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input name="data[Manage_category][cap_manage_all_18]" id="cap_manage_all_18" value="18" class="cap_manage_all" onclick="change_check_all_status('cap_manage_all', 'cap_manage_check_all_all')" type="checkbox"><label for="cap_manage_all_18"> 还可以管理该分类下其他人的文章</label>

</td></tr>
          <tr class="finalrow">
            <th>技术与开发</th>
            <td>
<input name="data[Manage_category][cap_manage_own_20]" id="cap_manage_own_20" value="20" class="cap_manage_own" onclick="change_check_all_status('cap_manage_own', 'cap_manage_check_all_own')" type="checkbox"><label for="cap_manage_own_20"> 仅发表和管理自己的文章</label>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input name="data[Manage_category][cap_manage_all_20]" id="cap_manage_all_20" value="20" class="cap_manage_all" onclick="change_check_all_status('cap_manage_all', 'cap_manage_check_all_all')" type="checkbox"><label for="cap_manage_all_20"> 还可以管理该分类下其他人的文章</label>

</td></tr>
          <tr class="finalrow">
            <th>我们生活的世界</th>
            <td>
<input name="data[Manage_category][cap_manage_own_21]" id="cap_manage_own_21" value="21" class="cap_manage_own" onclick="change_check_all_status('cap_manage_own', 'cap_manage_check_all_own')" type="checkbox"><label for="cap_manage_own_21"> 仅发表和管理自己的文章</label>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input name="data[Manage_category][cap_manage_all_21]" id="cap_manage_all_21" value="21" class="cap_manage_all" onclick="change_check_all_status('cap_manage_all', 'cap_manage_check_all_all')" type="checkbox"><label for="cap_manage_all_21"> 还可以管理该分类下其他人的文章</label>

</td></tr>
        </tbody></table>
        </td>
      </tr>
-->
    <tr id="admin_manage_roles" <?php echo !empty($is_admin) ? 'style="display:none;"' : ''; ?>>
        <th><?php T::_e('Admin Manage Roles'); ?></th>
        <td>
            <table>

          <tbody>
<?php

foreach(AdminMenu::$menus as $menus) {
    foreach($menus as $menu) {
        echo '<tr class="finalrow">';
        echo '<th>', T::_($menu['title']), '</th>';
        echo '<td>';
        $checked = false;
        foreach($menu['roles'] as $key => $role) {
            if (isset($admin_roles[$role])) {
                $checked = true;
            } else {
                $checked = false;
            }
            echo form::checkbox(array('name'=>'AdminRoles[]', 'id'=>$role, 'value'=>$role), '', $checked).form::label($role, T::_($key));

        }
        echo '</td>';
        echo "</tr>\n";
    }
}
?>

        </tbody></table>
        </td>
      </tr>


        <tr class="finalrow">
          <th></th>
          <td><input value="<?php T::_e($submenu); ?>" type="submit">
<?php
if (!empty($user_id)) {
?>
<input value="<?php T::_e('Cancel'); ?>" class="cancel" onclick="window.location='<?php echo url::admin_site('users'); ?>';" type="button">
<?php
}
?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
