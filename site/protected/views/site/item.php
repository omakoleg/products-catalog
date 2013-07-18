<?php
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerScriptFile('/js/item.js');
$cs->registerScriptFile('/js/fancybox/jquery.mousewheel-3.0.4.pack.js');
$cs->registerScriptFile('/js/fancybox/jquery.fancybox-1.3.4.pack.js');

$this->pageTitle = Yii::app()->name . ' - item page';
if($product){
?>


<link rel="stylesheet" type="text/css" href="/js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<div id="content_container">
    <div id="product_pager"></div>
    <div id="product_photos_container">
        <?php if(isset($product->productImages)){ ?>
            <?php foreach ($product->productImages as $value) {?>
                <a rel="group1" href="<?php echo $value->recipeImgBehavior->getFileUrl('large'); ?>" title="<?php echo $value->name; ?>"
                    ><img class="selected" src="<?php echo $value->recipeImgBehavior->getFileUrl('preview'); ?>"/></a>
            <?php } ?>
        <div id="photos_switcher">
            <?php
            $i  = 0; 
            foreach ($product->productImages as $value) {?>
            <img src="<?php echo $value->recipeImgBehavior->getFileUrl('thumb'); ?>"
            title="<?php echo $value->name; ?>" ind="<?php echo $i++; ?>"/>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
    <div id="product_info_container">
        <?php if($product->is_new){?>
        <div class="product_new">
            new
        </div>
        <?php } ?>
        <div class="product_name">
            <?php echo CHtml::encode($product->name); ?>
        </div>
        <div class="product_ref">
            Ref. <?php echo CHtml::encode($product->ref); ?>
        </div>
        <div class="product_description">
            <?php echo CHtml::encode($product->description); ?>
        </div>
        <?php  foreach ($product->getFeaturesHierarchy() as $f) { ?>
            
            <?php if($f['display_type'] == 'regular'){ ?>
                <div class="product_composition">
                    <?php echo $f['name']; ?> :
                    <?php  foreach ($f['values'] as $v) { ?>
                         <div class="product_composition_item">
                            <span class="composition_name"><?php echo $v; ?></span>
                            <span class="composition_desc"></span>
                        </div>
                    <?php } ?>
                </div><br/>
            <?php } ?>
            
            <?php if($f['display_type'] == 'color'){ ?>
                <div class="product_colours">
                    <?php echo $f['name']; ?> :
                    <div>
                    <?php  foreach ($f['values'] as $v) { ?>
                        <span style="background-color: <?php echo $v; ?>;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <?php } ?>
                    </div>
                </div><br/>
            <?php } ?>
            
            <?php if($f['display_type'] == 'backblack'){ ?>
                 <div class="product_sizes">
                     <?php echo $f['name']; ?> :
                     <div>
                     <ul>
                        <?php  foreach ($f['values'] as $v) { ?>
                            <li><?php echo $v; ?></li>
                        <?php } ?>
                     </ul>
                     </div>
                 </div><br/><br/>
            <?php } ?>
        <?php } ?>
    </div>
    <div id="footer_compensation"></div>
</div>
<?php }else{ ?>
Product not found
<?php } ?>