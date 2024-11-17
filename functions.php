<?php

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

$theme_json = WP_Theme_JSON_Resolver::get_merged_data()->get_settings();

/**
 * Define constants.
 */
if (!defined('B_PREFIX')) define('B_PREFIX', $theme_json['custom']['prefix']);
if (!defined('B_TEMP_PATH')) define('B_TEMP_PATH', get_template_directory());
if (!defined('B_TEMP_URL')) define('B_TEMP_URL', get_template_directory_uri());
if (!defined('B_STYLE_PATH')) define('B_STYLE_PATH', get_stylesheet_directory());
if (!defined('B_STYLE_URL')) define('B_STYLE_URL', get_stylesheet_directory_uri());


function add_block_category($categories, $post)
{
    return array_merge(
        array(
            array(
                'slug'  => 'beyond-category',
                'title' => __('Beyond Blocks', B_PREFIX)
            )
        ),
        $categories
    );
}
add_filter('block_categories_all', 'add_block_category', 10, 2);

function register_acf_blocks()
{
    $subdirectories = glob(__DIR__ . '/blocks/*', GLOB_ONLYDIR);

    foreach ($subdirectories as $subdirectory) {
        register_block_type($subdirectory);
    }
}

add_action('init', 'register_acf_blocks');

include_once('include/helper-functions.php');
include_once('include/action-config.php');

if (class_exists('acf')) {
    include_once('acf/menu-item-depth.php');
}

function include_field_types()
{
    if (class_exists('acf')) {
        include_once('acf/field-image-selector.php');
    }
}

add_action('acf/include_field_types', 'include_field_types');
