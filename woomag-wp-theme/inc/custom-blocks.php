<?php
/**
 * Custom Gutenberg Blocks
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register custom blocks
function woomag_theme_one_register_blocks() {
    // Hero Section Block
    wp_register_script(
        'woomagone-hero-block',
        get_template_directory_uri() . '/assets/js/blocks/hero-block.js',
        array('wp-blocks', 'wp-element', 'wp-editor'),
        filemtime(get_template_directory() . '/assets/js/blocks/hero-block.js')
    );

    register_block_type('woomag-theme/hero', array(
        'editor_script' => 'woomagone-hero-block',
        'render_callback' => 'woomag_theme_one_hero_block_render',
    ));

    // Testimonial Block
    wp_register_script(
        'woomagone-testimonial-block',
        get_template_directory_uri() . '/assets/js/blocks/testimonial-block.js',
        array('wp-blocks', 'wp-element', 'wp-editor'),
        filemtime(get_template_directory() . '/assets/js/blocks/testimonial-block.js')
    );

    register_block_type('woomag-theme/testimonial', array(
        'editor_script' => 'woomagone-testimonial-block',
        'render_callback' => 'woomag_theme_one_testimonial_block_render',
    ));

    // Feature Grid Block
    wp_register_script(
        'woomagone-features-block',
        get_template_directory_uri() . '/assets/js/blocks/features-block.js',
        array('wp-blocks', 'wp-element', 'wp-editor'),
        filemtime(get_template_directory() . '/assets/js/blocks/features-block.js')
    );

    register_block_type('woomag-theme/features', array(
        'editor_script' => 'woomagone-features-block',
        'render_callback' => 'woomag_theme_one_features_block_render',
    ));
}
add_action('init', 'woomag_theme_one_register_blocks');

// Hero Block Render
function woomag_theme_one_hero_block_render($attributes) {
    $title = isset($attributes['title']) ? $attributes['title'] : 'Hero Title';
    $subtitle = isset($attributes['subtitle']) ? $attributes['subtitle'] : 'Hero subtitle text';
    $buttonText = isset($attributes['buttonText']) ? $attributes['buttonText'] : 'Learn More';
    $buttonUrl = isset($attributes['buttonUrl']) ? $attributes['buttonUrl'] : '#';
    $backgroundImage = isset($attributes['backgroundImage']) ? $attributes['backgroundImage'] : '';

    ob_start();
    ?>
    <div class="hero-block relative bg-gray-900 text-white py-20"
        <?php if ($backgroundImage): ?>
            style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url(<?php echo esc_url($backgroundImage); ?>); background-size: cover; background-position: center;"
        <?php endif; ?>>
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6"><?php echo esc_html($title); ?></h1>
            <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-90"><?php echo esc_html($subtitle); ?></p>
            <a href="<?php echo esc_url($buttonUrl); ?>"
               class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-bold py-4 px-8 rounded-lg text-lg transition-colors duration-200">
                <?php echo esc_html($buttonText); ?>
            </a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Testimonial Block Render
function woomag_theme_one_testimonial_block_render($attributes) {
    $quote = isset($attributes['quote']) ? $attributes['quote'] : 'This is an amazing testimonial quote.';
    $author = isset($attributes['author']) ? $attributes['author'] : 'John Doe';
    $position = isset($attributes['position']) ? $attributes['position'] : 'CEO, Company';
    $avatar = isset($attributes['avatar']) ? $attributes['avatar'] : '';

    ob_start();
    ?>
    <div class="testimonial-block bg-white p-8 rounded-lg shadow-lg">
        <blockquote class="text-lg italic text-gray-700 mb-6">
            "<?php echo esc_html($quote); ?>"
        </blockquote>
        <div class="flex items-center">
            <?php if ($avatar): ?>
                <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($author); ?>" class="w-12 h-12 rounded-full mr-4">
            <?php endif; ?>
            <div>
                <div class="font-semibold text-gray-900"><?php echo esc_html($author); ?></div>
                <div class="text-gray-600 text-sm"><?php echo esc_html($position); ?></div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Features Block Render
function woomag_theme_one_features_block_render($attributes) {
    $features = isset($attributes['features']) ? $attributes['features'] : array();

    if (empty($features)) {
        $features = array(
            array('title' => 'Feature 1', 'description' => 'Feature description', 'icon' => '⭐'),
            array('title' => 'Feature 2', 'description' => 'Feature description', 'icon' => '🚀'),
            array('title' => 'Feature 3', 'description' => 'Feature description', 'icon' => '💎'),
        );
    }

    ob_start();
    ?>
    <div class="features-block py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($features as $feature): ?>
                <div class="feature-item text-center p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="feature-icon text-4xl mb-4"><?php echo esc_html($feature['icon']); ?></div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-900"><?php echo esc_html($feature['title']); ?></h3>
                    <p class="text-gray-600"><?php echo esc_html($feature['description']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Block category
function woomag_theme_one_block_categories($categories) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'woomag-theme',
                'title' => __('Modern Theme', 'woomag-theme'),
            ),
        )
    );
}
add_filter('block_categories_all', 'woomag_theme_one_block_categories', 10, 2);
?>