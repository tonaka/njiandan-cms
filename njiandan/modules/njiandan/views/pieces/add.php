<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="content">
<form action="" id="settings" method="post">
    <table>
      <tbody>
        <tr>
          <th><?php T::_e('Piece title'); ?></th>
          <td><?php echo form::input(array('name'=>'title', 'value'=>$piece->title, 'size'=>50)); ?> <span class="error"><?php echo $title_error; ?></span></td>
        </tr>
        <tr>
          <th><?php T::_e('Tag'); ?></th>
          <td><?php echo form::input(array('name'=>'tag', 'value'=>$piece->uri, 'size'=>50)); ?> <span class="error"><?php echo $tag_error; ?></span> <span class="u"><?php T::_e('Alphabetical characters, numbers, underscores and dashes only.'); ?></span></td>
        </tr>
        <tr>
          <th><?php T::_e('Content'); ?></th>
          <td><?php echo form::textarea(array('name'=>'content', 'value'=>$piece->content, 'rows'=>13, 'cols'=>50)); ?></td>
        </tr>
        <tr class="finalrow">
          <th></th>
          <td>
          <input value="<?php T::_e($submit_title); ?>" type="submit">
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
