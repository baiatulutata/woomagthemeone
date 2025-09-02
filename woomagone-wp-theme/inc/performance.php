<?php
/**
 * Performance Optimization Features
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Enable WebP support
function woomag_theme_one_webp_support() {
    add_filter('wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
        $filetype = wp_check_filetype($filename, $mimes);
        return [
            'ext'             => $filetype['ext'],
            'type'            => $filetype['type'],
            'proper_filename' => $data['proper_filename']
        ];
    }, 10, 4);
}
add_action('init', 'woomag_theme_one_webp_support');

// Add WebP MIME type
function woomag_theme_one_webp_mime($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'woomag_theme_one_webp_mime');

// Lazy load images
function woomag_theme_one_lazy_load_images($content) {
    if (!is_admin() && !is_feed()) {
        $content = preg_replace_callback(
            '/<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>/i',
            function($matches) {
                $img_tag = $matches[0];
                $before_src = $matches[1];
                $src = $matches[2];
                $after_src = $matches[3];

                // Skip if already has loading attribute
                if (strpos($img_tag, 'loading=') !== false) {
                    return $img_tag;
                }

                // Skip if it's a data URI
                if (strpos($src, 'data:') === 0) {
                    return $img_tag;
                }

                return sprintf(
                    '<img%s src="%s" loading="lazy"%s>',
                    $before_src,
                    $src,
                    $after_src
                );
            },
            $content
        );
    }
    return $content;
}
add_filter('the_content', 'woomag_theme_one_lazy_load_images');

// Preload critical resources
function woomag_theme_one_preload_resources() {
    // Preload main font
    echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
    echo '<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"></noscript>';

    // Preload critical CSS
    $css_file = get_template_directory_uri() . '/assets/css/style.css';
    echo '<link rel="preload" href="' . esc_url($css_file) . '" as="style">';
}
add_action('wp_head', 'woomag_theme_one_preload_resources', 1);

// Optimize CSS delivery
function woomag_theme_one_optimize_css() {
    // Add critical CSS inline
    $critical_css = get_template_directory() . '/assets/css/critical.css';
    if (file_exists($critical_css)) {
        echo '<style id="critical-css">';
        echo file_get_contents($critical_css);
        echo '</style>';
    }
}
add_action('wp_head', 'woomag_theme_one_optimize_css', 2);

// Remove unnecessary WordPress features
function woomag_theme_one_cleanup() {
    // Remove emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // Remove unnecessary REST API links
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // Remove Windows Live Writer manifest link
    remove_action('wp_head', 'wlwmanifest_link');
}
add_action('init', 'woomag_theme_one_cleanup');

// Defer non-critical JavaScript
function woomag_theme_one_defer_scripts($tag, $handle, $src) {
    // Don't defer these critical scripts
    $critical_scripts = array('jquery', 'woomagone-theme-script');

    if (in_array($handle, $critical_scripts)) {
        return $tag;
    }

    // Add defer attribute to non-critical scripts
    return str_replace('<script ', '<script defer ', $tag);
}
add_filter('script_loader_tag', 'woomag_theme_one_defer_scripts', 10, 3);

// Add DNS prefetch for external resources
function woomag_theme_one_dns_prefetch() {
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">';
}
add_action('wp_head', 'woomag_theme_one_dns_prefetch', 0);

// Cache busting for development
function woomag_theme_one_cache_busting($src, $handle) {
    if (WP_DEBUG && (strpos($src, get_template_directory_uri()) !== false)) {
        $src = add_query_arg('v', time(), $src);
    }
    return $src;
}
add_filter('style_loader_src', 'woomag_theme_one_cache_busting', 10, 2);
add_filter('script_loader_src', 'woomag_theme_one_cache_busting', 10, 2);

// Optimize database queries
function woomag_theme_one_optimize_queries() {
    // Remove unnecessary meta queries on frontend
    if (!is_admin()) {
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    }
}
add_action('init', 'woomag_theme_one_optimize_queries');

// Add schema.org structured data
function woomag_theme_one_structured_data() {
    if (is_singular('post')) {
        global $post;
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author()
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_site_icon_url()
                )
            )
        );

        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(null, 'large');
        }

        echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
    }
}
add_action('wp_head', 'woomag_theme_one_structured_data');
?>