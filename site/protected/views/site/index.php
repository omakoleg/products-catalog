<?php
$this->pageTitle = Yii::app()->name . ' - Main page';
?>

<ul id="left_menu">
    <?php if($categories) 
        foreach ($categories as $category) {?>
        <li class="<?php echo in_array($category->id, $selected) ? 'sel' : ''; ?>">
        <span><a href="/c/<?php echo $category->slug; ?>"><?php echo strtoupper(CHtml::encode($category->name)); ?></a></span>
        <?php if($category->childs && in_array($category->id, $selected)){?>
            <ul>
            <?php foreach ($category->childs as $child) { ?>
                <li class="<?php echo in_array($child->id, $selected) ? 'sel' : ''; ?>">
                    <a href="/c/<?php echo $child->slug; ?>" title="<?php echo CHtml::encode($child->name); ?>"
                        ><?php echo strtoupper(CHtml::encode($child->name)); ?></a>
                </li>
            <?php } ?>
            </ul>
        <?php } ?>            
    </li>
    <?php } ?>
</ul>
<div id="page_container">
    <div id="filter">
        <?php 
        foreach($filter as $f){ ?>
         <div class="filter_type">
            <a href="#"><?php echo $f['name']; ?></a>
            <ul>
                <?php foreach($f['values'] as $kfv => $fv){ ?>
                <li>
                    <label>
                        <input type="checkbox" checked="checked" value="<?php echo $kfv; ?>"/>
                        <?php echo $fv['name']; ?>
                    </label>
                </li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
    </div>
    <div id="page">
        <?php $this->renderPartial('_list', array('dataProvider' => $dataProvider), false, true); ?>
    </div>
    <div id="footer_compensation"></div>
</div>