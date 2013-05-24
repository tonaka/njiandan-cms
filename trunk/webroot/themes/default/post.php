<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
Template type: post
Template name: 单个日志页面模板
*/
require_once('header.php');
?>

<header class="jumbotron subhead" id="overview">
	<div class="container">
		<h1><?php echo PostTag::title(); ?></h1>
	</div>
</header>

<div class="container">
	<div class="row">
		<div class="span12">
			<?php echo DiagramTag::position_as_ul(); ?>
			 
			<p>
			<?php echo PostTag::content(); ?>
			</p>
		</div>
	</div>
</div>
<?php require_once('footer.php'); ?>
