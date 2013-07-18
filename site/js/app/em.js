/**
 * Event Managet
 */
(function($) {
	
	'use strict';

	function Event() {
		this._observers = [];
	}


	Event.prototype = {
		raise : function(eventData) {
			if (this._observers.length > 0) {
				for (var i in this._observers) {
					if ( typeof this._observers[i] !== 'undefined') {
						var item = this._observers[i];
						if ( typeof item.observer !== 'undefined') {
							item.observer.call(item.context, eventData);
						}
					}
				}
			}
		},

		subscribe : function(fobserver, context) {
			var ctx = context || null, dub = false;

			this._observers.forEach(function(item) {
				if (item.observer.toString() === fobserver.toString()) {
					dub = true;
					return false;
				}
			});

			if (!dub) {
				this._observers.push({
					observer : fobserver,
					context : ctx
				});
			} else {
				// App.L('info', ['Duplicated bind']);
			}
		},

		unsubscribe : function(fobserver, context) {
			for (var i in this._observers)
			if ( typeof this._observers[i].observer !== 'undefined' && this._observers[i].observer.toString() === fobserver.toString() && this._observers[i].context === context) {
				delete this._observers[i]
			};
		}
	};

	/**
	 * Event Manager's body
	 * --------------------------------------------------------------
	 */
	var _eventsArray = {};

	function _bindEvent(eventName, callbackFunction, context) {
		if (_eventsArray[eventName] === undefined) {
			_eventsArray[eventName] = new Event();
		}
		_eventsArray[eventName].subscribe(callbackFunction, context);
		return this;
	};

	function _unbindEvent(eventName, callbackFunction, context) {
		if ( typeof _eventsArray[eventName] !== 'undefined') {
			_eventsArray[eventName].unsubscribe(callbackFunction, context);
		}
		return this;
	};

	function _totalUnBind(eventName) {
		if (_eventsArray[eventName] !== undefined) {
			delete _eventsArray[eventName];
		}
	}

	function _triggerEvent(eventName, eventData) {
		//App.L('info', ['_triggerEvent', eventName, eventData]);
		if (_eventsArray[eventName] !== undefined) {
			_eventsArray[eventName].raise(eventData);
		}

		return this;
	};

	function _getRespondersByEventName(eventName) {
		if ( typeof eventName === 'undefined') {
			App.L(_eventsArray);
		} else {
			for (var i in _eventsArray[eventName]._observers) {
				var item = _eventsArray[eventName]._observers[i];
				App.L(item.observer);
			}
		}
	};

	/**
	 * Event Manager's public interface
	 * --------------------------------------------------------------
	 */
	App.EM = {
		bind : _bindEvent,
		unbind : _unbindEvent,
		totalUnBind : _totalUnBind,
		trig : _triggerEvent,
		eventResponders : _getRespondersByEventName
	}

})(jQuery); 