<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('.tr_row').mouseover(function(){$(this).addClass('over');}).mouseout(function(){$(this).removeClass('over');});
});
</script>
<div id="content">
  <form action="" id="list" method="post">
    <div style="display: none;" id="confirm_delete" class="d">
      <p class="warning"> <b> Are you sure you want to delete selected users? 
         </b><br><br>
       <input type="radio" value="1" name="delete_way" id="inactive_user">  <label for="inactive_user"> Inactive users only </label>&nbsp;&nbsp;&nbsp;&nbsp;<span class="err"> Just this user cant&#8217;t login.</span> <br>
        <input type="radio" value="2" name="delete_way" id="delete_user_only">  <label for="delete_user_only"> Delete users only </label>&nbsp;&nbsp;&nbsp;&nbsp;<span class="err"> Delete users and keep posts.</span> <br>

        <input type="radio" value="3" name="delete_way" id="delete_user_and_posts">  <label for="delete_user_and_posts"> Delete users and posts </label>&nbsp;&nbsp;&nbsp;&nbsp;<span class="err"> All posts in the user accounts will be lost.</span><br>
      </p>
      <input type="button" name="delete" value="Delete users" onclick="delete_users_now();">
      <input type="button" name="Button" value="Cancel" onclick="hideItem('confirm_delete');">
    </div>
<div id="user_table_list">
    <table>

      <tr>
        <th colspan="3" class="tablebar"></th>
        <td colspan="2" class="tablebar" align="right"><?php echo $pagelink; ?></td>
      </tr>

      <tr>
        <th><?php T::_e('ID'); ?></th>
        <th><?php T::_e('Username'); ?></th>
        <th><?php T::_e('Status'); ?></th>
        <th><?php T::_e('Last signed in'); ?></th>
        <th><?php T::_e('Action'); ?></th>
      </tr>
<?php
foreach($users as $user) {
    if ($user->status == 1) {
        $status = 'Active';
    } else if ($user->status == 2) {
        $status = 'Inactive';
    } else {
        $status = 'Deleted';
    }
?>
      <tr class="tr_row">
        <td><?php echo $user->id; ?></td>
        <td><?php echo $user->username; ?></td>
        <td><?php T::_e($status); ?></td>
        <td><?php echo !empty($user->last_login) ? date('Y-m-d H:i', $user->last_login) : T::_('Never logged in'); ?></td>
<?php
    // if is a supper user
    if ($user->id == 1 or $user->id == $this->user->id) {
        echo '<td></td>';
    } else {
        echo '<td>';
        if ($this->user->can('add_new_user')) {
            echo html::admin_anchor("user_new/index/$user->id?redirect_uri=" . Router::$complete_uri, T::_('Edit'));
        }
        if ($this->user->can('delete_user')) {
            echo html::admin_anchor("users/delete/$user->id?redirect_uri=" . Router::$complete_uri, T::_('Delete'), array('onclick'=>'return confirm(\'' . sprintf(T::_('Are you sure you want to delete user %s? All posts in the user accounts will be lost.'), $user->username) . '\')'));
        }
        echo '</td>';

    }
?>
      </tr>
<?php
}
?>
      <tr>
        <td colspan="3" class="tablebar"> </td>
        <td colspan="2" class="tablebar" align="right"><?php echo $pagelink; ?></td>

      </tr>
    </table>
</div>
  </form>
</div>
