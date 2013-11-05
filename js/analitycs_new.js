// pagetracker variable
var pageTracker;

function track(site,track,url,target) {
	if (pageTracker !== undefined)
		pageTracker._trackPageview('/track/'+site+'/'+track);
	if (url !== undefined) {
		if (url.match("store.diesel.com"))
			trackWoodland('cloud/item_to_online_store');
		if (!url.match('http://'))
			url = _www_url+url;
		if (target !== undefined && target == 'blank') {
			window.open(url);
		} else if (target !== undefined && target.match('popup')) {
			var size = { w: target.split('popup')[1].split('x')[0], h: target.split('popup')[1].split('x')[1] };
			$.open.newWindow(url, {
				width: size.w,
				height: size.h,
				top:  window.screenY + (window.outerHeight - size.h) / 2,
				left: window.screenX + (window.outerWidth - size.w) / 2,
				'scrollbars': 'no'
			});
		} else {
			document.location.href = url;
		}
	}
	if (track == 'cloud/firstclick_hp_to_open' && jQuery(window).scrollTop() == 0) {
		animateScrollTo('tagcloud');
	}
};

// tracking helper function
var trackWoodland = function trackWoodland(trackcode, url, target) {
	track('haru', trackcode, url, target);
};
var trackMobile = function trackMobile(trackcode, url, target) {
	track('mobile', trackcode, url, target);
};