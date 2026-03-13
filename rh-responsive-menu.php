<?php
/**
 * Plugin Name: RH Mobile Menu
 * Description: A customizable responsive mobile menu plugin.
 * Version: 1.0.0
 * Author: Rokibul Hasan
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class RH_Responsive_Menu
{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'render_menu'));
    }

    public function add_admin_menu()
    {
        add_submenu_page(
            'options-general.php',
            'Mobile Menu Settings',
            'Mobile Menu',
            'manage_options',
            'rh-mobile-menu',
            array($this, 'settings_page_html')
        );
    }

    public function register_settings()
    {
        register_setting('rh_mobile_menu_options_group', 'rh_mobile_menu_selected');
        register_setting('rh_mobile_menu_options_group', 'rh_mobile_menu_breakpoint');
        register_setting('rh_mobile_menu_options_group', 'rh_mobile_menu_position');
        register_setting('rh_mobile_menu_options_group', 'rh_mobile_menu_top');
        register_setting('rh_mobile_menu_options_group', 'rh_mobile_menu_x_align');
        register_setting('rh_mobile_menu_options_group', 'rh_mobile_menu_x_distance');
    }

    public function settings_page_html()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $menus = wp_get_nav_menus();
        $selected_menu = get_option('rh_mobile_menu_selected', '');
        $breakpoint = get_option('rh_mobile_menu_breakpoint', '768');
        $position = get_option('rh_mobile_menu_position', 'fixed');
        $top = get_option('rh_mobile_menu_top', '20');
        $x_align = get_option('rh_mobile_menu_x_align', 'right');
        $x_distance = get_option('rh_mobile_menu_x_distance', '20');

        ?>
        <div class="wrap">
            <h1>
                <?php echo esc_html(get_admin_page_title()); ?>
            </h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('rh_mobile_menu_options_group');
                do_settings_sections('rh_mobile_menu_options_group');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="rh_mobile_menu_selected">Select Menu</label></th>
                        <td>
                            <select name="rh_mobile_menu_selected" id="rh_mobile_menu_selected">
                                <option value="">-- Select a Menu --</option>
                                <?php foreach ($menus as $menu): ?>
                                    <option value="<?php echo esc_attr($menu->term_id); ?>" <?php selected($selected_menu, $menu->term_id); ?>>
                                        <?php echo esc_html($menu->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description">Select the WordPress menu you want to display on mobile devices.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="rh_mobile_menu_breakpoint">Mobile Breakpoint (px)</label></th>
                        <td>
                            <input type="number" name="rh_mobile_menu_breakpoint" id="rh_mobile_menu_breakpoint"
                                value="<?php echo esc_attr($breakpoint); ?>" />
                            <p class="description">Screen width in pixels below which the mobile menu will appear (e.g., 768).
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="rh_mobile_menu_position">Button Position Type</label></th>
                        <td>
                            <select name="rh_mobile_menu_position" id="rh_mobile_menu_position">
                                <option value="fixed" <?php selected($position, 'fixed'); ?>>Fixed</option>
                                <option value="absolute" <?php selected($position, 'absolute'); ?>>Absolute</option>
                            </select>
                            <p class="description">Choose whether the menu button should stay fixed on screen or be absolute.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="rh_mobile_menu_top">Button Top (px)</label></th>
                        <td>
                            <input type="number" name="rh_mobile_menu_top" id="rh_mobile_menu_top"
                                value="<?php echo esc_attr($top); ?>" />
                            <p class="description">Distance from the top of the screen in pixels.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="rh_mobile_menu_x_align">Horizontal Alignment</label></th>
                        <td>
                            <select name="rh_mobile_menu_x_align" id="rh_mobile_menu_x_align">
                                <option value="left" <?php selected($x_align, 'left'); ?>>Left</option>
                                <option value="right" <?php selected($x_align, 'right'); ?>>Right</option>
                            </select>
                            <p class="description">Choose whether the menu button should align to the left or right side.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="rh_mobile_menu_x_distance">Horizontal Distance (px)</label></th>
                        <td>
                            <input type="number" name="rh_mobile_menu_x_distance" id="rh_mobile_menu_x_distance"
                                value="<?php echo esc_attr($x_distance); ?>" />
                            <p class="description">Distance from the selected side (left/right) in pixels.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style('rh-mobile-menu-style', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), '1.0.0');
        wp_enqueue_script('rh-mobile-menu-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), '1.0.0', true);

        $breakpoint = get_option('rh_mobile_menu_breakpoint', '768');
        $position = get_option('rh_mobile_menu_position', 'fixed');
        $top = get_option('rh_mobile_menu_top', '20');
        $x_align = get_option('rh_mobile_menu_x_align', 'right');
        $x_distance = get_option('rh_mobile_menu_x_distance', '20');

        $other_side = ($x_align === 'left') ? 'right' : 'left';

        $custom_css = '
            #rh-mobile-menu-button {
                position: ' . esc_attr($position) . ' !important;
                top: ' . intval($top) . 'px !important;
                ' . esc_attr($x_align) . ': ' . intval($x_distance) . 'px !important;
                ' . esc_attr($other_side) . ': auto !important;
            }
            @media (min-width: ' . (intval($breakpoint) + 1) . 'px) {
                #rh-mobile-menu-button { display: none !important; }
                #rh-mobile-menu-container { display: none !important; }
            }
        ';
        wp_add_inline_style('rh-mobile-menu-style', $custom_css);
    }

    public function render_menu()
    {
        $selected_menu = get_option('rh_mobile_menu_selected', '');

        if (empty($selected_menu)) {
            return;
        }

        ?>
        <div id="rh-mobile-menu-button">
            <span class="rh-hamburger-line"></span>
            <span class="rh-hamburger-line"></span>
            <span class="rh-hamburger-line"></span>
        </div>

        <div id="rh-mobile-menu-overlay"></div>

        <div id="rh-mobile-menu-container">
            <div class="rh-mobile-menu-header">
                <span id="rh-mobile-menu-close">&times;</span>
            </div>
            <div class="rh-mobile-menu-content">
                <?php
                wp_nav_menu(array(
                    'menu' => $selected_menu,
                    'container' => false,
                    'menu_class' => 'rh-mobile-nav-list',
                    'fallback_cb' => false,
                ));
                ?>
            </div>
        </div>
        <?php
    }
}

new RH_Responsive_Menu();
