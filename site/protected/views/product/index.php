<?php
$this->breadcrumbs = array('Products');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/app/ui.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/admin/product.js', CClientScript::POS_END);
?>

<p>
	<a class='btn btn-info' href='#' onclick="App.EM.trig('product:add');return false;">Add Product</a>
</p>

<div>
	<form class="form-inline" id="products-table-filter-form">
		<select name="category_id">
			<option value="">Select category</option>
			<?php foreach($categories as $key => $name){ ?>
				<option value="<?php echo $key; ?>"><?php echo $name; ?></option>
			<?php } ?>
		</select>
		<select name="is_new">
			<option value="">Is new ?</option>
			<option value="1">New</option>
			<option value="0">Not new</option>
		</select>
	  <input type="text" name="name_like" placeholder="name here ..."/>
	  <input type="text" name="ref_like" placeholder="ref ID here ..."/>
	  <button type="button" class="btn btn-success" onclick="App.EM.trig('filter:apply');return false;">Filter</button>
	  <button type="button" class="btn" onclick="App.EM.trig('filter:reset');return false;">reset</button>
	</form>
	
	
</div>

<table class="products-table table table-bordered">
	<thead>
		<tr>
			<th>ID</th>
			<th></th>
			<th></th>
			<th>Is New</th>
			<th class="products-table-pager-placeholder"></th>
			<th>
			</th>
		</tr>
	</thead>
	<tbody>

	</tbody>
</table>

<script type="text/html" id="products-table-pager">
	<div class="pagination pagination-small" style="margin:0;">
	  <ul>
	  	<% _.each(_.range(count), function(page) { %>
	  		<li <% if(page == current){ %>  class="active" <% } %>>
	  			<a href='#' 
	  			onclick="App.EM.trig('product-table:page','<%= page %>');return false;"><%= (page + 1) %></a>
	  		</li>
		<% }); %>
	  </ul>
	</div>
</script>

<script type="text/html" id="products-table-row">
	<tr>
		<td style="width:30px;"><%= row.id %></td>
		<td style="width:70px;">
			<a href="#" onclick="App.EM.trig('attributes-modal-show',<%= row.id %>);return false;">attributes</a><br/>
			<a href="#" onclick="App.EM.trig('categories-modal-show',<%= row.id %>);return false;">categories</a><br/>
			<a href="#" onclick="App.EM.trig('images-modal-show',<%= row.id %>);return false;">images</a><br/>
		</td>
		<td style="width:200px;">
			<% if(row.productImages){ %>
				<% _.each(row.productImages, function(item) { %>
					<a href="<%= item.img_large %>" target="_blank">
						<img src="<%= item.img_thumb %>" class="product-image-thumb"/>
					</a>
				<% }); %>		
			<% } %>
		</td>
		<td style="width:50px;">
			<span class="admin-is-new-bage <% if(row.is_new == 1){ %> st-admin-is-new-bage <% } %>"
				onclick="App.EM.trig('product:is-new',{id:'<%= row.id %>', is_new:'<%= row.is_new == '1'? 1: 0 %>' });return false;"
			> new </span>
		</td>
		<td>
			<div class="product-item-line">
				<b>Name:</b> <%= row.name %>
			</div>
			<div class="product-item-line">
				<b>Ref ID:</b> <%= row.ref %>
			</div>
			<div class="product-item-line">
				<b>Price:</b> <%= row.price %>
			</div>
			<div class="product-item-line">
				<%= row.description %>
			</div>
		</td>
		<td style="width: 100px;">
			<a class='btn btn-info' href='#' onclick="App.EM.trig('product:edit',<%= row.id %>);return false;">
				<i class="icon-edit icon-white"/>
			</a>
			<a class='btn btn-danger' href='#' onclick="App.EM.trig('product:delete',<%= row.id %>);return false;">
				<i class="icon-remove icon-white"/>
			</a>
		</td>
	</tr>
</script>

<script type="text/javascript">
	$(document).ready(function() {
		var table = App.Ui.Table.create({
			selector : '.products-table',
			row : '#products-table-row',
			pager: {
				template: '#products-table-pager',
				holder: '.products-table-pager-placeholder',
				page: 0,
				per_page: 50,
			},
			url : '/product/table',
			events : {
				'product:added' : function() {
					table.reload();
				},
				'product:updated' : function() {
					table.reload();
				},
				'product:deleted' : function() {
					table.reload();
				},
				'product-table:page': function(n){
					table.loadPage(n);
				},
				'product-table:reload': function(){
					console.log('reload');
					table.reload();
				}
			}
		});
		App.EM.bind('filter:apply', function(){
			var form = $('#products-table-filter-form');
			var data = {
				category_id: form.find('[name=category_id]').val(),
				is_new: form.find('[name=is_new]').val(),
				name_like: form.find('[name=name_like]').val(),
				ref_like: form.find('[name=ref_like]').val(),
			}
			table.setFilter(data);
			table.reload();
		});
		App.EM.bind('filter:reset', function(){
			var form = $('#products-table-filter-form');
			form && form[0] && form[0].reset();
			table.setFilter({});
			table.reload();
		});
	});

</script>