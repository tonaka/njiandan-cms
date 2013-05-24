<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
Template type: page
Template name: 意见建议模板
*/
require_once('header.php');
?>
<header class="jumbotron subhead" id="overview">
	<div class="container">
		<h1><?php echo DiagramTag::diagram()->title; ?></h1>
		<p class="lead">遇到问题?有意见?请您告诉我们,谢谢. 如果您在使用的时候有遇到什么问题疑惑,或者有一些改进的建议,请您告诉我们,我们会及时的与您联系一起将问题解决清楚,谢谢您的支持,您与我们在一同改进.</p>
	</div>
</header>

<div class="container">
	<div class="row">
		<div class="span12">
			<?php echo DiagramTag::position_as_ul(); ?>
			 
			<p>
			    <form action="<?php echo CommentTag::form_url(); ?>" method="post">
					<fieldset>
						<legend>发表意见建议</legend>
						<div class="control-group">
							<?php if (!UserTag::is_login()) { ?>
							<label for="username">用户名 (必填)</label>
							<input class="input-xxlarge" type="text" id="username" name="username" placeholder="用户名...">
							<span class="help-inline"><?php if (CommentTag::values('username_error')) echo CommentTag::values('username_error'); ?></span>

							<label for="email">邮箱 (不会被公布出来) (必填)</label>
							<input class="input-xxlarge" type="text" id="email" name="email" placeholder="邮箱...">
							<span class="help-inline"><?php if (CommentTag::values('email_error')) echo CommentTag::values('email_error'); ?></span>

							<label for="url">网址</label>
							<input class="input-xxlarge" type="text" id="url" name="url" placeholder="网址...">
							<span class="help-inline"><?php if (CommentTag::values('url_error')) echo CommentTag::values('url_error'); ?></span>
							<?php } else { ?>
							<?php echo '<p class="muted">' . T::_('Logged in as') . ' ' . html::admin_anchor('profile', UserTag::username()) . '.</p>'; ?>
							<?php } ?>

							<label for="content">内容 (必填)</label>
							<textarea rows="5" id="content" name="content" class="input-xxlarge"></textarea>
							<span class="help-inline"><?php if (CommentTag::values('content_error')) echo CommentTag::values('content_error'); ?></span>
						</div>

						<div class="control-group">
							<button type="submit" class="btn">提交意见建议</button>
						</div>
					</fieldset>
				</form>
			</p>
		</div>
	</div>
</div>


<?php require_once('footer.php'); ?>

<script>
  $(function(){
	  $('#feedback').attr("class", "active");
  });	
</script>