<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<title><?php echo NjiandanTag::smart_title(); ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="N简单网页设计工作室(个人性质), 致力于为企业定制开发符合自己风格的企业网站,企业软件, 花最少的钱得最优的服务. 为您和您的企业提供专业的网站建设,网页设计,软件开发等相关网络服务.">
		<meta name="author" content="njiandan.com hpze2000@qq.com">
		<meta name="keywords" content="N简单网页设计工作室(个人性质), 福州网页设计, 福州网站开发, 福州网站建设, 福州个人网页设计工作室, 福州软件开发">
		<meta name="robots" content="index,follow">
		<meta name="application-name" content="njiandan.com">

		<link href="<?php echo url::themesPath() ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo url::themesPath() ?>/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
		<link href="<?php echo url::themesPath() ?>/css/docs.css" rel="stylesheet">


		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="<?php echo url::themesPath() ?>/bootstrap/html5shiv.js"></script>
		<![endif]-->

		<link rel="shortcut icon" href="<?php echo url::themesPath() ?>/favicon.png">
	</head>

	<body data-spy="scroll" data-target=".bs-docs-sidebar">
    
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="brand" href="<?php echo url::base(true); ?>">N简单CMS</a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<?php foreach (DiagramTag::mainmenu() as $diagram_list) {?>
						<li id="<?php echo $diagram_list->uri; ?>"><a href="<?php echo url::base(true) . $diagram_list->uri; ?>"><?php echo $diagram_list->title; ?></a></li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>