jQuery(document).ready(function ($) {
    var $menuButton = $('#rh-mobile-menu-button');
    var $closeButton = $('#rh-mobile-menu-close');
    var $menuContainer = $('#rh-mobile-menu-container');
    var $overlay = $('#rh-mobile-menu-overlay');

    function openMenu() {
        $menuContainer.addClass('rh-menu-open');
        $overlay.addClass('rh-menu-open');
        $('body').css('overflow', 'hidden'); // Prevent scrolling behind menu
    }

    function closeMenu() {
        $menuContainer.removeClass('rh-menu-open');
        $overlay.removeClass('rh-menu-open');
        $('body').css('overflow', '');
    }

    $menuButton.on('click', function () {
        openMenu();
    });

    $closeButton.on('click', function () {
        closeMenu();
    });

    $overlay.on('click', function () {
        closeMenu();
    });

    // Handle sub-menu toggles
    $('.rh-mobile-nav-list .menu-item-has-children > a').on('click', function (e) {
        var linkUrl = $(this).attr('href');

        // If the link is just a placeholder (#) or if we want to force tap to open sub-menu first
        // If we want the link to be clickable but also drop down, we maybe only prevent default if clicking the chevron
        // For simplicity, we toggle sub-menu on link tap, but if it has a real link, we maybe navigate?
        // Let's just toggle and prevent default for sub-menus like common mobile menus
        e.preventDefault();
        $(this).siblings('.sub-menu').slideToggle();
        $(this).toggleClass('submenu-open');

        if ($(this).hasClass('submenu-open')) {
            $(this).css('color', '#0073aa');
        } else {
            $(this).css('color', '#fff');
        }
    });
});
