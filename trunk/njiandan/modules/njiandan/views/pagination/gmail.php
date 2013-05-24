<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * gmail pagination style
 * 
 * @preview   « Newest ‹ Newer 11 - 15 of 28 Older › Oldest » 
 */
?>

<span class="pagination">
<?php
$pagelink = '';
$pagelink_info = sprintf(T::_("<b>%s</b> - <b>%s</b> of <b>%s</b>"), $current_first_item, $current_last_item, $total_items);

$older_page = ' <a href="' . str_replace('{page}', $next_page, $url) . '">' . T::_('Older') . ' ›</a> ';
$oldest_page = ' <a href="' . str_replace('{page}', $total_pages, $url) . '">' . T::_('Oldest') . ' »</a> ';

$newer_page = ' <a href="' . str_replace('{page}', $previous_page, $url) . '">‹ ' . T::_('Newer') . '</a> ';
$newest_page = ' <a href="' . str_replace('{page}', 1, $url) . '">« ' . T::_('Newest') . '</a> ';

if ($total_pages == 1 || $total_pages == 0) {
    $pagelink = $pagelink_info;
} else if ($current_page == 1) {
    $pagelink = $pagelink_info . $older_page;
    if ($total_pages >= 3) {
        $pagelink .= $oldest_page;
    }
} else if ($current_page == $total_pages) {
    if ($total_pages >= 3) {
        $pagelink = $newest_page;
    }
    $pagelink .= $newer_page . $pagelink_info;
} else {
    if ($current_page >= 3) {
        $pagelink = $newest_page;
    }
    $pagelink .= $newer_page . $pagelink_info;
    $pagelink .= $older_page;

    if ($total_pages - $current_page >= 2) {
        $pagelink .= $oldest_page;
    }
}
echo $pagelink;
?>
</span>
