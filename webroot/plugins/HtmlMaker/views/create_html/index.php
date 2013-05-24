<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php echo html::admin_script('html_maker_jquery.simplemodal-1.3.3.min.js'); ?>
<?php echo html::admin_stylesheet('html_maker_style.css'); ?>
<script type="text/javascript">
$(document).ready(function() {
    $('#create_html').bind('click', function() {
        create_diagram(1);
    });
});

function create_diagram(page) {
    $.modal('<div><h2>开始生成网站架构...</h2><div id="loading"></div></div>');
    $.getJSON('<?php echo url::admin_site('create_html/create_diagram'); ?>/' + page, function(data) {
        if (data.status == 'continue') {
            $.modal.close();
            $.modal('<div><h2>已生成网站架构:' + data.count + '</h2><div id="loading"></div></div>');
            create_diagram(page + 1);
        } else {
            $.modal.close();
            $.modal('<div><h2>网站架构图生成完成.</h2><h2>开始生成内容页面...</h2><div id="loading"></div></div>');
            create_post(1);
        }
    });
}

function create_post(page) {
    $.getJSON('<?php echo url::admin_site('create_html/create_post'); ?>/' + page, function(data) {
        if (data.status == 'continue') {
            $.modal.close();
            $.modal('<div><h2>网站架构图生成完成.</h2><h2>已生成内容页面:' + data.count + '</h2><div id="loading"></div></div>');
            create_post(page + 1);
        } else {
            $.modal.close();
            $.modal('<div><h2>网站架构图生成完成.</h2><h2>内容页面生成完成.</h2><h2>开始生成列表页.</h2><div id="loading"></div></div>');
            create_list(1, 1);
        }
    });
}

function create_list(count, page) {
    $.getJSON('<?php echo url::admin_site('create_html/create_list'); ?>/' + count + '/' + page, function(data) {
        if (data.status == 'continue') {
            $.modal.close();
            $.modal('<div><h2>网站架构图生成完成.</h2><h2>内容页面生成完成.</h2><h2>正在生成列表' + data.count + '的第' + data.page + '页</h2><div id="loading"></div></div>');
            create_list(count, page + 1);
        } else if (data.status == 'done') {
            $.modal.close();
            $.modal('<div><h2>网站架构图生成完成.</h2><h2>内容页面生成完成.</h2><h2>正在生成列表' + data.count + '的第' + data.page + '页</h2><div id="loading"></div></div>');
            create_list(count +1, 1);
        } else if (data.status == 'all_done') {
            $.modal.close();
            show_noticemessage('整站html生成成功!');
        }
    });
}
</script>
<div id="content">
<h2><?php T::_e('整站生成html'); ?></h2>

<p><?php T::_e('整站生成html,可以减少服务器负担. 生成后原有的/index.php/的方式可以继续访问,因此每次你修改和调整程序查看效果时,不需要重新生成html.直接通过/index.php/的方式访问即可.'); ?></p>
    <input value="<?php T::_e('整站生成html'); ?>" type="submit" id="create_html">
<p>
</p>
</div>  
