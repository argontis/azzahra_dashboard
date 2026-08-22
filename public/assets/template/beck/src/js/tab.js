(function($) { 
    "use strict";
        
    // Show tab content
    $('body').on('click', 'a[data-toggle="tab"]', function(key, el) {
        let navTabs = $(this).closest('.nav-tabs');

        // Set active tab nav
        navTabs.find('a[data-toggle="tab"]').removeClass('active');
        $(this).addClass('active');

        if (navTabs.hasClass('wizard') || $(this).closest('.wizard').length) {
            navTabs.find('a[data-toggle="tab"]')
                .removeClass('text-white bg-theme-1')
                .addClass('text-gray-600 bg-gray-200');
            $(this)
                .removeClass('text-gray-600 bg-gray-200')
                .addClass('text-white bg-theme-1');
        }

        // Set active tab content
        let elementId = $(this).attr('data-target');
        $(elementId).closest('.tab-content')
            .find('.tab-content__pane')
            .removeClass('active');
        $(elementId).addClass('active');
    });
})($);