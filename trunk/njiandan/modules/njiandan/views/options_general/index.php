<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="content">
<form action="" id="settings" method="post">
    <table>
      <tbody>

        <tr>
          <th><?php T::_e('Site title'); ?></th>
          <td><?php echo form::input('site_title', $site_title, 'size="40"'); ?></td>
        </tr>
        <tr>
          <th><?php T::_e('Tagline'); ?></th>
          <td><?php echo form::input('site_description', $site_description, 'size="40"'); ?> <span class="u"><?php T::_e('In a few words, explain what this site is about.'); ?></span></td>

        </tr>
        <tr>
          <th><?php T::_e('Site url'); ?></th>
          <td><?php echo form::input('site_url', $site_url, 'size="40"'); ?> <span class="error"><?php echo $site_url_error; ?></span><span class="u"><?php T::_e('Njiandan home url.'); ?></span></td>
        </tr>

      <tr>
          <th><?php T::_e('Language'); ?></th> 
          <td>
          <?php echo form::dropdown('default_language', $languages, $default_language); ?>
           <span class="u"><?php T::_e('Default language for users on this site'); ?></span></p>
    </td></tr>
        <tr>
          <th><?php T::_e('Disk Space total size'); ?></th>

          <td><?php echo form::input('space_size', $space_size, 'size="40"'); ?></td>
        </tr>
        <tr>
          <th><?php T::_e('Datebase total size'); ?></th>
          <td><?php echo form::input('database_size', $database_size, 'size="40"'); ?></td>
        </tr>
        <tr>
          <th><?php T::_e('Upload max filesize'); ?></th>

          <td><?php echo form::input('upload_max_filesize', $upload_max_filesize, 'size="40"'); ?> <span class="u"><?php printf(T::_('The system upload max filesize is %s'), ini_get('upload_max_filesize')); ?></span></td>
        </tr>
        <tr>
          <th><?php T::_e('Editor width'); ?></th>
          <td><?php echo form::input('editor_width', $editor_width, 'size="40"'); ?> <span class="u"><?php T::_e('The wydiwyg editor width'); ?></span></td>
        </tr>

        <tr>
          <th><?php T::_e('Editor height'); ?></th>
          <td><?php echo form::input('editor_height', $editor_height, 'size="40"'); ?> <span class="u"><?php T::_e('The wydiwyg editor height'); ?></span></td>
        </tr>


        <tr>
          <th><?php T::_e('Default date format'); ?></th>
          <td>
          <?php echo form::dropdown('default_date_format', $date_formats, $default_date_format); ?>
          </td>
        </tr>
        <tr class="finalrow">
          <th></th>
          <td>
<?php
// check is use have role
if ($this->user->can('edit_options_general')) {
?>
          <input value="<?php T::_e('Update options'); ?>" type="submit">
<?php
}
?>
          </td>
        </tr>

      </tbody>
    </table>

  </form>
</div>
