(function($) {'use strict';

	function Category() {

		var _row = _.template($('#category-table-row').html());
		var _item = _.template($('#category-item').html());
		var root = $('.categories-table');
		var tbody = root.find('tbody');

		var list = {};

		function onCategoryDelete(id) {
			if (confirm("Remove category ?")) {
				$.post('/category/delete', {
					id : id
				}).done(function(responce) {
					if (responce && responce.error) {
						alert(responce.message? responce.message: "Can't delete error occured");
					} else {
						App.EM.trig('category:deleted');
					}
				});
			}
		}

		function onCategoryUpdate(id) {
			var modal = new App.EditModalWindow({
				title : 'Edit category',
				labels : {
					close : 'Cancel',
					save : 'Save Changes'
				},
				data : {
					id : id
				},
				url : '/category/update',
				onLoad : function(responce, contentBox) {

				},
				onSave : function(modal, contentBox, chain) {
					var form = contentBox.find('form');
					var data = form.serialize();
					$.post('/category/update', data).done(function(responce) {
						if (responce && responce.content) {
							contentBox.html(responce.content);
						} else {
							App.EM.trig('category:updated');
							chain();
						}
					}, 'json');
				}
			});
		}

		function onCategoryAdd() {
			var modal = new App.EditModalWindow({
				title : 'Add category',
				labels : {
					close : 'Cancel',
					save : 'Save Changes'
				},
				data : { },
				url : '/category/create',
				onLoad : function(responce, contentBox) {

				},
				onSave : function(modal, contentBox, chain) {
					var form = contentBox.find('form');
					var data = form.serialize();
					$.post('/category/create', data).done(function(responce) {
						if (responce && responce.content) {
							contentBox.html(responce.content);
						} else {
							App.EM.trig('category:added');
							chain();
						}
					}, 'json');
				}
			});
		}

		function loadCategories(funcCallback) {
			$.get('/category/table', {}, function(responce) {
				if (!responce) {
					return;
				}
				list = responce.data;
				funcCallback();
			}, 'json');
		}

		function renderTable() {
			tbody.empty();
			var listLocal = {};
			_.each(list, function(v, k) {
				if (!v.parent_id) {
					listLocal[v.id.toString()] = v;
					listLocal[v.id.toString()]['subcategories'] = {};
				} else {
					listLocal[v.parent_id.toString()]['subcategories'][v.id.toString()] = v;
				}
			});
			_.each(listLocal, function(v, k) {
				var item = _item({
					item : v
				});
				var subcategories = [];
				if (_.size(v['subcategories'])) {
					_.each(v['subcategories'], function(v2, k2) {
						subcategories.push(_item({
							item : v2
						}));
					});
				}
				tbody.append(_row({
					item : item,
					subitems : subcategories.join('')
				}));

			});
		}

		return {
			init : function() {
				App.EM.bind('category:add', onCategoryAdd);
				App.EM.bind('category:update', onCategoryUpdate);
				App.EM.bind('category:delete', onCategoryDelete);

				var functionHardReset = function() {
					loadCategories(function() {
						renderTable();
					});
				};

				App.EM.bind('category:updated', functionHardReset);
				App.EM.bind('category:added', functionHardReset);
				App.EM.bind('category:deleted', functionHardReset);
				functionHardReset();
			}
		}
	}


	App.Category = new Category();
	App.Category.init();

})(jQuery);
