<?php
$this->widget('application.extensions.dataSend.DataSendingListView', array(
    'dataProvider' => $dataProvider,
    'summaryText' => 'Show {start}-{end} of {count} | page {page} of {pages}',
    'summaryCssClass' => 'pager',
    'enableSorting' => false,
    'populateDataFunction' => 'callMe',
    'enablePagination' => true,
    'itemView' => '_view',
    'ajaxUpdate' => true,
    'template' => "{summary}\n{sorter}\n{pager}\n{items}\n{pager}",
    'emptyText' => 'There are no products in this category. Try other filter values or category',
));
?>