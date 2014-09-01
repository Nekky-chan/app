define(
	'wikia.globalnavigation.lazyload',
	['jquery', 'wikia.nirvana', 'wikia.querystring'],
	function lazyLoad($, nirvana, Querystring) {
		'use strict';

		var getHubLinks, getMenuItemsDone, errorHandler, isMenuWorking, menuLoading,
			menuLoaded, subMenuSelector;

		menuLoaded = false;
		menuLoading = false;

		/**
		 * Callback to handle request that come back with success (Creation of submenus)
		 * @param  {object} menuItems JSON object with all submenu for Global Nav data
		 */
		getMenuItemsDone = function (menuItems) {
			var $sections,
				$hubs = $('#hubs'),
				$verticals = $('> .hubs', $hubs),
				$hubLinks = $('> .hub-links', $hubs);

			$sections = $($.parseHTML(menuItems)).removeClass('active');
			$('> .active', $hubLinks).removeClass('active');

			subMenuSelector = '.' + $('> .active', $verticals).data('vertical') + '-links';

			$hubLinks.append($sections);
			$hubLinks.find(subMenuSelector).addClass('active');

			menuLoading = false;
			menuLoaded = true;
		};

		/**
		 * Callback to handle request when there is some error...
		 */
		errorHandler = function () {
			menuLoading = false;
			menuLoaded = false;
		};

		getHubLinks = function () {
			var lang;

			if (menuLoaded || menuLoading) {
				return;
			}

			menuLoading = true;

			lang = new Querystring().getVal('uselang');

			$.when(
				nirvana.sendRequest({
					controller: 'GlobalNavigationController',
					method: 'lazyLoadHubsMenu',
					format: 'html',
					type: 'GET',
					data: {
						lang: lang
					}
				})
			).then(
				getMenuItemsDone,
				errorHandler
			);
		};

		isMenuWorking = function () {
			return (menuLoading || menuLoaded);
		};

		return {
			getHubLinks: getHubLinks,
			isMenuWorking: isMenuWorking
		};
	}
);
