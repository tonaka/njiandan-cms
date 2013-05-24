<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
Template type: page
Template name: 页面模板
*/
require_once('header.php');
?>

<header class="jumbotron subhead" id="overview">
	<div class="container">
		<h1><?php echo PageTag::title(); ?></h1>		
		<p class="lead"><?php echo PageTag::content(); ?></p>
	</div>
</header>

<div class="container">
	<div class="row">
		<div class="span3 bs-docs-sidebar">
			<ul class="nav nav-list bs-docs-sidenav">
				<?php $submenuList = DiagramTag::submenu(PageTag::uri()); ?>
				<?php foreach ($submenuList as $submenu) { ?> 
				<li><a href="#<?php echo $submenu->uri; ?>"><i class="icon-chevron-right"></i><?php echo $submenu->title; ?></a></li>
				<?php } ?>
			</ul>
		</div>

		<div class="span9">
			<?php echo DiagramTag::position_as_ul(); ?>

			<?php foreach ($submenuList as $submenu) { ?> 
			<section id="<?php echo $submenu->uri; ?>">
				<div class="page-header">
					<h1><?php echo $submenu->title; ?></h1>
				</div>
				<blockquote><?php echo $submenu->content; ?></blockquote>
			</section>
			<?php } ?>
		</div>
	</div>
</div>

<?php require_once('footer.php'); ?>


<script>
  $(function(){
		$('#<?php echo PageTag::uri(); ?>').attr("class", "active");

		//SyntaxHighlighter.all();
  });	
</script>