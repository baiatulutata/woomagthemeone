<?php
/**
 * Custom Block Styles for WoomagOne
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register custom block styles
function woomag_theme_one_register_block_styles() {
    // Button styles
    register_block_style('core/button', array(
        'name'  => 'outline',
        'label' => __('Outline', 'woomag-theme'),
    ));

    register_block_style('core/button', array(
        'name'  => 'gradient',
        'label' => __('Gradient', 'woomag-theme'),
    ));

    register_block_style('core/button', array(
        'name'  => 'rounded-full',
        'label' => __('Rounded Full', 'woomag-theme'),
    ));

    // Quote styles
    register_block_style('core/quote', array(
        'name'  => 'modern',
        'label' => __('Modern', 'woomag-theme'),
    ));

    register_block_style('core/quote', array(
        'name'  => 'highlight',
        'label' => __('Highlight', 'woomag-theme'),
    ));

    // Cover styles
    register_block_style('core/cover', array(
        'name'  => 'rounded',
        'label' => __('Rounded', 'woomag-theme'),
    ));

    register_block_style('core/cover', array(
        'name'  => 'bordered',
        'label' => __('Bordered', 'woomag-theme'),
    ));

    // Group styles
    register_block_style('core/group', array(
        'name'  => 'card',
        'label' => __('Card', 'woomag-theme'),
    ));

    register_block_style('core/group', array(
        'name'  => 'shadow',
        'label' => __('Shadow', 'woomag-theme'),
    ));

    register_block_style('core/group', array(
        'name'  => 'bordered',
        'label' => __('Bordered', 'woomag-theme'),
    ));

    // Image styles
    register_block_style('core/image', array(
        'name'  => 'rounded',
        'label' => __('Rounded', 'woomag-theme'),
    ));

    register_block_style('core/image', array(
        'name'  => 'shadow',
        'label' => __('Shadow', 'woomag-theme'),
    ));

    register_block_style('core/image', array(
        'name'  => 'bordered',
        'label' => __('Bordered', 'woomag-theme'),
    ));

    // Columns styles
    register_block_style('core/columns', array(
        'name'  => 'card-grid',
        'label' => __('Card Grid', 'woomag-theme'),
    ));

    register_block_style('core/columns', array(
        'name'  => 'feature-grid',
        'label' => __('Feature Grid', 'woomag-theme'),
    ));

    // Heading styles
    register_block_style('core/heading', array(
        'name'  => 'gradient-text',
        'label' => __('Gradient Text', 'woomag-theme'),
    ));

    register_block_style('core/heading', array(
        'name'  => 'underlined',
        'label' => __('Underlined', 'woomag-theme'),
    ));

    // Paragraph styles
    register_block_style('core/paragraph', array(
        'name'  => 'highlight',
        'label' => __('Highlight', 'woomag-theme'),
    ));

    register_block_style('core/paragraph', array(
        'name'  => 'large-text',
        'label' => __('Large Text', 'woomag-theme'),
    ));

    // List styles
    register_block_style('core/list', array(
        'name'  => 'checkmarks',
        'label' => __('Checkmarks', 'woomag-theme'),
    ));

    register_block_style('core/list', array(
        'name'  => 'no-bullets',
        'label' => __('No Bullets', 'woomag-theme'),
    ));
}
add_action('init', 'woomag_theme_one_register_block_styles');

// Add CSS for custom block styles
function woomag_theme_one_block_styles_css() {
    ?>
    <style id="woomag-block-styles">
        /* Button Styles */
        .wp-block-button.is-style-outline .wp-block-button__link {
            @apply bg-transparent border-2 border-current text-primary-600 hover:bg-primary-600 hover:text-white transition-all duration-300;
        }

        .wp-block-button.is-style-gradient .wp-block-button__link {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            @apply border-0 text-white;
        }

        .wp-block-button.is-style-rounded-full .wp-block-button__link {
            @apply rounded-full px-8;
        }

        /* Quote Styles */
        .wp-block-quote.is-style-modern {
            @apply border-l-4 border-primary-500 pl-6 bg-gray-50 py-4 rounded-r-lg relative;
        }

        .wp-block-quote.is-style-modern::before {
            content: '"';
            @apply text-6xl text-primary-200 absolute -top-2 -left-2 font-serif leading-none;
        }

        .wp-block-quote.is-style-highlight {
            @apply bg-primary-50 border border-primary-200 p-6 rounded-lg relative;
        }

        .wp-block-quote.is-style-highlight::after {
            content: '';
            @apply absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary-400 to-primary-600;
        }

        /* Cover Styles */
        .wp-block-cover.is-style-rounded {
            @apply rounded-2xl overflow-hidden;
        }

        .wp-block-cover.is-style-bordered {
            @apply border-4 border-white rounded-lg overflow-hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Group Styles */
        .wp-block-group.is-style-card {
            @apply bg-white p-6 rounded-lg shadow-md border border-gray-100 hover:shadow-lg transition-shadow duration-300;
        }

        .wp-block-group.is-style-shadow {
            @apply shadow-xl rounded-lg p-6 border border-gray-50;
        }

        .wp-block-group.is-style-bordered {
            @apply border-2 border-primary-200 rounded-lg p-6 bg-primary-25;
        }

        /* Image Styles */
        .wp-block-image.is-style-rounded img {
            @apply rounded-xl;
        }

        .wp-block-image.is-style-shadow img {
            @apply shadow-lg rounded-lg;
        }

        .wp-block-image.is-style-bordered img {
            @apply border-4 border-white rounded-lg;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Columns Styles */
        .wp-block-columns.is-style-card-grid .wp-block-column {
            @apply bg-white p-6 rounded-lg shadow-md mx-2 mb-4 border border-gray-100 hover:shadow-lg transition-shadow duration-300;
        }

        .wp-block-columns.is-style-feature-grid .wp-block-column {
            @apply text-center p-8 rounded-lg bg-gradient-to-br from-gray-50 to-white border border-gray-200 mx-2 mb-4 hover:border-primary-300 transition-colors duration-300;
        }

        /* Heading Styles */
        .wp-block-heading.is-style-gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .wp-block-heading.is-style-underlined {
            @apply relative pb-4;
        }

        .wp-block-heading.is-style-underlined::after {
            content: '';
            @apply absolute bottom-0 left-0 w-16 h-1 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full;
        }

        /* Paragraph Styles */
        .wp-block-paragraph.is-style-highlight {
            @apply bg-yellow-100 border-l-4 border-yellow-400 p-4 rounded-r-lg;
        }

        .wp-block-paragraph.is-style-large-text {
            @apply text-xl leading-relaxed text-gray-700;
        }

        /* List Styles */
        .wp-block-list.is-style-checkmarks li {
            @apply relative pl-8 list-none;
        }

        .wp-block-list.is-style-checkmarks li::before {
            content: '✓';
            @apply absolute left-0 text-green-500 font-bold text-lg;
        }

        .wp-block-list.is-style-no-bullets {
            @apply list-none;
        }

        .wp-block-list.is-style-no-bullets li {
            @apply pl-4 border-l-2 border-gray-200 mb-3;
        }

        /* Dark mode adjustments */
        .dark .wp-block-quote.is-style-modern {
            @apply bg-gray-800 border-primary-400;
        }

        .dark .wp-block-quote.is-style-highlight {
            @apply bg-primary-900 border-primary-700;
        }

        .dark .wp-block-group.is-style-card {
            @apply bg-gray-800 border-gray-700;
        }

        .dark .wp-block-group.is-style-shadow {
            @apply bg-gray-800 border-gray-700;
        }

        .dark .wp-block-columns.is-style-card-grid .wp-block-column {
            @apply bg-gray-800 border-gray-700;
        }

        .dark .wp-block-columns.is-style-feature-grid .wp-block-column {
            @apply bg-gray-800 border-gray-700;
        }

        .dark .wp-block-paragraph.is-style-highlight {
            @apply bg-yellow-900 border-yellow-600 text-yellow-100;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .wp-block-columns.is-style-card-grid .wp-block-column,
            .wp-block-columns.is-style-feature-grid .wp-block-column {
                @apply mx-0;
            }
        }
    </style>
    <?php
}
add_action('wp_head', 'woomag_theme_one_block_styles_css');

// Enqueue block editor styles
function woomag_theme_one_block_editor_styles() {
    wp_enqueue_style(
        'woomag-block-editor-styles',
        get_template_directory_uri() . '/assets/css/block-editor.css',
        array(),
        wp_get_theme()->get('Version')
    );
}
add_action('enqueue_block_editor_assets', 'woomag_theme_one_block_editor_styles');
?>