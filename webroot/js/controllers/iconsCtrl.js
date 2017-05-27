
/** 
  * controllers that generate the list of icons (Fontawesome and Themify)
*/
app.controller('IconsCtrl', ["$scope", function ($scope) {

    $scope.icons = [{
        "type": "fontawesome",
        "name": "Font Awesome",
        "version": "4.2",
        "prefix": "fa",
        "sections": [{
            'title': '20 New Icons in 4.5',
            'icons': ['fa-bluetooth', 'fa-bluetooth-b', 'fa-codiepie', 'fa-credit-card-alt', 'fa-edge', 'fa-fort-awesome', 'fa-hashtag', 'fa-mixcloud', 'fa-modx', 'fa-pause-circle', 'fa-pause-circle-o', 'fa-percent', 'fa-product-hunt', 'fa-reddit-alien', 'fa-scribd', 'fa-shopping-bag', 'fa-shopping-basket', 'fa-stop-circle', 'fa-stop-circle-o', 'fa-usb']
        }, {
            'title': 'Web Application Icons',
            'icons': ['fa-adjust', 'fa-anchor', 'fa-archive', 'fa-arrows', 'fa-arrows-h', 'fa-arrows-v', 'fa-asterisk', 'fa-ban', 'fa-bar-chart-o', 'fa-barcode', 'fa-bars', 'fa-beer', 'fa-bell', 'fa-bell-o', 'fa-bolt', 'fa-book', 'fa-bookmark', 'fa-bookmark-o', 'fa-briefcase', 'fa-bug', 'fa-building-o', 'fa-bullhorn', 'fa-bullseye', 'fa-calendar', 'fa-calendar-o', 'fa-camera', 'fa-camera-retro', 'fa-caret-square-o-down', 'fa-caret-square-o-left', 'fa-caret-square-o-right', 'fa-caret-square-o-up', 'fa-certificate', 'fa-check', 'fa-check-circle', 'fa-check-circle-o', 'fa-check-square', 'fa-check-square-o', 'fa-circle', 'fa-circle-o', 'fa-clock-o', 'fa-cloud', 'fa-cloud-download', 'fa-cloud-upload', 'fa-code', 'fa-code-fork', 'fa-coffee', 'fa-cog', 'fa-cogs', 'fa-comment', 'fa-comment-o', 'fa-comments', 'fa-comments-o', 'fa-compass', 'fa-credit-card', 'fa-crop', 'fa-crosshairs', 'fa-cutlery', 'fa-dashboard', 'fa-desktop', 'fa-dot-circle-o', 'fa-download', 'fa-edit', 'fa-ellipsis-h', 'fa-ellipsis-v', 'fa-envelope', 'fa-envelope-o', 'fa-eraser', 'fa-exchange', 'fa-exclamation', 'fa-exclamation-circle', 'fa-exclamation-triangle', 'fa-external-link', 'fa-external-link-square', 'fa-eye', 'fa-eye-slash', 'fa-female', 'fa-fighter-jet', 'fa-film', 'fa-filter', 'fa-fire', 'fa-fire-extinguisher', 'fa-flag', 'fa-flag-checkered', 'fa-flag-o', 'fa-flash', 'fa-flask', 'fa-folder', 'fa-folder-o', 'fa-folder-open', 'fa-folder-open-o', 'fa-frown-o', 'fa-gamepad', 'fa-gavel', 'fa-gear', 'fa-gears', 'fa-gift', 'fa-glass', 'fa-globe', 'fa-group', 'fa-hdd-o', 'fa-headphones', 'fa-heart', 'fa-heart-o', 'fa-home', 'fa-inbox', 'fa-info', 'fa-info-circle', 'fa-key', 'fa-keyboard-o', 'fa-laptop', 'fa-leaf', 'fa-legal', 'fa-lemon-o', 'fa-level-down', 'fa-level-up', 'fa-lightbulb-o', 'fa-location-arrow', 'fa-lock', 'fa-magic', 'fa-magnet', 'fa-mail-forward', 'fa-mail-reply', 'fa-mail-reply-all', 'fa-male', 'fa-map-marker', 'fa-meh-o', 'fa-microphone', 'fa-microphone-slash', 'fa-minus', 'fa-minus-circle', 'fa-minus-square', 'fa-minus-square-o', 'fa-mobile', 'fa-mobile-phone', 'fa-money', 'fa-moon-o', 'fa-music', 'fa-pencil', 'fa-pencil-square', 'fa-pencil-square-o', 'fa-phone', 'fa-phone-square', 'fa-picture-o', 'fa-plane', 'fa-plus', 'fa-plus-circle', 'fa-plus-square', 'fa-plus-square-o', 'fa-power-off', 'fa-print', 'fa-puzzle-piece', 'fa-qrcode', 'fa-question', 'fa-question-circle', 'fa-quote-left', 'fa-quote-right', 'fa-random', 'fa-refresh', 'fa-reply', 'fa-reply-all', 'fa-retweet', 'fa-road', 'fa-rocket', 'fa-rss', 'fa-rss-square', 'fa-search', 'fa-search-minus', 'fa-search-plus', 'fa-share', 'fa-share-square', 'fa-share-square-o', 'fa-shield', 'fa-shopping-cart', 'fa-sign-in', 'fa-sign-out', 'fa-signal', 'fa-sitemap', 'fa-smile-o', 'fa-sort', 'fa-sort-alpha-asc', 'fa-sort-alpha-desc', 'fa-sort-amount-asc', 'fa-sort-amount-desc', 'fa-sort-asc', 'fa-sort-desc', 'fa-sort-down', 'fa-sort-numeric-asc', 'fa-sort-numeric-desc', 'fa-sort-up', 'fa-spinner', 'fa-square', 'fa-square-o', 'fa-star', 'fa-star-half', 'fa-star-half-empty', 'fa-star-half-full', 'fa-star-half-o', 'fa-star-o', 'fa-subscript', 'fa-suitcase', 'fa-sun-o', 'fa-superscript', 'fa-tablet', 'fa-tachometer', 'fa-tag', 'fa-tags', 'fa-tasks', 'fa-terminal', 'fa-thumb-tack', 'fa-thumbs-down', 'fa-thumbs-o-down', 'fa-thumbs-o-up', 'fa-thumbs-up', 'fa-ticket', 'fa-times', 'fa-times-circle', 'fa-times-circle-o', 'fa-tint', 'fa-toggle-down', 'fa-toggle-left', 'fa-toggle-right', 'fa-toggle-up', 'fa-trash-o', 'fa-trophy', 'fa-truck', 'fa-umbrella', 'fa-unlock', 'fa-unlock-alt', 'fa-unsorted', 'fa-upload', 'fa-user', 'fa-users', 'fa-video-camera', 'fa-volume-down', 'fa-volume-off', 'fa-volume-up', 'fa-warning', 'fa-wheelchair', 'fa-wrench']
        }, {
            'title': 'Form Control Icons',
            'icons': ['fa-check-square', 'fa-check-square-o', 'fa-circle', 'fa-circle-o', 'fa-dot-circle-o', 'fa-minus-square', 'fa-minus-square-o', 'fa-plus-square', 'fa-plus-square-o', 'fa-square', 'fa-square-o']
        }, {
            'title': 'Currency Icons',
            'icons': ['fa-bitcoin', 'fa-btc', 'fa-cny', 'fa-dollar', 'fa-eur', 'fa-euro', 'fa-gbp', 'fa-inr', 'fa-jpy', 'fa-krw', 'fa-money', 'fa-rmb', 'fa-rouble', 'fa-rub', 'fa-ruble', 'fa-rupee', 'fa-try', 'fa-turkish-lira', 'fa-usd', 'fa-won', 'fa-yen']
        }, {
            'title': 'Text Editor Icons',
            'icons': ['fa-align-center', 'fa-align-justify', 'fa-align-left', 'fa-align-right', 'fa-bold', 'fa-chain', 'fa-chain-broken', 'fa-clipboard', 'fa-columns', 'fa-copy', 'fa-cut', 'fa-dedent', 'fa-eraser', 'fa-file', 'fa-file-o', 'fa-file-text', 'fa-file-text-o', 'fa-files-o', 'fa-floppy-o', 'fa-font', 'fa-indent', 'fa-italic', 'fa-link', 'fa-list', 'fa-list-alt', 'fa-list-ol', 'fa-list-ul', 'fa-outdent', 'fa-paperclip', 'fa-paste', 'fa-repeat', 'fa-rotate-left', 'fa-rotate-right', 'fa-save', 'fa-scissors', 'fa-strikethrough', 'fa-table', 'fa-text-height', 'fa-text-width', 'fa-th', 'fa-th-large', 'fa-th-list', 'fa-underline', 'fa-undo', 'fa-unlink']
        }, {
            'title': 'Directional Icons',
            'icons': ['fa-angle-double-down', 'fa-angle-double-left', 'fa-angle-double-right', 'fa-angle-double-up', 'fa-angle-down', 'fa-angle-left', 'fa-angle-right', 'fa-angle-up', 'fa-arrow-circle-down', 'fa-arrow-circle-left', 'fa-arrow-circle-o-down', 'fa-arrow-circle-o-left', 'fa-arrow-circle-o-right', 'fa-arrow-circle-o-up', 'fa-arrow-circle-right', 'fa-arrow-circle-up', 'fa-arrow-down', 'fa-arrow-left', 'fa-arrow-right', 'fa-arrow-up', 'fa-arrows', 'fa-arrows-alt', 'fa-arrows-h', 'fa-arrows-v', 'fa-caret-down', 'fa-caret-left', 'fa-caret-right', 'fa-caret-square-o-down', 'fa-caret-square-o-left', 'fa-caret-square-o-right', 'fa-caret-square-o-up', 'fa-caret-up', 'fa-chevron-circle-down', 'fa-chevron-circle-left', 'fa-chevron-circle-right', 'fa-chevron-circle-up', 'fa-chevron-down', 'fa-chevron-left', 'fa-chevron-right', 'fa-chevron-up', 'fa-hand-o-down', 'fa-hand-o-left', 'fa-hand-o-right', 'fa-hand-o-up', 'fa-long-arrow-down', 'fa-long-arrow-left', 'fa-long-arrow-right', 'fa-long-arrow-up', 'fa-toggle-down', 'fa-toggle-left', 'fa-toggle-right', 'fa-toggle-up']
        }, {
            'title': 'Video Player Icons',
            'icons': ['fa-arrows-alt', 'fa-backward', 'fa-compress', 'fa-eject', 'fa-expand', 'fa-fast-backward', 'fa-fast-forward', 'fa-forward', 'fa-pause', 'fa-play', 'fa-play-circle', 'fa-play-circle-o', 'fa-step-backward', 'fa-step-forward', 'fa-stop', 'fa-youtube-play']
        }, {
            'title': 'Brand Icons',
            'icons': ['fa-adn', 'fa-android', 'fa-apple', 'fa-bitbucket', 'fa-bitbucket-square', 'fa-bitcoin', 'fa-btc', 'fa-css3', 'fa-dribbble', 'fa-dropbox', 'fa-facebook', 'fa-facebook-square', 'fa-flickr', 'fa-foursquare', 'fa-github', 'fa-github-alt', 'fa-github-square', 'fa-gittip', 'fa-google-plus', 'fa-google-plus-square', 'fa-html5', 'fa-instagram', 'fa-linkedin', 'fa-linkedin-square', 'fa-linux', 'fa-maxcdn', 'fa-pagelines', 'fa-pinterest', 'fa-pinterest-square', 'fa-renren', 'fa-skype', 'fa-stack-exchange', 'fa-stack-overflow', 'fa-trello', 'fa-tumblr', 'fa-tumblr-square', 'fa-twitter', 'fa-twitter-square', 'fa-vimeo-square', 'fa-vk', 'fa-weibo', 'fa-windows', 'fa-xing', 'fa-xing-square', 'fa-youtube', 'fa-youtube-play', 'fa-youtube-squar']
        }, {
            'title': 'Medical Icons',
            'icons': ['fa-ambulance', 'fa-h-square', 'fa-hospital-o', 'fa-medkit', 'fa-plus-square', 'fa-stethoscope', 'fa-user-md', 'fa-wheelchair']
        }]

    }, {
        "type": "themify",
        "name": "Themify Icons",
        "version": "",
        "prefix": "",
        "sections": [{
            'title': 'Arrows & Direction Icons',
            'icons': ['ti-arrow-up', 'ti-arrow-right', 'ti-arrow-left', 'ti-arrow-down', 'ti-arrows-vertical', 'ti-arrows-horizontal', 'ti-angle-up', 'ti-angle-right', 'ti-angle-left', 'ti-angle-down', 'ti-angle-double-up', 'ti-angle-double-right', 'ti-angle-double-left', 'ti-angle-double-down', 'ti-move', 'ti-fullscreen', 'ti-arrow-top-right', 'ti-arrow-top-left', 'ti-arrow-circle-up', 'ti-arrow-circle-right', 'ti-arrow-circle-left', 'ti-arrow-circle-down', 'ti-arrows-corner', 'ti-split-v', 'ti-split-v-alt', 'ti-split-h', 'ti-hand-point-up', 'ti-hand-point-right', 'ti-hand-point-left', 'ti-hand-point-down', 'ti-back-right', 'ti-back-left', 'ti-exchange-vertical']
        }, {
            'title': 'Web App Icons',
            'icons': ['ti-wand', 'ti-save', 'ti-save-alt', 'ti-direction', 'ti-direction-alt', 'ti-user', 'ti-link', 'ti-unlink', 'ti-trash', 'ti-target', 'ti-tag', 'ti-desktop', 'ti-tablet', 'ti-mobile', 'ti-email', 'ti-star', 'ti-spray', 'ti-signal', 'ti-shopping-cart', 'ti-shopping-cart-full', 'ti-settings', 'ti-search', 'ti-zoom-in', 'ti-zoom-out', 'ti-cut', 'ti-ruler', 'ti-ruler-alt-2', 'ti-ruler-pencil', 'ti-ruler-alt', 'ti-bookmark', 'ti-bookmark-alt', 'ti-reload', 'ti-plus', 'ti-minus', 'ti-close', 'ti-pin', 'ti-pencil', 'ti-pencil-alt', 'ti-paint-roller', 'ti-paint-bucket', 'ti-na', 'ti-medall', 'ti-medall-alt', 'ti-marker', 'ti-marker-alt', 'ti-lock', 'ti-unlock', 'ti-location-arrow', 'ti-layout', 'ti-layers', 'ti-layers-alt', 'ti-key', 'ti-image', 'ti-heart', 'ti-heart-broken', 'ti-hand-stop', 'ti-hand-open', 'ti-hand-drag', 'ti-flag', 'ti-flag-alt', 'ti-flag-alt-2', 'ti-eye', 'ti-import', 'ti-export', 'ti-cup', 'ti-crown', 'ti-comments', 'ti-comment', 'ti-comment-alt', 'ti-thought', 'ti-clip', 'ti-check', 'ti-check-box', 'ti-camera', 'ti-announcement', 'ti-brush', 'ti-brush-alt', 'ti-palette', 'ti-briefcase', 'ti-bolt', 'ti-bolt-alt', 'ti-blackboard', 'ti-bag', 'ti-world', 'ti-wheelchair', 'ti-car', 'ti-truck', 'ti-timer', 'ti-ticket', 'ti-thumb-up', 'ti-thumb-down', 'ti-stats-up', 'ti-stats-down', 'ti-shine', 'ti-shift-right', 'ti-shift-left', 'ti-shift-right-alt', 'ti-shift-left-alt', 'ti-shield', 'ti-notepad', 'ti-server', 'ti-pulse', 'ti-printer', 'ti-power-off', 'ti-plug', 'ti-pie-chart', 'ti-panel', 'ti-package', 'ti-music', 'ti-music-alt', 'ti-mouse', 'ti-mouse-alt', 'ti-money', 'ti-microphone', 'ti-menu', 'ti-menu-alt', 'ti-map', 'ti-map-alt', 'ti-location-pin', 'ti-light-bulb', 'ti-info', 'ti-infinite', 'ti-id-badge', 'ti-hummer', 'ti-home', 'ti-help', 'ti-headphone', 'ti-harddrives', 'ti-harddrive', 'ti-gift', 'ti-game', 'ti-filter', 'ti-files', 'ti-file', 'ti-zip', 'ti-folder', 'ti-envelope', 'ti-dashboard', 'ti-cloud', 'ti-cloud-up', 'ti-cloud-down', 'ti-clipboard', 'ti-calendar', 'ti-book', 'ti-bell', 'ti-basketball', 'ti-bar-chart', 'ti-bar-chart-alt', 'ti-archive', 'ti-anchor', 'ti-alert', 'ti-alarm-clock', 'ti-agenda', 'ti-write', 'ti-wallet', 'ti-video-clapper', 'ti-video-camera', 'ti-vector', 'ti-support', 'ti-stamp', 'ti-slice', 'ti-shortcode', 'ti-receipt', 'ti-pin2', 'ti-pin-alt', 'ti-pencil-alt2', 'ti-eraser', 'ti-more', 'ti-more-alt', 'ti-microphone-alt', 'ti-magnet', 'ti-line-double', 'ti-line-dotted', 'ti-line-dashed', 'ti-ink-pen', 'ti-info-alt', 'ti-help-alt', 'ti-headphone-alt', 'ti-gallery', 'ti-face-smile', 'ti-face-sad', 'ti-credit-card', 'ti-comments-smiley', 'ti-time', 'ti-share', 'ti-share-alt', 'ti-rocket', 'ti-new-window', 'ti-rss', 'ti-rss-alt']
        }, {
            'title': 'Control Icons',
            'icons': ['ti-control-stop', 'ti-control-shuffle', 'ti-control-play', 'ti-control-pause', 'ti-control-forward', 'ti-control-backward', 'ti-volume', 'ti-control-skip-forward', 'ti-control-skip-backward', 'ti-control-record', 'ti-control-eject']
        }, {
            'title': 'Text Editor',
            'icons': ['ti-paragraph', 'ti-uppercase', 'ti-underline', 'ti-text', 'ti-Italic', 'ti-smallcap', 'ti-list', 'ti-list-ol', 'ti-align-right', 'ti-align-left', 'ti-align-justify', 'ti-align-center', 'ti-quote-right', 'ti-quote-left']
        }, {
            'title': 'Layout Icons',
            'icons': ['ti-layout-width-full', 'ti-layout-width-default', 'ti-layout-width-default-alt', 'ti-layout-tab', 'ti-layout-tab-window', 'ti-layout-tab-v', 'ti-layout-tab-min', 'ti-layout-slider', 'ti-layout-slider-alt', 'ti-layout-sidebar-right', 'ti-layout-sidebar-none', 'ti-layout-sidebar-left', 'ti-layout-placeholder', 'ti-layout-menu', 'ti-layout-menu-v', 'ti-layout-menu-separated', 'ti-layout-menu-full', 'ti-layout-media-right', 'ti-layout-media-right-alt', 'ti-layout-media-overlay', 'ti-layout-media-overlay-alt', 'ti-layout-media-overlay-alt-2', 'ti-layout-media-left', 'ti-layout-media-left-alt', 'ti-layout-media-center', 'ti-layout-media-center-alt', 'ti-layout-list-thumb', 'ti-layout-list-thumb-alt', 'ti-layout-list-post', 'ti-layout-list-large-image', 'ti-layout-line-solid', 'ti-layout-grid4', 'ti-layout-grid3', 'ti-layout-grid2', 'ti-layout-grid2-thumb', 'ti-layout-cta-right', 'ti-layout-cta-left', 'ti-layout-cta-center', 'ti-layout-cta-btn-right', 'ti-layout-cta-btn-left', 'ti-layout-column4', 'ti-layout-column3', 'ti-layout-column2', 'ti-layout-accordion-separated', 'ti-layout-accordion-merged', 'ti-layout-accordion-list', 'ti-widgetized', 'ti-widget', 'ti-widget-alt', 'ti-view-list', 'ti-view-list-alt', 'ti-view-grid', 'ti-upload', 'ti-download', 'ti-loop', 'ti-layout-sidebar-2', 'ti-layout-grid4-alt', 'ti-layout-grid3-alt', 'ti-layout-grid2-alt', 'ti-layout-column4-alt', 'ti-layout-column3-alt', 'ti-layout-column2-alt']
        }, {
            'title': 'Brand Icons',
            'icons': ['ti-flickr', 'ti-flickr-alt', 'ti-instagram', 'ti-google', 'ti-github', 'ti-facebook', 'ti-dropbox', 'ti-dropbox-alt', 'ti-dribbble', 'ti-apple', 'ti-android', 'ti-yahoo', 'ti-trello', 'ti-stack-overflow', 'ti-soundcloud', 'ti-sharethis', 'ti-sharethis-alt', 'ti-reddit', 'ti-microsoft', 'ti-microsoft-alt', 'ti-linux', 'ti-jsfiddle', 'ti-joomla', 'ti-html5', 'ti-css3', 'ti-drupal', 'ti-wordpress', 'ti-tumblr', 'ti-tumblr-alt', 'ti-skype', 'ti-youtube', 'ti-vimeo', 'ti-vimeo-alt', 'ti-twitter', 'ti-twitter-alt', 'ti-linkedin', 'ti-pinterest', 'ti-pinterest-alt', 'ti-themify-logo', 'ti-themify-favicon', 'ti-themify-favicon-alt']
        }]
    }];
}]);