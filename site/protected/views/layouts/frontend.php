<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <header>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/site.css" media="all" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/item.css" media="all" />        
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/site.js"></script>
    </header>
    <body>
        <div id="header_container">
            <?php //echo Yii::app()->name; ?>
        </div>

        <?php echo $content; ?>
        
        <div id="footer_container">
            <span id="footer_login">
            <?php if(Yii::app()->user->isGuest){ ?>
                <?php echo CHtml::link('login',array('site/login')); ?>
            <?php }else{ ?>
                <?php echo CHtml::link('admin',array('/category')); ?>    
            <?php } ?>
            </span>
            <span id="copyright">Copyright &copy; <?php echo date('Y'); ?> Maki . All Rights Reserved.</span>
        </div>
    </body>
</html>