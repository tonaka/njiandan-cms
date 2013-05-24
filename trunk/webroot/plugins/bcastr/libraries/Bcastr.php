<?php defined('SYSPATH') OR die('No direct access allowed.');

class Bcastr {

	/**
	 * output bcastr html data
	 *
	 */
    public static function output($args = array()) {
        $default_args = array
(
    'uri'=>'',
    'limit'=>10,
    'star'=>False,
    'link'=>'',
    'image'=> '',
    'children'=>False,
    'width'=>800,
    'height'=>300,
    'id'=>'bcastr',
    'shuffle' => false,
    'roundCorner' => 5,  // 图片的圆角, 默认值是5
    'autoPlayTime' => 8, // 图片切换时间，默认值是8，单位秒
    'isHeightQuality' => False, // 图片缩小是否采用高质量的方法，默认值false
    'blendMode' => 'normal', // 图片的混合模式
    'transDuration' => 1, // 图片在切换过程中的时间，默认值1，单位秒
    'windowOpen' => '_self', // 图片连接的打开方式，默认值”_self”,使用本窗口打开, 也可以使用”_blank”,在新窗口打开
    'btnSetMargin' => 'auto 5 5 auto', // 按钮的位置，文字的位置，用了css的margin概念，默认值”auto 5 5 auto”，四个数值代表 上 右 下 左相对于播放器的距离，四个数值用空格分开，不需具体数值用”auto”填写 ，比如左上对齐并都有10像素的距离可以写 “10 auto auto 10″, 右下角对齐是”auto 10 10 auto”
    'btnDistance' => 20, // 每个按钮的距离，默认值20
    'isShowTitle' => False, // 是否显示标题，默认值”true”
    'titleBgColor' => '0xff6600', // 标题背景的颜色，默认0xff6600
    'titleTextColor' => '0xffffff', // 标题文字的颜色，默认0xffffff
    'titleBgAlpha' => '0.75', // 标题背景的透明度，默认0.75
    'titleFont' => 'Arial', // 标题文字的字体，默认值”Arial”
    'titleMoveDuration' => 1, // 标题背景动画的时间，默认值1，单位秒
    'btnAlpha' => 0.7, // 按钮的透明度，默认值0.7
    'btnTextColor' => '0xffffff', // 按钮文字的颜色，默认值0xffffff
    'btnDefaultColor' => '0×1B3433', // 按钮的默认颜色，默认值0×1B3433
    'btnHoverColor' => '0xff9900', // 按钮的默认颜色，默认值0xff9900
    'btnFocusColor' => '0xff6600', // 按钮当前颜色，默认值0xff6600
    'changImageMode' => 'click', // 切换图片的方法，默认值”click”,点击切换图片，还可以使用”hover”,鼠标悬停就切换图片
    'isShowBtn' => True, // 是否显示按钮，默认值”true”
    'scaleMode' => 'noBorder', // 图片放缩模式: 默认值是”noBorder”, “showAll”: 可以看到全部图片,保持比例,可能上下或者左右, “exactFil”: 放缩图片到舞台的尺寸,可能比例失调, “noScale”: 图片的原始尺寸,无放缩, “noBorder”: 图片充满播放器,保持比例,可能会被裁剪
    'transform' => 'alpha', // 图片动画模式: 默认值是”alpha”, “alpha”: 透明度淡入淡出, “blur”: 模糊淡入淡出, “left”: 左方图片滚动, “right”: 右方图片滚动, “top”: 上方图片滚动, “bottom”: 下方图片滚动, “breathe”: 有一点点地放缩的淡入淡出, “breatheBlur”: 有一点点地放缩的模糊淡入淡出，本页的例子就是这个
    'isShowAbout' => False, // 是否显示关于信息，默认值”false”
);
        $args += $default_args;

        $args['isHeightQuality'] = ($args['isHeightQuality'] and strtolower($args['isHeightQuality']) != 'false') ? 'true' : 'false';
        $args['isShowBtn'] = ($args['isShowBtn'] and strtolower($args['isShowBtn']) != 'false') ? 'true' : 'false';
        $args['isShowTitle'] = ($args['isShowTitle'] and strtolower($args['isShowTitle']) != 'false') ? 'true' : 'false';
        $args['isShowAbout'] = ($args['isShowAbout'] and strtolower($args['isShowAbout']) != 'false') ? 'true' : 'false';

        $uri = !empty($args['uri']) ? '/' . $args['uri'] : '';
        $swf_url = url::base() . 'webroot/plugins/bcastr/webroot/swf/bcastr4.swf';
        $output = '<object type="application/x-shockwave-flash" data="' . $swf_url . '?xml=' . url::site('bcastr') . $uri . '"  width="' . $args['width'] . '" height="' . $args['height'] . '" id="' . $args['id'] . '">
    <param name="movie" value="' . $swf_url . '?xml=' . url::site('bcastr') . '" />
</object>';

        $posts = PostsTag::post_list($args);

        if ($args['shuffle']) {
            $results = array();
            foreach($posts as $post) {
                $results[] = $post;
            }
            shuffle($results);
            $posts = $results;
        }

        $post_list = '';

		foreach($posts as $post) {
		    $link_key = !empty($args['link']) ? $args['link'] : 'link';
		    $image_key = !empty($args['image']) ? $args['image'] : 'thumb';
		    if ($post->$image_key) {
		        $post_list .= '
		        <item>
			        <link>' . $post->$link_key . '</link>
			        <image>' . $post->$image_key . '</image>
			        <title>' . $post->title . '</title>
		        </item>';
			}
	    }

        $config = '
	    <config>	
		    <roundCorner>' . $args['roundCorner'] . '</roundCorner>
		    <autoPlayTime>' . $args['autoPlayTime'] . '</autoPlayTime>
		    <isHeightQuality>' . $args['isHeightQuality'] . '</isHeightQuality>
		    <blendMode>' . $args['blendMode'] . '</blendMode>
		    <transDuration>' . $args['transDuration'] . '</transDuration>
		    <windowOpen>' . $args['windowOpen'] . '</windowOpen>
		    <btnSetMargin>' . $args['btnSetMargin'] . '</btnSetMargin>
		    <btnDistance>' . $args['btnDistance'] . '</btnDistance>
		    <titleBgColor>' . $args['titleBgColor'] . '</titleBgColor>
		    <titleTextColor>' . $args['titleBgColor'] . '</titleTextColor>
		    <titleBgAlpha>' . $args['titleBgAlpha'] . '</titleBgAlpha>
		    <titleMoveDuration>' . $args['titleMoveDuration'] . '</titleMoveDuration>
		    <btnAlpha>' . $args['btnAlpha'] . '</btnAlpha>	
		    <btnTextColor>' . $args['btnTextColor'] . '</btnTextColor>	
		    <btnDefaultColor>' . $args['btnDefaultColor'] . '</btnDefaultColor>
		    <btnHoverColor>' . $args['btnHoverColor'] . '</btnHoverColor>
		    <btnFocusColor>' . $args['btnFocusColor'] . '</btnFocusColor>
		    <changImageMode>' . $args['changImageMode'] . '</changImageMode>
		    <isShowBtn>' . $args['isShowBtn'] . '</isShowBtn>
		    <isShowTitle>' . $args['isShowTitle'] . '</isShowTitle>
		    <scaleMode>' . $args['scaleMode'] . '</scaleMode>
		    <transform>' . $args['transform'] . '</transform>
		    <isShowAbout>' . $args['isShowAbout'] . '</isShowAbout>
		    <titleFont>' . $args['titleFont'] . '</titleFont>
	    </config>';

        $output = '
<object type="application/x-shockwave-flash" data="' . $swf_url . '?xml=
	<data>
		<channel>' . $post_list . '
		</channel>' . $config . '
	</data>
"  width="' . $args['width'] . '" height="' . $args['height'] . '" id="' . $args['id'] . '">
<param name="movie" value="' . $swf_url . '?xml=
	<data>
		<channel>' . $post_list . '
		</channel>' . $config . '
	</data>" />
</object>
';
        return $output;
    }
}
