<div id="content">
    <form id="list">
    <table>
      <tr>
        <th class="tablebar"><?php T::_e('Plugin'); ?></th>
        <th class="tablebar"><?php T::_e('Version'); ?></th>
        <th class="tablebar"><?php T::_e('Description'); ?></th>
        <th class="tablebar"><?php T::_e('Status'); ?></th>
        <th class="tablebar"><?php T::_e('Action'); ?></th>
      </tr>
<?php
foreach($plugins as $plugin) {
    if ($plugin['status'] == 'Active') {
        $title = T::_('Active');
        $action = html::admin_anchor('plugins/deactivate/' . $plugin['folder'], T::_('Deactivate'), array('onclick'=>'return confirm("' . sprintf(T::_('Deactive pluign %s will delete all data about this plugin, Are you sure you want deactive plugin %s?'), $plugin['folder'], $plugin['folder']) . '")'));
    } else if ($plugin['status'] == 'Inactive') {
        $title = T::_('Inactive');
        $action = html::admin_anchor('plugins/activate/' . $plugin['folder'], T::_('Activate'));
    }
?>
      <tr class="tr_row">
        <td><?php echo $plugin['name']; ?></td>
        <td><?php echo $plugin['version']; ?></td>
        <td><?php echo $plugin['description']; ?> <?php printf(T::_('By <a href="%s" target="_blank">%s</a>'), $plugin['uri'], $plugin['author']); ?></td>
        <td><?php echo $title ?></td>
        <td><?php echo $action; ?></td>
      </tr>
<?php
}
?>
    </table>
    </form>
<br class="clear">
<h3><?php T::_e('Get More Plugins'); ?></h3>
<p><?php T::_e('You can find additional plugins for your site in the njiandan plugins directory. To install a plugin you generally just need to upload the plugin into your <code>webroot/plugins</code> directory. Once a plugin is uploaded, you should see it on this page.'); ?></p>
</div>
