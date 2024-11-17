<?php

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/* Action */
add_action('admin_enqueue_scripts', 'set_acf_color_palette');
add_action('enqueue_block_editor_assets', 'set_modules');
add_action('wp_enqueue_scripts', 'set_modules');
add_action('wp_enqueue_scripts', 'set_styles_scripts');
add_action('after_setup_theme', 'add_theme_supports');


/* Filter */
//add_filter('acf/settings/show_admin', '__return_false'); // Hide ACF field group menu item
//add_filter('use_block_editor_for_post', '__return_false', 5);
add_filter('big_image_size_threshold', '__return_false');
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

function custom_inline_menu_styles()
{
    global $pagenow;

    if ($pagenow === 'nav-menus.php') {
        echo '<style>#wpbody-content #menu-settings-column{display: block;position:sticky;top: 32px;}</style>';
    }
}

add_action('admin_head', 'custom_inline_menu_styles');

/**
 * Connection admin styles/scripts
 */
function set_acf_color_palette()
{
    $theme_json = WP_Theme_JSON_Resolver::get_merged_data()->get_settings();
    $color_palettes = '';
    foreach ($theme_json['color']['palette']['theme'] as $color) {
        $color_palettes .= '"' . $color['color'] . '",';
    }

    wp_add_inline_script('acf-input', "acf.add_filter('color_picker_args', function (args, field) {args.palettes = [" . trim($color_palettes, ',') . "]; return args;})");
}


function set_modules()
{
    $style_library = array(
        //'swiper' => B_STYLE_URL . '/assets/css/lib/swiper.css'
    );

    $script_library = array(
        //'swiper' => B_TEMP_URL . '/assets/lib/swiper-bundle.min.js'
    );

    if (!empty($style_library)) {
        foreach ($style_library as $handle => $src) {
            wp_register_style($handle, $src, array(), wp_get_theme()->get('Version'));
        }
    }

    if (!empty($script_library)) {
        foreach ($script_library as $handle => $src) {
            wp_register_script($handle, $src, array('jquery'), wp_get_theme()->get('Version'));
        }
    }

    /* *** LOCAL SCRIPTS *** */
    wp_localize_script('jquery', 'wp_ajax',
        array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wpajax-noncecode'),
            'prefix' => B_PREFIX
        )
    );
}

/**
 * Connection styles/scripts
 */
function set_styles_scripts()
{
    /* *** STYLES *** */
    wp_enqueue_style(B_PREFIX . '-style', B_STYLE_URL . '/assets/css/style.css', array(), wp_get_theme()->get('Version'));

    /* *** SCRIPTS *** */
    wp_enqueue_script(B_PREFIX . '-script', B_TEMP_URL . '/assets/js/script.js', array('jquery'), wp_get_theme()->get('Version'), true);
}


/**
 * Register supports
 */
function add_theme_supports()
{
    // Localisation Support
    load_theme_textdomain('html5blank', B_TEMP_PATH . '/languages');

    // Let WordPress manage the document title.
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support('post-thumbnails');

    // Switch default core markup for search form, comment form, and comments to output valid HTML5.
    add_theme_support('html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
    ));

    // Post formats
    add_theme_support('post-formats', array(
        'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'chat', 'audio'
    ));

    add_theme_support('woocommerce');

    // Register navigation menus.
    register_nav_menus(array(
        'header_menu' => __('Menu', 'ld'),
    ));

    // Add image sizes.
    //add_image_size('thumbnail-once', 200, 200, true);

    // Editor styles.
    add_theme_support('editor-styles');

    add_editor_style('assets/css/style-editor.css');
}

/**
 * @param $init
 * @return mixed
 */
function color_palettes_tiny_mce($init)
{
    $theme_json = WP_Theme_JSON_Resolver::get_merged_data()->get_settings();
    $color_palettes = '';
    foreach ($theme_json['color']['palette']['theme'] as $color) {
        $color_palettes .= '"' . preg_replace("/#/", "", $color['color']) . '","' . $color['name'] . '",';
    }

    $init['textcolor_map'] = '[' . $color_palettes . ']';

    $init['textcolor_rows'] = ceil(count($theme_json['color']['palette']['theme']) / 8);

    return $init;
}

add_filter('tiny_mce_before_init', 'color_palettes_tiny_mce');
