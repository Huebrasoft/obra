<?php
if (!defined('ABSPATH')) {
    exit;
}

function mavisal_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('menus');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);

    register_nav_menus([
        'primary' => __('Menú Principal', 'mavisal-theme'),
    ]);
}
add_action('after_setup_theme', 'mavisal_theme_setup');

function mavisal_enqueue_assets() {
    wp_enqueue_style('mavisal-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Montserrat:wght@500;600;700;800&display=swap', [], null);
    wp_enqueue_style('mavisal-main', get_template_directory_uri() . '/assets/css/main.css', [], wp_get_theme()->get('Version'));
    wp_enqueue_script('mavisal-main', get_template_directory_uri() . '/assets/js/main.js', [], wp_get_theme()->get('Version'), true);
}
add_action('wp_enqueue_scripts', 'mavisal_enqueue_assets');

function mavisal_register_cpts() {
    register_post_type('servicios', [
        'labels' => ['name' => __('Servicios', 'mavisal-theme'), 'singular_name' => __('Servicio', 'mavisal-theme')],
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-hammer',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);

    register_post_type('proyectos', [
        'labels' => ['name' => __('Proyectos', 'mavisal-theme'), 'singular_name' => __('Proyecto', 'mavisal-theme')],
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-building',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);

    register_post_type('testimonios', [
        'labels' => ['name' => __('Testimonios', 'mavisal-theme'), 'singular_name' => __('Testimonio', 'mavisal-theme')],
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-format-quote',
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'mavisal_register_cpts');

function mavisal_get_field($key, $default = '') {
    if (function_exists('get_field')) {
        $value = get_field($key);
        return (!empty($value) || $value === '0') ? $value : $default;
    }
    return $default;
}

function mavisal_nav_link_class($atts, $item, $args) {
    if (isset($args->theme_location) && 'primary' === $args->theme_location) {
        $atts['class'] = 'text-gray-200 hover:text-mavisal-orange transition font-medium text-sm block px-3 py-3 md:p-0';
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'mavisal_nav_link_class', 10, 3);

function mavisal_nav_li_class($classes, $item, $args) {
    if (isset($args->theme_location) && 'primary' === $args->theme_location) {
        $classes[] = 'border-b border-gray-800 md:border-none';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'mavisal_nav_li_class', 10, 3);
