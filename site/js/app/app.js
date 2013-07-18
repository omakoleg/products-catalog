var App = {};
App.Helper = {};
App.Validation = {};

App.Helper.countItems = function(obj) {
	var count = 0;
	for (var prop in obj) {
		if (obj.hasOwnProperty(prop))
			++count;
	}
	return count;
}

App.Validation.modelValidationToMessage = function(obj) {
	var res = [];
	if (obj.error) {
		$.each(obj.errors, function(k, v) {
			$.each(v, function(k1, v1) {
				res.push('<p>' + v1 + '</p>');
			});
		});
	}
	return res.join('');
}

App.inherit = (function() {
	function F() {
	}

	return function(child, parent) {
		F.prototype = parent.prototype;
		child.prototype = new F;
		child.prototype.constructor = child;
		child.superproto = parent.prototype;
		return child;
	};
})();

(function($) {'use strict';
	/*
	 * SHOW window
	 */
	function ModalWindow(options) {
		var self = this;
		this._labels = {
			close : (options.labels && options.labels.close) || 'Close'
		};
		this._title = options.title || 'Modal Window';
		this._ajaxData = options.data || {};
		this._url = options.url;
		this._onLoad = options.onLoad;
		this._onLoaded = options.onLoaded;
		this._onClose = options._onClose;
		this._onClosed = options._onClosed;
		this._width = options.width;

		this._id = 'modal-window-' + Math.floor(Math.random() * 1234567).toString(16);

		var html = ['<div class="modal hide fade" tabindex="-1" role="dialog" id="' + this._id + '">'];
		html.push('<div class="modal-header">');
		html.push('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>');
		html.push('<h3>' + this._title + '</h3>');
		html.push('</div>');
		html.push('<div class="modal-body">');
		html.push('</div>');
		html.push('<div class="modal-footer">');
		html.push('<button class="btn" data-dismiss="modal">' + this._labels.close + '</button>');
		html.push('</div>');
		html.push('</div>');

		$("body").append(html.join(''));
		this._box = $("body #" + this._id);
		this._box.modal();
		if (this._width) {
			this._box.css({
				'width' : this._width + 'px',
				'margin-left' : function() {
					return -($(this).width() / 2);
				}
			});
		}
		this._contentBox = this._box.find('.modal-body');
		$("#" + this._id).on('hidden', function(e) {
			if ($.data(e.target) == $.data(this)) {
				self._box.remove();
			}
		});

		$.isFunction(self._onLoad) && self._onLoad(this, self._contentBox);
		$.get(this._url, this._ajaxData).done(function(responce) {
			self._contentBox.html(responce.content);
			self._box.modal('show');
			$.isFunction(self._onLoaded) && self._onLoaded(responce, self._contentBox);
		}, 'json');
	}


	ModalWindow.prototype.constructor = ModalWindow;
	ModalWindow.prototype.close = function() {
		var self = this;
		$.isFunction(self._onClose) && self._onClose();
		self._contentBox.find('*').unbind();
		self._box.fadeOut(444, function() {
			self._box.modal('hide').remove();
			$.isFunction(self._onClosed) && self._onClosed();
		});
	}
	/*
	 * EDIT window
	 */
	function EditModalWindow(options) {
		var self = this;
		var _t = options.onLoaded;		
		options.onLoaded = function(r, c) {
			_t && _t(r, c);
			c.find('form').submit(function(e) {
				return self.save();
			});
		}

		ModalWindow.call(this, options);

		this._labels = $.extend({
			save : (options.labels && options.labels.save) || 'Save Changes'
		}, this._labels);

		this._onSave = options.onSave;
		this._box.find('.modal-footer').append('<button class="btn btn-primary">' + this._labels.save + '</button>');
		this._saveBtn = this._box.find('button.btn-primary');
		this._saveBtn.on('click', function(e) {
			e.preventDefault();
			self.save();
		});
	}


	App.inherit(EditModalWindow, ModalWindow);

	EditModalWindow.prototype.save = function(e) {
		var self = this;
		var chainCallback = function() {
			self.close();
		};
		this._onSave(this, this._contentBox, chainCallback);
		return false;
	};

	App.EditModalWindow = EditModalWindow;
	App.ModalWindow = ModalWindow;

})(jQuery);

(function($) {'use strict';

	var opts = {
	}

	function _disableBody() {
		$(document).bind('dragenter', function(e) {
			return false;
		}).bind('dragleave', function(e) {
			return false;
		}).bind('dragover', function(e) {
			var dt = e.originalEvent.dataTransfer;
			if (!dt) {
				return;
			}
			dt.dropEffect = 'none';
			return false;
		}.bind(this));
	}

	function _enableDrop(selector, options) {
		$(selector).bind("dragover", function(e) {
			options.dragover && options.dragover(e);
			return false;
		}).bind("dragenter", function(e) {
			options.dragenter && options.dragenter(e);
			return false;
		}).bind('dragleave', function(e) {
			options.dragleave && options.dragleave(e);
			return false;
		}).bind("drop", function(e) {
			options.drop && options.drop(e);
			return false;
		});
	}

	var module = {
		disableBody : _disableBody,
		enableDrop : _enableDrop,
	};

	App.DropArea = module;

})(jQuery);

(function($) {'use strict';

	var opts = {
		form : null,
		response : null,
		iframe_div : null
	}

	function mergeDataToFormData(formData, data) {
		$.each(data, function(k, v) {
			formData.append(k, v);
		});
		return formData;
	}

	function _send(options) {

		var opts = {
			url : options.url,
			type : 'POST',
		};
		if (options.beforeSend) {
			opts.beforeSend = options.beforeSend;
		}
		if (options.error) {
			opts.error = options.error;
		}

		var data = new FormData(options.form ? $(options.form)[0] : {});
		if (options.data && data != undefined) {
			data = mergeDataToFormData(data, options.data);
		}

		return $.ajax($.extend(opts, {
			success : options.success,
			data : data || {},
			cache : false,
			mimeType : 'multipart/form-data',
			contentType : false,
			processData : false
		}));
	}

	var module = {
		send : _send
	};

	App.FormDataSend = module;

})(jQuery);

