function confirm_assign_delete(elem, product_id, category_id) {
    if (confirm('Are you sure you want to delete this item?')) {
        $.post('/product/assignDelete', {
            product_id : product_id,
            category_id : category_id
        }, function(r) {
            if(r == '1'){
                $(elem).closest('div').remove();
            }else{
                alert(r);
            }
        });
    }
    return false;
}
