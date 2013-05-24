<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
Template type: list
Template name: 日志列表模板
*/
require_once('header.php');
?>
 
<header class="jumbotron subhead" id="overview">
	<div class="container">
		<h1><?php echo DiagramTag::diagram()->title; ?></h1>
		<p class="lead">好记性不如乱笔头</p>
	</div>
</header>

<div class="container">
	<div class="row">
		<div class="span12">
			<?php echo DiagramTag::position_as_ul(); ?>

			<div class="row-fluid" style="margin-top: 20px;">
				<?php foreach(PostsTag::post_list() as $post) { ?>
				<dl>
					<dt><a href="<?php echo url::base(true) . $post->link; ?>"><?php echo $post->title ?></a></dt>
					<dd><?php echo nl2br(strip_tags($post->content)) ?></dd>
				</dl>
				<p><a href="<?php echo url::base(true) . $post->link; ?>" class="btn">查看更多...</a></p>
				<?php } ?>
			</div>

			<center>
			<?php
				echo PostsTag::pagelink(array("pagination" => "bootstrap_pagination"));
			?>
			</center>
		</div>
	</div>
</div>

<?php require_once('footer.php'); ?>

<script>
	$(function(){
		$('#developmentblog').attr("class", "active");
	});	
</script>