<?php
$this->breadcrumbs = array('Categories');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/app/ui.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/admin/category.js', CClientScript::POS_END);
?>

<p>
	<a class='btn btn-info' href='#' onclick="App.EM.trig('category:add');return false;">Add Category</a>
</p>

<table class="categories-table table table-bordered">
	<thead>
		<tr>
			<th>Category</th>
			<th>Subcategories</th>
		</tr>
	</thead>
	<tbody>

	</tbody>
</table>

<script type="text/html" id="category-item">
	<span class="cat-item">
	<span><%= item.name %></span>
	<% if(!item.childs_count){ %>
	<span>
		<a href="#" onclick="App.EM.trig('category:delete', <%= item.id %>);return false;"><i class="icon-remove"></i></a>
	</span>
	<% } %>
	<span>
		<a href="#" onclick="App.EM.trig('category:update', <%= item.id %>);return false;"><i class="icon-edit"></i></a>
	</span>
	</span>
</script>

<script type="text/html" id="category-table-row">
	<tr>
	<td><%= item %></td>
	<td><%= subitems %></td>
	</tr>
</script>

