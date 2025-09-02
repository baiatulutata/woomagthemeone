<?php
/**
 * Theme Functions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function woomag_theme_one_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('custom-background');
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    add_editor_style('assets/css/style.css');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'modern-theme'),
        'footer'  => __('Footer Menu', 'modern-theme'),
    ));
}
add_action('after_setup_theme', 'woomag_theme_one_setup');

// Enqueue scripts and styles
function woomag_theme_one_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style(
        'modern-theme-style',
        get_template_directory_uri() . '/assets/css/style.css',
        array(),
        filemtime(get_template_directory() . '/assets/css/style.css') // Cache busting
    );

    // Enqueue main JavaScript
    wp_enqueue_script(
        'modern-theme-script',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        filemtime(get_template_directory() . '/assets/js/main.js'), // Cache busting
        true
    );

    // Localize script for AJAX
    wp_localize_script('modern-theme-script', 'woomag_theme_one_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('woomag_theme_one_nonce')
    ));

    // Load comment reply script when needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'woomag_theme_one_scripts');

// Register widget areas
function woomag_theme_one_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Widgets', 'modern-theme'),
        'id'            => 'footer-widgets',
        'description'   => __('Add widgets here to appear in your footer.', 'modern-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title text-lg font-semibold mb-4">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'woomag_theme_one_widgets_init');

// Custom walker for navigation menu
class Modern_Walker_Nav_Menu extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden group-hover:block\">\n";
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . ' group relative"' : ' class="group relative"';

        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names .'>';

        $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';

        $link_class = ($depth == 0) ? 'text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200' : 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100';

        $item_output = $args->before ?? '';
        $item_output .= '<a' . $attributes . ' class="' . $link_class . '">';
        $item_output .= ($args->link_before ?? '') . apply_filters('the_title', $item->title, $item->ID) . ($args->link_after ?? '');
        $item_output .= '</a>';
        $item_output .= $args->after ?? '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}

// Breadcrumb function
function woomag_theme_one_breadcrumb() {
    if (is_front_page()) return;

    echo '<nav class="breadcrumb container mx-auto px-4 py-4" aria-label="Breadcrumb">';
    echo '<a href="' . home_url('/') . '" class="hover:text-primary-600">' . __('Home', 'modern-theme') . '</a>';
    echo '<span class="breadcrumb-separator mx-2">/</span>';

    if (is_category() || is_single()) {
        if (is_single()) {
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                echo '<a href="' . get_category_link($category->term_id) . '" class="hover:text-primary-600">' . $category->name . '</a>';
                echo '<span class="breadcrumb-separator mx-2">/</span>';
            }
            echo '<span class="text-gray-500">' . get_the_title() . '</span>';
        } else {
            echo '<span class="text-gray-500">' . single_cat_title('', false) . '</span>';
        }
    } elseif (is_page()) {
        if ($post->post_parent) {
            $ancestors = get_post_ancestors($post);
            $ancestors = array_reverse($ancestors);

            foreach ($ancestors as $ancestor) {
                echo '<a href="' . get_permalink($ancestor) . '" class="hover:text-primary-600">' . get_the_title($ancestor) . '</a>';
                echo '<span class="breadcrumb-separator mx-2">/</span>';
            }
        }
        echo '<span class="text-gray-500">' . get_the_title() . '</span>';
    } elseif (is_search()) {
        echo '<span class="text-gray-500">' . __('Search Results for: ', 'modern-theme') . get_search_query() . '</span>';
    } elseif (is_tag()) {
        echo '<span class="text-gray-500">' . single_tag_title('', false) . '</span>';
    } elseif (is_archive()) {
        echo '<span class="text-gray-500">' . get_the_archive_title() . '</span>';
    } elseif (is_404()) {
        echo '<span class="text-gray-500">' . __('404 Not Found', 'modern-theme') . '</span>';
    }

    echo '</nav>';
}

// Custom excerpt length
function woomag_theme_one_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'woomag_theme_one_excerpt_length', 999);

// Custom excerpt more
function woomag_theme_one_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'woomag_theme_one_excerpt_more');

// Add custom image sizes
function woomag_theme_one_image_sizes() {
    add_image_size('hero-image', 1920, 600, true);
    add_image_size('card-image', 400, 300, true);
}
add_action('after_setup_theme', 'woomag_theme_one_image_sizes');

// Customizer settings
function woomag_theme_one_customize_register($wp_customize) {
    // Header Image Section
    $wp_customize->add_section('header_image_section', array(
        'title'    => __('Header Image', 'modern-theme'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('header_image_setting', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'header_image_control', array(
        'label'    => __('Upload Header Image', 'modern-theme'),
        'section'  => 'header_image_section',
        'settings' => 'header_image_setting',
    )));

    // Colors Section
    $wp_customize->add_setting('primary_color', array(
        'default'           => '#3b82f6',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color_control', array(
        'label'    => __('Primary Color', 'modern-theme'),
        'section'  => 'colors',
        'settings' => 'primary_color',
    )));
}
add_action('customize_register', 'woomag_theme_one_customize_register');

// Output custom CSS
function woomag_theme_one_custom_css() {
    $primary_color = get_theme_mod('primary_color', '#3b82f6');
    ?>
    <style type="text/css">
        :root {
            --primary-color: <?php echo esc_attr($primary_color); ?>;
        }
        .text-primary-600,
        .hover\:text-primary-600:hover {
            color: var(--primary-color);
        }
        .bg-primary-600 {
            background-color: var(--primary-color);
        }
        .border-primary-500 {
            border-color: var(--primary-color);
        }
    </style>
    <?php
}
add_action('wp_head', 'woomag_theme_one_custom_css');

// Theme activation hook
function woomag_theme_one_after_switch_theme() {
    // Create default menu if none exists
    $menu_name = 'Primary Menu';
    $menu_exists = wp_get_nav_menu_object($menu_name);

    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);

        // Add some default menu items
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title'  => __('Home'),
            'menu-item-classes' => 'home',
            'menu-item-url'     => home_url('/'),
            'menu-item-status'  => 'publish'
        ));

        // Set the menu to primary location
        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
}
add_action('after_switch_theme', 'woomag_theme_one_after_switch_theme');

// Remove WordPress version from head
remove_action('wp_head', 'wp_generator');

// Clean up WordPress head
function woomag_theme_one_head_cleanup() {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
}
add_action('init', 'woomag_theme_one_head_cleanup');


// Debug function - add temporarily to check if files exist
function woomag_theme_one_debug_assets() {
    if (current_user_can('administrator')) {
        $css_path = get_template_directory() . '/assets/css/style.css';
        $js_path = get_template_directory() . '/assets/js/main.js';

        echo '<!-- DEBUG INFO -->';
        echo '<!-- CSS Path: ' . $css_path . ' -->';
        echo '<!-- CSS Exists: ' . (file_exists($css_path) ? 'YES' : 'NO') . ' -->';
        echo '<!-- JS Path: ' . $js_path . ' -->';
        echo '<!-- JS Exists: ' . (file_exists($js_path) ? 'YES' : 'NO') . ' -->';

        if (file_exists($css_path)) {
            echo '<!-- CSS Size: ' . filesize($css_path) . ' bytes -->';
            echo '<!-- CSS Modified: ' . date('Y-m-d H:i:s', filemtime($css_path)) . ' -->';
        }
    }
}
add_action('wp_head', 'woomag_theme_one_debug_assets');

// Alternative enqueue method with higher priority
function woomag_theme_one_force_styles() {
    $css_file = get_template_directory() . '/assets/css/style.css';

    if (file_exists($css_file)) {
        wp_enqueue_style(
            'modern-theme-tailwind',
            get_template_directory_uri() . '/assets/css/style.css',
            array(),
            filemtime($css_file),
            'all'
        );
    } else {
        // Fallback: try to load from src directly (for development)
        wp_enqueue_style(
            'modern-theme-tailwind-fallback',
            get_template_directory_uri() . '/src/css/style.css',
            array(),
            time(),
            'all'
        );
    }
}
add_action('wp_enqueue_scripts', 'woomag_theme_one_force_styles', 5);
?>