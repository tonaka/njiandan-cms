<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="content">
<form action="" id="settings" method="post">
    <table>

      <tbody>
        <tr>
          <th>Thumbnail setting</th>
          <td>
            <ul><li><input name="data[Options][thumbnail_status]" value="0" id="status_no_use" onclick="set_thumbnail_is_display(this);" type="radio"  /> <label for="status_no_use">Inactive thunbnail</lable></li>
                <li><input name="data[Options][thumbnail_status]" value="1" id="status_1" onclick="set_thumbnail_is_display(this);" type="radio" checked="checked" /> <label for="status_1">Make thumbnail in setting image size range</lable></li>

                <li><input name="data[Options][thumbnail_status]" value="2" id="status_2" onclick="set_thumbnail_is_display(this);" type="radio"  /> <label for="status_2">Make thumbnail in setting image size</lable></li>
            </ul>
            <table id="thunmbnail_setting" style="">
                <tbody>
                <tr class="finalrow">
                <th>Quality</th>
                <td><input name="data[Options][thumbnail_quality]" value="100"  /> <span class="u">Set the Thunbmail quality, the range is 0~100(integer),the number more big, the quality more better, but the size is more big.</span></td>

                </tr>
                <tr class="finalrow">
                <th>Image Size</th>
                <td><input name="data[Options][thumbnail_width]" value="230"  /> X 
                    <input name="data[Options][thumbnail_height]" value="140"  /> <span class="u">(width x height) If the image size smaller then this setting, Thubnail won&#8217;t be create.</span></td>
                </tr>
                </tbody>

            </table>
        </td>
        </tr>

        <tr>
          <th>Watermark</th>

          <td>
               <table>

                    <tbody>
                    <tr class="finalrow">
                    <th>Use watermark</th>
                    <td><input name="data[Options][is_watermark]" value="1" onclick="set_watermark_is_display(this);" type="checkbox"  /></td>
                    </tr>
                    </tbody>
               </table>
               <div style="display:none;" id="watermark_setting">

                <table>
                    <tbody>
                    <tr class="finalrow">
                    <th>Watermark position</th>
                    <td>
                        <div class="watermark_position">
                        <ul>
                            <li><input name="data[Options][watermark_position]" value="top_left" id="position_top_left" type="radio"  /> <label for="position_top_left">top left</lable></li>

                            <li><input name="data[Options][watermark_position]" value="top_center" id="position_top_center" type="radio"  /> <label for="position_top_center">top center</li>
                            <li><input name="data[Options][watermark_position]" value="top_right" id="position_top_right" type="radio"  /> <label for="position_top_right">top right</li>
                        </ul>
                        </div>
                        <div class="watermark_position">
                        <ul>
                            <li><input name="data[Options][watermark_position]" value="middle_left" id="position_middle_left" type="radio"  /> <label for="position_middle_left">middle left</li>

                            <li><input name="data[Options][watermark_position]" value="middle_center" id="position_middle_center" type="radio" checked="checked" /> <label for="position_middle_center">middle center</li>
                            <li><input name="data[Options][watermark_position]" value="middle_right" id="position_middle_right" type="radio"  /> <label for="position_middle_right">middle right</li>
                        </ul>
                        </div>
                        <div class="watermark_position">
                        <ul>
                            <li><input name="data[Options][watermark_position]" value="bottom_left" id="position_bottom_left" type="radio"  /> <label for="position_bottom_left">bottom left</li>

                            <li><input name="data[Options][watermark_position]" value="bottom_center" id="position_bottom_center" type="radio"  /> <label for="position_bottom_center">bottom center</li>
                            <li><input name="data[Options][watermark_position]" value="bottom_right" id="position_bottom_right" type="radio"  /> <label for="position_bottom_right">bottom right</li>
                        </ul>
                        </div>

                        </td>
                    </tr>

                    <tr class="finalrow">
                    <th>Watermark add condition</th>
                    <td><input name="data[Options][watermark_condition_width]" value="0"  /> X 
                    <input name="data[Options][watermark_condition_height]" value="0"  /> <span class="u">If the image size smaller then the watermark, the watermark won&#8217;t be add, if 0 size use the watermark.</span></td>
                    </tr>
                    <tr class="finalrow">
                    <th>Watermark transparent</th>

                    <td><input name="data[Options][watermark_transparent]" value="65"  /></td>
                    </tr>
                    </tbody>
                </table>
                </div>
          </td>
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
