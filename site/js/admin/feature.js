(function($) {'use strict';

	function Feature() {

		var _row = _.template($('#feature-table-row').html());
		var _item = _.template($('#feature-item').html());
		var _item_value = _.template($('#feature-value-item').html());
		var root = $('.features-table');
		var tbody = root.find('tbody');

		var list = {};

		function onFeatureDelete(id) {
			if (confirm("Remove feature ?")) {
				$.post('/feature/delete', {
					id : id
				}).done(function(responce) {
					if (responce && responce.error) {
						alert(responce.message? responce.message: "Can't delete error occured");
					} else {
						App.EM.trig('feature:deleted');
					}
				});
			}
		}
		function onFeatureValueDelete(id) {
			if (confirm("Remove feature value?")) {
				$.post('/feature/deleteValue', {
					id : id
				}).done(function(responce) {
					if (responce && responce.error) {
						alert(responce.message? responce.message: "Can't delete error occured");
					} else {
						App.EM.trig('feature-value:deleted');
					}
				});
			}
		}

		function loadFeatures(funcCallback) {
			$.get('/feature/table', {}, function(responce) {
				if (!responce) {
					return;
				}
				list = responce.data;
				funcCallback();
			}, 'json');
		}

		function renderTable() {
			tbody.empty();
			_.each(list, function(v, k) {
				var item = _item({
					item : v
				});
				var values = [];
				if (_.size(v['featureValues'])) {
					_.each(v['featureValues'], function(v2, k2) {
						values.push(_item_value({
							feature: v,
							item : v2
						}));
					});
				}
				tbody.append(_row({
					item : item,
					values : values.join(''),
					feature: v
				}));

			});
		}
		function onFeatureValueAdd(param){
			var modal = new App.EditModalWindow({
				title : 'Add feature value',
				labels : {
					close : 'Cancel',
					save : 'Save Changes'
				},
				data : {
					feature_id: param.feature_id 
				},
				url : '/feature/createValue',
				onLoad : function(responce, contentBox) {

				},
				onSave : function(modal, contentBox, chain) {
					var form = contentBox.find('form');
					var data = form.serialize();
					$.post('/feature/createValue', data).done(function(responce) {
						if (responce && responce.content) {
							contentBox.html(responce.content);
						} else {
							App.EM.trig('feature-value:added');
							chain();
						}
					}, 'json');
				}
			});
		}

		function onFeatureAdd() {
			var modal = new App.EditModalWindow({
				title : 'Add feature',
				labels : {
					close : 'Cancel',
					save : 'Save Changes'
				},
				data : { },
				url : '/feature/create',
				onLoad : function(responce, contentBox) {

				},
				onSave : function(modal, contentBox, chain) {
					var form = contentBox.find('form');
					var data = form.serialize();
					$.post('/feature/create', data).done(function(responce) {
						if (responce && responce.content) {
							contentBox.html(responce.content);
						} else {
							App.EM.trig('feature:added');
							chain();
						}
					}, 'json');
				}
			});
		}
		function onFeatureValueUpdate(id) {
			var modal = new App.EditModalWindow({
				title : 'Edit feature value',
				labels : {
					close : 'Cancel',
					save : 'Save Changes'
				},
				data : {
					id : id
				},
				url : '/feature/updateValue',
				onLoad : function(responce, contentBox) {
					
				},
				onSave : function(modal, contentBox, chain) {
					var form = contentBox.find('form');
					var data = form.serialize();
					$.post('/feature/updateValue', data).done(function(responce) {
						if (responce && responce.content) {
							contentBox.html(responce.content);
						} else {
							App.EM.trig('feature-value:updated');
							chain();
						}
					}, 'json');
				}
			});
		}

		function onFeatureUpdate(id) {
			var modal = new App.EditModalWindow({
				title : 'Edit feature',
				labels : {
					close : 'Cancel',
					save : 'Save Changes'
				},
				data : {
					id : id
				},
				url : '/feature/update',
				onLoad : function(responce, contentBox) {

				},
				onSave : function(modal, contentBox, chain) {
					var form = contentBox.find('form');
					var data = form.serialize();
					$.post('/feature/update', data).done(function(responce) {
						if (responce && responce.content) {
							contentBox.html(responce.content);
						} else {
							App.EM.trig('feature:updated');
							chain();
						}
					}, 'json');
				}
			});
		}

		return {
			init : function() {
				 App.EM.bind('feature:add', onFeatureAdd);
				 App.EM.bind('feature-value:add', onFeatureValueAdd);
				 App.EM.bind('feature:update', onFeatureUpdate);
				 App.EM.bind('feature-value:update', onFeatureValueUpdate);
				 App.EM.bind('feature:delete', onFeatureDelete);
				 App.EM.bind('feature-value:delete', onFeatureValueDelete);

				var functionHardReset = function() {
					loadFeatures(function() {
						renderTable();
					});
				};

				 App.EM.bind('feature:updated', functionHardReset);
				 App.EM.bind('feature:added', functionHardReset);
				 App.EM.bind('feature:deleted', functionHardReset);
				 App.EM.bind('feature-value:deleted', functionHardReset);
				 App.EM.bind('feature-value:added', functionHardReset);
				 App.EM.bind('feature-value:updated', functionHardReset);
				functionHardReset();
			}
		}
	}


	App.Feature = new Feature();
	App.Feature.init();

})(jQuery);