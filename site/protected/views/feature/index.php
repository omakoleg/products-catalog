<?php
$this->breadcrumbs = array('Features');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/app/ui.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/admin/feature.js', CClientScript::POS_END);
?>


<p>
    <a class='btn btn-info' href='#' onclick="App.EM.trig('feature:add');return false;">Add Feature</a>
</p>

<table class="features-table table table-bordered">
    <thead>
        <tr> 
            <th>Name</th>
            <th>Values</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<script type="text/html" id="feature-item">
    <span class="feat-item">
        <span><b><%= item.name %></b><small class="small06">( <%= item.display_type_label %> ) </small></span>
        <span>
            <a href="#" onclick="App.EM.trig('feature:delete', <%= item.id %>);return false;"><i class="icon-remove"></i></a>
        </span>
        <span>
            <a href="#" onclick="App.EM.trig('feature:update', <%= item.id %>);return false;"><i class="icon-edit"></i></a>
        </span>
    </span>
</script>

<script type="text/html" id="feature-value-item">
    <span class="feat-value-item">
    	<% if(feature.display_type == 'color'){%>
    		<span style="background-color: <%= item.name %>;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
    	<% } else if(feature.display_type == 'backblack'){%>
    		<span class="admin-color-display-type"><%= item.name %></span>
    	<% } else {%>
    		<span><%= item.name %></span>
    	<% } %>    
        <span>
            <a href="#" onclick="App.EM.trig('feature-value:delete', <%= item.id %>);return false;"><i class="icon-remove"></i></a>
        </span>
        <span>
            <a href="#" onclick="App.EM.trig('feature-value:update', <%= item.id %>);return false;"><i class="icon-edit"></i></a>
        </span>
    </span>
</script>

<script type="text/html" id="feature-table-row">
    <tr>
    <td><%= item %></td>
    <td>
    	<a class='btn' style="float:left;" href='#' onclick="App.EM.trig('feature-value:add',{feature_id: '<%= feature.id %>'});return false;">Add value</a>
    	<%= values %>
    </td>
    </tr>
</script>