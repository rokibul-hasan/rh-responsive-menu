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

    // Append toggle button to items with sub-menus
    $('.rh-mobile-nav-list .menu-item-has-children > a').after('<span class="rh-submenu-toggle"></span>');

    // Handle sub-menu toggles via the new button
    $('.rh-submenu-toggle').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $toggle = $(this);
        var $subMenu = $toggle.siblings('.sub-menu');
        
        $subMenu.slideToggle();
        $toggle.toggleClass('submenu-open');
    });
});
