App.Ui = {};

(function($) {'use strict';

	var local = {
		options : {},
		root : null,
		row : null,
		data : [],
		events : {},
		pager : {
			template : undefined,
			holder : undefined,
			page : undefined,
			per_page : undefined,
			count : undefined
		},
		filter:{
			
		}
	}

	function _create(options) {
		local.row = _.template($(options.row).html());
		local.options.url = options.url;
		local.options.data = options.data;
		local.options.events = options.events;
		local.root = $(options.selector);
		//pager init
		if (options.pager) {
			local.pager = options.pager || {};
			local.pager._template = _.template($(local.pager.template).html());
			local.pager.page = options.pager.page || 0;
			local.pager.per_page = options.pager.per_page || 50;
		} else {
			local.pager = undefined;
		}

		reload({});
		handleEvents();
		return this;
	}

	function handleEvents() {
		if (local.options.events) {
			$.each(local.options.events, function(k, v) {
				App.EM.totalUnBind(k);
				App.EM.bind(k, v);
			});
		}
	}

	function getThCount() {
		return $(options.root).find('thead th').length;
	}

	function setTableError(message) {
		var tr = ['<tr>'];
		tr.push('<td colspan="' + getThCount() + '">');
		tr.push(message);
		tr.push('</td>');
		tr.push('</tr>');
		local.root.find('tbody').empty().html(tr.join(''));
	}

	function loadPage(page) {
		var swap_to_page = page;
		if (page == 'next') {
			swap_to_page = (local.pager.page + 1);
		}
		if (page == 'prev') {
		 	swap_to_page = (local.pager.page - 1);
		}
		if (swap_to_page < 0) {
			swap_to_page = 0;
		}
		if (local.pager.page != swap_to_page) {
			local.pager.page = swap_to_page;
			reload({});
		}
	}
	function setFilter(filter){
		local.filter = filter;
	}

	function reloadPager() {
		if (local.pager) {
			var diff = local.count / local.pager.per_page,
				round_diff = Math.round(diff);
			var cnt = round_diff + ( (diff - round_diff) > 0? 1: 0); 
			local.root.find(local.pager.holder).empty().append(local.pager._template({
				count: cnt,
				current: local.pager.page
			}));
		}
	}

	function reload(opts) {
		var sendData = $.extend(local.options.data || {}, opts || {});
		sendData.limit = local.pager.per_page;
		sendData.offset = (local.pager.per_page * local.pager.page);
		sendData.filter = local.filter || {};
		$.get(local.options.url, sendData, function(responce) {
			if (!responce) {
				return;
			}
			if (responce.error) {
				setTableError(responce.message || "Can't get table data");
			} else {
				local.root.find('tbody').empty();
				local.count = responce.count;
				$.each(responce.data, function(k, v) {
					local.root.find('tbody').append(local.row({
						row : v
					}));
				});
				reloadPager();
			}
		}, 'json');
	}

	var module = {
		create : _create,
		reload : reload,
		loadPage : loadPage,
		setFilter: setFilter
	}

	App.Ui.Table = module;

})(jQuery);
