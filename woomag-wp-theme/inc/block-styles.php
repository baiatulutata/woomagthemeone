<?php
/**
 * Custom Block Styles
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

    // Quote styles
    register_block_style('core/quote', array(
        'name'  => 'modern',
        'label' => __('Modern', 'woomag-theme'),
    ));

    // Cover styles
    register_block_style('core/cover', array(
        'name'  => 'rounded',
        'label' => __('Rounded', 'woomag-theme'),
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

    // Image styles
    register_block_style('core/image', array(
        'name'  => 'rounded',
        'label' => __('Rounded', 'woomag-theme'),
    ));

    register_block_style('core/image', array(
        'name'  => 'shadow',
        'label' => __('Shadow', 'woomag-theme'),
    ));

    // Columns styles
    register_block_style('core/columns', array(
        'name'  => 'card-grid',
        'label' => __('Card Grid', 'woomag-theme'),
    ));
}
add_action('init', 'woomag_theme_one_register_block_styles');

// Add CSS for custom block styles
function woomag_theme_one_block_styles_css() {
    ?>
    <style>
        /* Button Outline Style */
        .wp-block-button.is-style-outline .wp-block-button__link {
            @apply bg-transparent border-2 border-current text-primary-600 hover:bg-primary-600 hover:text-white;
        }

        /* Button Gradient Style */
        .wp-block-button.is-style-gradient .wp-block-button__link {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            @apply border-0;
        }

        /* Modern Quote Style */
        .wp-block-quote.is-style-modern {
            @apply border-l-4 border-primary-500 pl-6 bg-gray-50 py-4 rounded-r-lg;
        }

        /* Rounded Cover Style */
        .wp-block-cover.is-style-rounded {
            @apply rounded-2xl overflow-hidden;
        }

        /* Card Group Style */
        .wp-block-group.is-style-card {
            @apply bg-white p-6 rounded-lg shadow-md border border-gray-100;
        }

        /* Shadow Group Style */
        .wp-block-group.is-style-shadow {
            @apply shadow-lg rounded-lg p-6;
        }

        /* Rounded Image Style */
        .wp-block-image.is-style-rounded img {
            @apply rounded-lg;
        }

        /* Shadow Image Style */
        .wp-block-image.is-style-shadow img {
            @apply shadow-lg rounded-lg;
        }

        /* Card Grid Columns Style */
        .wp-block-columns.is-style-card-grid .wp-block-column {
            @apply bg-white p-6 rounded-lg shadow-md mx-2 mb-4;
        }
    </style>
    <?php
}
add_action('wp_head', 'woomag_theme_one_block_styles_css');
?>