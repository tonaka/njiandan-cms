<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="content">
<form action="" id="settings" method="post">
    <table>
      <tbody>

        <tr>
          <th>Using cache</th>
          <td><input name="data[Options][using_cache]" value="on" type="checkbox"  /></td>
        </tr>
        <tr>
          <th>Cache timeout</th>
          <td><input name="data[Options][cache_timeout]" size="20" value="600"  /> <span class="u">The default is 600, The cache will be update when cache timeout.</span></td>

        </tr>
        <tr class="finalrow">
          <th></th>

          <td><input value="Update options" type="submit">
          </td>
        </tr>
      </tbody>
    </table>

  </form>
</div>
