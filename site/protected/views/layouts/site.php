<?php $this->beginContent('/layouts/frontend'); ?>
<div id="menu_container">
    <ul id="menu">
        <?php if($this->categories) 
        foreach ($this->categories as $category) {?>
        <li class="<?php echo in_array($category->id, $this->selected) ? 'sel' : ''; ?>">
        <span><a href="/c/<?php echo $category->slug; ?>"><?php echo strtoupper(CHtml::encode($category->name)); ?></a></span>
        <?php if($category->childs){?>
            <ul>
            <?php foreach ($category->childs as $child) { ?>
                <li class="<?php echo in_array($child->id, $this->selected) ? 'sel' : ''; ?>">
                    <a href="/c/<?php echo $child->slug; ?>" title="<?php echo CHtml::encode($child->name); ?>"
                        ><?php echo strtoupper(CHtml::encode($child->name)); ?></a>
                </li>
            <?php } ?>
            </ul>
        <?php } ?>            
    </li>
    <?php } ?>
    </ul>
</div>
<div id="content_container">
    <div id='breadcrumbs'>
        <?php
        $aList = array();
        $aData = array_reverse($this->breadcrumbs);
        $iCount = count($aData);
        if ($this->breadcrumbs)
            for ($i = 0; $i < $iCount; $i++) {
                if ($i != $iCount - 1) {
                    $aList[CHtml::encode($aData[$i]->name)] = array('/c/' . CHtml::encode($aData[$i]->slug));
                } else {
                    $aList[] = CHtml::encode($aData[$i]->name);
                }
            }
        $this->widget('zii.widgets.CBreadcrumbs', array(
            'links' => $aList,
            'separator' => '&gt;'
        ));
    ?>
    </div>
    <?php echo $content; ?>
</div>
<?php $this->endContent(); ?>