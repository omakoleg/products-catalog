<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<?php
	Yii::app()->clientScript->registerCoreScript('jquery');
	Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/css/bootstrap.min.css", '');
	Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/css/admin.css", '');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/underscore.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap.min.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/app/app.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/app/em.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/admin.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery-ui-1.9.2.custom.min.js');
	?>
	
</head>

<body>
	<div id="yw0" class="navbar navbar-inverse">
		<div class="navbar-inner navbar-fixed-top">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<div class="nav-collapse">
					<ul class="nav">
						<li>
							<a href="/category"><i class="icon-home icon-white"></i> <?php echo EBootstrap::encode(Yii::app()->name); ?> admin panel</a>
						</li>
						<li class="divider-vertical"></li>
						<li <?php if(Yii::app()->controller->id == 'category'){?>  class="active" <?php } ?>><a href="/category/index">Categories</a></li>
						<li <?php if(Yii::app()->controller->id == 'product'){?>  class="active" <?php } ?>><a href="/product/index">Products</a></li>
						<li <?php if(Yii::app()->controller->id == 'feature'){?>  class="active" <?php } ?>><a href="/feature/index">Features</a></li>
						<li class="divider-vertical"></li>
					</ul>
					<ul class="nav pull-right">
						<li>
							<?php if(Yii::app()->user->isGuest){ ?>
								<a href="/site/login">Login</a>
							<?php }else{ ?>
								<a href="/site/logout">Logout (<?php echo Yii::app()->user->name; ?>)</a>
							<?php } ?>
						</li>
					</ul>
				</div>
				</div>
		</div>
	</div>
	
<div style="margin: 0 20px 0 20px; padding-top: 40px;">
    <?php if(isset($this->breadcrumbs)): ?>
        <?php $this->widget('EBootstrapBreadcrumbs', array(
			'links' => $this->breadcrumbs,
			'homeLink' => 'Manage'
		));
     ?>
    <?php endif ?>

	<?php echo $content; ?>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> Maki . All Rights Reserved.<br/>
	</div><!-- footer -->


</div>
</body>
</html>