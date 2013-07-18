(function($) {'use strict';

	function Product() {
		var local = {
			features : {},
			images : {},
			selected : {},
			template : null,
			product_image_template : null,
			product_image_add_index : 0
		}

		function deleteProduct(product_id) {
			if (confirm("Remove product with images ?")) {
				$.post('/product/delete', {
					id : product_id
				}).done(function() {
					App.EM.trig('product:deleted');
				});
			}
		}

		function showImagesList(product_id) {
			var modal = new App.EditModalWindow({
				title : 'Manage images',
				labels : {
					close : 'Close',
					save : 'Save'
				},
				data : {
					product_id : product_id
				},
				url : '/product/images',
				onLoaded : function(r, contentBox) {
					local.images = r.product_images || {};
					local.product_image_template = _.template($('#images-table-item-template').html());
					renderImagesTable();
					addDropBehaviour();
				},
				onSave : function(modal, contentBox, chain) {
					App.FormDataSend.send({
						url : '/product/imagesSave',
						form : contentBox.find('form'),
						success : function(responce) {
							chain();
						}
					});
				}
			});
		}

		function addDropBehaviour() {
			App.DropArea.disableBody();
			App.DropArea.enableDrop('.files-drop-area', {
				dragenter : function(e) {
					$(e.target).css('border', '1px solid #3F3F3F');
				},
				dragleave : function(e) {
					$(e.target).css('border', '0px');
				},
				drop : function(e) {
					e.preventDefault();

					var dt = e.originalEvent.dataTransfer;
					if (!dt && !dt.files)
						return;

					var root = $(e.target);
					var files = dt.files;
					var cntFinished = 0;
					var product_id = root.data('product_id');

					var imgEl = root.find('img');
					var progressEl = root.prev('.files-drop-area-message').find('.progress');
					var messageEl = root.prev('.files-drop-area-message').find('.message');
					var errorsEl = root.prev('.files-drop-area-message').find('.errors');

					var finishCallback = function(current, cnt) {
						if (current >= cnt) {
							root.css('border', '0px');
							messageEl.show();
							imgEl.hide();
							progressEl.hide();
						} else {
							messageEl.hide();
							imgEl.show();
							progressEl.show();
							progressEl.html('Uploaded: ' + current + ' / ' + cnt + ' images.');
						}
					}
					errorsEl.empty();
					for (var i = 0; i < files.length; i++) {
						finishCallback(cntFinished, files.length);
						if (files[i].size > 2097152) {
							cntFinished++;
							finishCallback(cntFinished, files.length);
							errorsEl.append('<p>File too big: ' + files[i].name + '</p>');
							continue;
						}
						App.FormDataSend.send({
							url : '/product/FileUpload',
							data : {
								'ProductImage[product_id]' : product_id,
								'ProductImage[filename]' : files[i]
							},
							error : function(e) {
								cntFinished++;
								finishCallback(cntFinished, files.length);
								errorsEl.append('Error uploading file: <small>' + e.responseText + '</small>');
							},
							success : function(responce) {
								cntFinished++;
								finishCallback(cntFinished, files.length);
								var rObjs = jQuery.parseJSON(responce);

								if (rObjs.error == false) {
									local.images.push(rObjs);
									renderImagesTable();
								} else if (rObjs.error == true) {
									errorsEl.append(App.Validation.modelValidationToMessage(rObjs));
								} else {
									errorsEl.append(responce);
								}
							}
						});
					}

					return false;
				}
			});
		}

		function renderImagesTable() {
			if (local.images) {
				$('.images-table').empty();
				$.each(local.images, function(k, v) {
					$('.images-table').append(local.product_image_template({
						image : v
					}));
				})
			}
		}

		function showCategoriesList(product_id) {
			var modal = new App.EditModalWindow({
				title : 'Assign categories',
				labels : {
					close : 'Cancel',
					save : 'Save Changes'
				},
				data : {
					product_id : product_id
				},
				url : '/product/assign',
				onLoad : function(responce, contentBox) {

				},
				onSave : function(modal, contentBox, chain) {
					var form = contentBox.find('form');
					var data = form.serialize();
					$.post('/product/assignSave', data).done(function() {
						chain();
					});
				}
			});
		}

		function editProductDialog(id) {
			var modal = new App.EditModalWindow({
				title : 'Update product',
				labels : {
					close : 'Cancel',
					save : 'Save Changes'
				},
				data : {
					id : id
				},
				url : '/product/update',
				onLoad : function(responce, contentBox) {

				},
				onSave : function(modal, contentBox, chain) {
					var form = contentBox.find('form');
					var data = form.serialize();
					$.post('/product/update', data).done(function(responce) {
						if (responce && responce.content) {
							contentBox.html(responce.content);
						} else {
							App.EM.trig('product:updated');
							chain();
							//$.fn.yiiGridView.update('crud-grid');
						}
					}, 'json');
				}
			});
		}

		function addProductDialog() {
			var modal = new App.EditModalWindow({
				title : 'Add product',
				labels : {
					close : 'Cancel',
					save : 'Save Changes'
				},
				data : { },
				url : '/product/create',
				onLoad : function(responce, contentBox) {

				},
				onSave : function(modal, contentBox, chain) {
					var form = contentBox.find('form');
					var data = form.serialize();
					$.post('/product/create', data).done(function(responce) {
						if (responce && responce.content) {
							contentBox.html(responce.content);
						} else {
							App.EM.trig('product:added');
							chain();
							//$.fn.yiiGridView.update('crud-grid');
						}
					}, 'json');
				}
			});
		}

		function showAttributesList(product_id) {
			var modal = new App.EditModalWindow({
				title : 'Assign attributes',
				labels : {
					close : 'Close',
					save : 'Save Changes'
				},
				width : '1000',
				data : {
					product_id : product_id
				},
				url : '/product/attributes',
				onLoaded : function(r, contentBox) {
					local.features = r.featuresall || {};
					local.selected = r.features || {};
					local.template = _.template($('#attributes-table-item-template').html());
					renderTable();
				},
				onSave : function(modal, contentBox, chain) {
					var form = contentBox.find('form');
					var data = form.serialize();
					$.post('/product/attributesSave', data).done(function(responce) {
						chain();
					}, 'json');
				}
			});
		}

		function removeSelection(f_id, fv_id) {
			if (fv_id) {
				local.features[f_id]['featureValues'][fv_id].disabled = false;
			} else {
				$.each(local.features[f_id]['featureValues'], function(k, v) {
					v['disabled'] = false;
				});
			}
			local.features[f_id]['disabled'] = false;

			if (fv_id) {
				delete local.selected[f_id]['featureValues'][fv_id];
				if (!App.Helper.countItems(local.selected[f_id]['featureValues'])) {
					delete local.selected[f_id];
				}
			} else {
				delete local.selected[f_id];
			}
		}

		function setSelectedFeatures() {
			if (local.selected && _.size(local.selected) != 0) {
				_.each(local.selected, function(v, k) {
					var fvLength = _.size(local.features[k]['featureValues']);
					var cnt = 0;
					v['featureValues'] && $.each(v['featureValues'], function(kv, vv) {
						local.features[k]['featureValues'][kv]['disabled'] = true;
						cnt++;
					});
					if (fvLength == cnt) {
						local.features[k]['disabled'] = true;
					}
				});
			}
		}

		function renderFeatures() {
			var selected = $('.features-list-box option:selected');
			var selected_id = (selected.length && selected.attr('value')) || undefined;
			var opts = [];
			$.each(local.features, function(k, v) {
				if (!v.disabled) {
					opts.push('<option value="' + k + '"' + (k == selected_id ? " selected='selected'" : '') + '>' + v.name + '</option>');
				}
			});
			$('.features-list-box').empty().html(opts.join(''));
			selected_id && renderFeatureValues(selected_id);
		}

		function renderFeatureValues(feature_id) {
			var feature_vals = [];
			if (feature_id && local.features[feature_id]) {
				feature_vals = local.features[feature_id].featureValues;
			}
			var opts = [];
			_.each(feature_vals, function(v, k) {
				if (!v.disabled) {
					opts.push('<option value="' + k + '">' + v.name + '</option>');
				}
			});
			$('.feature-values-list-box').empty().html(opts.join(''));
		}

		function renderTable() {
			setSelectedFeatures();
			renderFeatures();
			if (local.selected) {
				$('.attributes-table').empty();
				_.each(local.selected, function(v,k) {
					$('.attributes-table').append(local.template({
						feature : v
					}));
				})
			}
		}

		function attributesModalSelectFeature() {
			var item = $('.features-list-box option:selected');
			renderFeatureValues(item.attr('value'));
		}

		function attributesModalSelectFeatureValue(listBox) {
			var feature_item = $('.features-list-box option:selected'), feature_value_item = $('.feature-values-list-box option:selected');

			if (!feature_item.length || !feature_value_item.length) {
				return;
			}

			var feature = local.features[feature_item.attr('value')], feature_value = local.features[feature.id].featureValues[feature_value_item.attr('value')];

			var key = feature.id.toString();
			if (local.selected == undefined) {
				local.selected = {};
			}
			if (local.selected[key] == undefined) {
				var newFeature = {
					id : feature.id,
					name : feature.name,
					featureValues : {}
				};
				local.selected[key] = newFeature;
			}
			local.selected[key]['featureValues'][feature_value.id.toString()] = {
				id : feature_value.id,
				name : feature_value.name
			};
			renderTable();
		}

		function onAttributeDelete(params) {
			removeSelection(params.f_id, params.fv_id);
			renderTable();
		}

		function onRemoveImage(params) {
			var id = params.id;
			local.images = _.reject(local.images, function(el) {
				return el.id == params.id;
			});
			$(params.elem).remove();
		}

		function isNewProduct(param) {
			$.post('/product/update', {
				'Product[id]' : param.id,
				'Product[is_new]' : (param.is_new == '1')? '0': '1'
			}, function(responce) {
				console.log('saved');
				App.EM.trig('product-table:reload');
			}, 'json');
		}

		return {
			init : function() {
				App.EM.bind('categories-modal-show', showCategoriesList);
				App.EM.bind('attributes-modal-show', showAttributesList);
				App.EM.bind('images-modal-show', showImagesList);
				App.EM.bind('product:add', addProductDialog);
				App.EM.bind('product:edit', editProductDialog);
				App.EM.bind('product:delete', deleteProduct);
				App.EM.bind('product:is-new', isNewProduct);
				App.EM.bind('attributes-modal:select-feature', attributesModalSelectFeature);
				App.EM.bind('attributes-modal:select-feature-value', attributesModalSelectFeatureValue);
				App.EM.bind('attributes-modal:attribute-delete', onAttributeDelete);

				App.EM.bind('images:delete', onRemoveImage);
			}
		}
	}


	App.Product = new Product();
	App.Product.init();

})(jQuery);
