<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
Template type: list
Template name: 列表模板
*/
require_once('header.php');
?>

<header class="jumbotron subhead" id="overview">
	<div class="container">
		<h1><?php echo DiagramTag::diagram()->title; ?></h1>
		<p class="lead">努力把编程变成享受...</p>
	</div>
</header>

<div class="container">
	<div class="row">
		<div class="span3 bs-docs-sidebar">
			<ul class="nav nav-list bs-docs-sidenav">
				<li><a href="<?php echo url::base(true); ?>njiandancase"><i class="icon-chevron-right"></i>所有案例</a></li>
				<?php $submenuList = DiagramTag::submenu(PageTag::uri()); ?>
				<?php foreach ($submenuList as $submenu) { ?> 
				<li><a href="<?php echo url::base(true) . $submenu->uri; ?>"><i class="icon-chevron-right"></i><?php echo $submenu->title; ?></a></li>
				<?php } ?>
			</ul>
		</div>
		<div class="span9">
			<?php echo DiagramTag::position_as_ul(); ?>

			<center><a href="<?php echo url::base(true); ?>feedback" class="btn btn-info">如果您的网站是使用N简单CMS创建的，点击这里提交给我们</a></center>

			<div class="row-fluid" style="margin-top: 20px;">
				<ul class="thumbnails">
					<?php foreach(PostsTag::post_list(array('children' => true)) as $post) { ?>
					<li class="span4">
						<div class="thumbnail">
							<img data-src="holder.js/300x200" <?php if ($post->thumb_original) { ?>src="<?php if (SUBDIRECTORY) echo "/".SUBDIRECTORY ?><?php echo "/" . $post->thumb_original; ?>" <?php } ?> alt="<?php echo $post->title; ?>">
							<div class="caption">
								<h3><?php echo $post->title; ?></h3>
								<p></p>
								<p><a href="<?php echo url::base(true) . $post->link; ?>" class="btn">查看详细</a> <a href="<?php echo $post->site_url; ?>" target="_blank" class="btn btn-primary">打开网站</a></p>
							</div>
						</div>
					</li>
					<?php } ?>
				</ul>
			</div>

			<center>
			<?php
				echo PostsTag::pagelink(array('pagination' => 'bootstrap_pagination', 'children' => true));
			?>
			</center>
		</div>
	</div>
</div>

<?php require_once('footer.php'); ?>

<script>
  $(function(){
	  $('#njiandancase').attr("class", "active");
  });	
</script>