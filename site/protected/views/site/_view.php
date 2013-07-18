<div class="item">
    <a href="/p/<?php echo $this->selectedCategory->slug; ?>/<?php echo $data->id; ?>"> <img src="<?php
    echo ( $data->productImages && count($data->productImages)) ? $data->productImages[0]->getFileUrl('normal') :'/img/site/no-image.png';
    ?>"/>
    <div>
        <span class="<?php if($data->is_new){?>item_is_new<?php } else{ ?>not_item_is_new<?php } ?>">new</span> 
        <span class="item_name"><?php echo CHtml::encode($data->name); ?></span>
        <span class="item_price">$<?php echo number_format($data->price, 2, ',', ' '); ?></span>
    </div> </a>
</div>
