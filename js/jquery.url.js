(function($) {
    
	var Url = function(url) {
		var parts = url.split('?');
		this.base = parts[0];
		this.params = {};
		if (parts.length <= 1)
			return;
		var params = parts[1].split('&');
		for (var i = 0; i < params.length; ++i) {
			var pair = params[i].split('=');
			var name = decodeURIComponent(pair[0]);
			var value = "";
			if (pair.length > 1)
				value = decodeURIComponent(pair[1]);
			if (name in this.params) {
				if (!(this.params[name] instanceof Array))
					this.params[name] = [this.params[name]];
				this.params[name].push(value);
			} else {
				this.params[name] = value;
			}
		}
	};
	
	Url.prototype.toString = function() {
		var parts = [];
		for (var key in this.params) {
			var name = encodeURIComponent(key);
			var value = this.params[key];
			var values = value;
			if (!(value instanceof Array)) {
				values = [value];
			}
			var pieces = [];
			for (var i = 0; i < values.length; ++i) {
				pieces.push(name + '=' + encodeURIComponent(values[i].toString()));
			}
			parts.push(pieces.join('&'));
		}
		var query = parts.length ? '?' + parts.join('&') : "";
		return this.base + query;
	};
	
	$.url = function(url) {
		// TODO: chek url syntax with RegExp
		return new Url(url);
	};
	
})(jQuery);