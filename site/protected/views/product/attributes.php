<table style="width:100%;">
	<tr>
		<td style="width:230px; vertical-align: top;">
			<select onclick="App.EM.trig('attributes-modal:select-feature');return false;"
			size="12"
			class="features-list-box">
			</select>
		</td>
		<td style="width:230px; vertical-align: top;">
			<select ondblclick="App.EM.trig('attributes-modal:select-feature-value');return false;"
			size="12"
			class="feature-values-list-box">
			</select>
		</td>
		<td style="vertical-align: top;">
			<!-- template -->
			<script type="text/html" id="attributes-table-item-template">
				<tr>
					<td style="width:150px;"><%= feature.name %></td>
					<td>
						<input type="hidden" name="feature_id[]" value="<%= feature.id %>"/>
						<% _.each(feature.featureValues, function(item) { %>
							<span class="label label-success">
								<%= item.name %>
								<input type="hidden" name="feature_value_id[<%= feature.id %>][]" value="<%= item.id %>"/>
								<a href="#" onclick="App.EM.trig('attributes-modal:attribute-delete',{f_id: <%= feature.id %>,fv_id: <%= item.id %>});return false;">
									<i class="icon-remove"></i>
								</a>
							</span>&nbsp;
						<% }); %>
					</td>
					<td style="width:20px;">
						<a href="#" onclick="App.EM.trig('attributes-modal:attribute-delete',{f_id: <%= feature.id %>});return false;">
							<i class="icon-remove"></i>
						</a>
					</td>
				</tr>
			</script>
			<!-- template END -->
			
			<form>
				<table class="table table-bordered attributes-table">
					
				</table>
				<?php echo CHtml::hiddenField('product_id', $product_id); ?>
			</form>
		</td>
	</tr>
</table>
