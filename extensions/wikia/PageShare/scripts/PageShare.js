require(['wikia.window', 'wikia.tracker', 'jquery'], function (win, tracker, $) {
	'use strict';

	var trackFunc = tracker.buildTrackingFunction({
		action: win.Wikia.Tracker.ACTIONS.CLICK,
		category: 'social-share',
		trackingMethod: 'analytics'
	});

	/**
	 * @desc Share click handler
	 *
	 * @param {Event} event
	 */
	function shareLinkClick(event) {
		event.stopPropagation();
		event.preventDefault();

		var service = $(event.target).closest('a'),
			url = service.prop('href'),
			title = service.prop('title'),
			h = (win.innerHeight / 2 | 0), // round down
			w = (win.innerWidth / 2 | 0);  // round down

		trackFunc({label: service.data('share-service')});

		win.open(url, title, 'width=' + w + ',height=' + h);
	}

	function appendShareIcons(data) {
		var $container = $('#PageShareContainer'),
			url = encodeURIComponent(win.location.origin + win.location.pathname),
			title = encodeURIComponent(win.document.title),
			result;
		if (data.socialIcons) {
			result = data.socialIcons.replace(/\$url/g, url).replace(/\$title/g, title);
			$container.html(result)
				.on('click', '.page-share a', shareLinkClick);
		}
	}

	function loadShareIcons() {
		var useLang = $.getUrlVar('uselang'),
			mCache = $.getUrlVar('mcache'),
			requestData,
			shareLang,
			browserLang;

		if (win.wgUserName) {
			shareLang = win.wgUserLanguage;
		} else {
			browserLang = (
				// Chrome and Firefox
				win.navigator.languages ? win.navigator.languages[0] :
				// Chrome and Firefox fallback
				win.navigator.language ||
				// Internet Explorer
				win.navigator.browserLanguage ||
				win.navigator.userLanguage ||
				win.navigator.systemLanguage
			);

			if (browserLang) {
				shareLang = browserLang.substr(0, 2);
			}
		}

		requestData = {
			shareLang: shareLang,
			isTouchScreen: win.Wikia.isTouchScreen() ? 1 : 0
		};

		if (mCache) {
			requestData.mcache = mCache;
		}

		if (useLang) {
			requestData.useLang = useLang;
		}

		$.nirvana.sendRequest({
			type: 'GET',
			controller: 'PageShare',
			method: 'getShareIcons',
			data: requestData,
			callback: appendShareIcons
		});
	}

	// bind events to links
	$(function () {
		loadShareIcons();
	});
});
