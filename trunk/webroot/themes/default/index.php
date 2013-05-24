<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
Template type: page
Template name: 首页模板
*/
require_once('header.php');
?>

<div class="jumbotron masthead">
	<div class="container">
		<h1>N简单CMS</h1>
		<p>简单快速的小型web建站CMS程序。</p>
		<p>
		  <a href="#" class="btn btn-primary btn-large" >下载N简单CMS</a>
		</p>
		<ul class="masthead-links">
			<li><a href="<?php echo url::base(true); ?>njiandancase">案例</a></li>
			<li><a href="#">教程</a></li>
			<li><a href="<?php echo url::base(true); ?>njddoc">文档</a></li>
			<li id="version">Version 1.3.0</li>
		</ul>
	</div>
</div>

<div class="bs-docs-social">
	<div class="container">
		<ul class="bs-docs-social-buttons">
			<li>
				<span class="label label-success">N简单CMS开发QQ群:</span> 124110889
			</li>
		</ul>
	</div>
</div>

<div class="container">
	<div class="marketing">
		<h1>基于N简单CMS开发的网站</h1>
		<p class="marketing-byline">这里只列举了部分，如果您的网站是基于N简单CMS开发的，可以<a href="#" class="btn btn-info">点击这里</a>提交给我</p>
	</div>

	<div class="row-fluid">
		<?php foreach(PostsTag::post_list(array('children'=>true, 'uri' => 'njiandancase', 'limit' => 3)) as $post) { ?>
		<div class="span4">
			<a href="<?php echo url::base(true) . $post->link; ?>" title="<?php echo $post->title; ?>"><img class="marketing-img" src="<?php if (SUBDIRECTORY) echo "/".SUBDIRECTORY ?><?php echo "/" . $post->thumb_original; ?>"></a>
			<h2> 
				<a href="<?php echo url::base(true) . $post->link; ?>" title="<?php echo $post->title; ?>"><?php echo $post->title; ?><br><small><?php echo $post->title; ?></small></a>
			</h2>
		</div>
		<?php } ?>
	</div>
</div>

<?php require_once('footer.php'); ?>


<script src="<?php echo url::themesPath() ?>/js/Bubble.js"></script>
<script src="<?php echo url::themesPath() ?>/js/jquery.grumble.js"></script>

<script>
  $(function(){
	  $('#index').attr("class", "active");

	  var $me = $('#version'), interval;

	  $me.grumble(
		{
		  angle: 55,
		  text: 'New！',
		  distance: 40,
		  showAfter: 500,
		  hideOnClick: false,
		  sizeRange: [50], 
		  onShow: function(){
			var angle = 55, dir = 1;
			interval = setInterval(function(){
			  (angle > 65 ? (dir=-1, angle--) : ( angle < 55 ? (dir=1, angle++) : angle+=dir));
			  $me.grumble('adjust',{angle: angle});
			},55);
		  },
		  onHide: function(){
			clearInterval(interval);
		  }
		}
	  );
  });	
</script>