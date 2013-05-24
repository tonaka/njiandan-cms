<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<script type="text/javascript">
$(document).ready(function() {
    $('.post_item').bind('click', function(e) {
        var select_all_box = true
        $("input[name='post_item']").each(function() {
            if (this.checked == false) {
                select_all_box = false
            }
            document.getElementById('select_all').checked = select_all_box
        });
    });
    $('.tr_row').mouseover(function(){$(this).addClass('over');}).mouseout(function(){$(this).removeClass('over');});
<?php
if ($this->user->can('star_post')) {
?>
    $('.star').bind('click', function(e) {
        var this_id = this.id;
        $.get('<?php echo url::admin_site('/posts/change_star'); ?>/' + this_id + '?time=' + time_now(), function(data) {
            var image = ''
            if (data == 'on') {
                image = '<?php echo html::admin_image('star_on.gif'); ?>';
            } else {
                image = '<?php echo html::admin_image('star_off.gif'); ?>';
            }
            $('#'+this_id).html(image);
        });
    });
<?php
}
?>
});
function choose_diagram(diagram_id) {
    var url = '<?php echo url::admin_site('/posts'); ?>' + '/' + diagram_id;
    window.location = url;
}

function all_checkbox(status) {
    post_status = true;
    if (status == false) {
        post_status = false;
    }
    $("input[name='post_item']").each(function() {
        this.checked = post_status;
    });
}
<?php
if ($this->user->can('delete_post')) {
?>
function delete_posts() {
    var ids = '';
    // get all checked
    $("input[name='post_item']:checked").each(function() {
        ids = this.value + '.' + ids;
    });

    // no post checked
    if (ids == '') {
        show_noticemessage('<?php T::_e('No conversations selected.'); ?>');
        return false;
    }
    // delete the posts
    $.get('<?php echo url::admin_site('/posts/delete'); ?>/' + ids + '?time=' + time_now(), function(data) {
        if (data == 'delete_done') {
             window.location.href='<?php echo url::site(Router::$complete_uri); ?>';
        }
    });
}
<?php
}
?>
</script>
<div id="content">
 <form id="list">
    <table>
<?php
if ($all_posts_count) {
?>
      <tr>
        <td colspan="4" class="tablebar"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left"><span class="tablebar_content">
<?php
if ($this->user->can('delete_post')) {
?>
            <input value="<?php T::_e('Delete'); ?>" type="button" onclick="delete_posts();">
<?php
}
?>
        <select name="category" id="category" onchange="choose_diagram(this.value);">
        <?php echo $select_options; ?>
       </select></span>
            <td align="right"><?php echo $pagelink; ?></td>
          </tr>
        </table></td>
        </tr>
<?php
if (count($posts)) {
?>
      <tr>
        <th> <input value="" type="checkbox" name="select_all" id="select_all" onclick="all_checkbox(this.checked);"> <?php T::_e('Title'); ?></th>
        <th><?php T::_e('Categories'); ?></th>
        <th><?php T::_e('Author'); ?></th>
        <th><?php T::_e('When'); ?></th>
      </tr>
<?php
foreach($posts as $post) {
    $title = empty($post->title) ? '<span style="color:#999;">(' . T::_('no title') . ')</span>' : $post->title;
    $star = $post->is_star ? html::admin_image('star_on.gif') : html::admin_image('star_off.gif');
?>
      <tr class="tr_row">
        <td><input value="<?php echo $post->id; ?>" type="checkbox" name="post_item" class="post_item">
        <a class="star" id="<?php echo $post->id; ?>"><?php echo $star; ?></a>
        <?php echo html::admin_anchor("/post_new/$post->id/edit?redirect_uri=" . Router::$complete_uri, $title); ?></td>
        <td> <?php echo $post->diagram->title; ?> </td>
        <td> <?php echo $post->user->username; ?> </td>
        <td> <?php echo date::default_format($post->date); ?></td>
      </tr>
<?php
}
?>
      <tr>
        <td colspan="4" class="tablebar"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left"><span class="tablebar_content">
<?php
if ($this->user->can('delete_post')) {
?>
            <input value="<?php T::_e('Delete'); ?>" type="button" onclick="delete_posts();">
<?php
}
?>
        <select name="category" id="category" onchange="choose_diagram(this.value);">
<?php echo $select_options; ?>
       </select></span>
    </td>
        <td align="right"><?php echo $pagelink; ?></td>

          </tr>
        </table></td>
        </tr>
<?php
} else {
?>
    <tr>
        <td colspan="4" height="40"><span class="new">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php T::_e('You have no posts in this category!'); ?> </new></td>
    </tr>
<?php
}
} else {
?>
    <h3><?php T::_e('You have no posts now.'); ?> &nbsp; &nbsp; <?php echo html::admin_anchor('post_new', T::_('Add a post first.')); ?></h3>
<?php
}
?>
    </table>
  </form> 
</div>
