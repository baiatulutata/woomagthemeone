<?php
/**
 * Pricing Tables Block
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register Pricing Tables Block
function woomag_theme_one_register_pricing_tables_block() {
    wp_register_script(
        'woomag-pricing-tables-block',
        get_template_directory_uri() . '/assets/js/blocks/pricing-tables-block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components'),
        filemtime(get_template_directory() . '/assets/js/blocks/pricing-tables-block.js')
    );

    register_block_type('woomag-theme/pricing-tables', array(
        'editor_script' => 'woomag-pricing-tables-block',
        'render_callback' => 'woomag_theme_one_pricing_tables_render',
        'attributes' => array(
            'title' => array(
                'type' => 'string',
                'default' => 'Choose Your Plan'
            ),
            'subtitle' => array(
                'type' => 'string',
                'default' => 'Select the perfect plan for your needs'
            ),
            'billingToggle' => array(
                'type' => 'boolean',
                'default' => true
            ),
            'currency' => array(
                'type' => 'string',
                'default' => '$'
            ),
            'style' => array(
                'type' => 'string',
                'default' => 'modern'
            ),
            'columns' => array(
                'type' => 'number',
                'default' => 3
            ),
            'plans' => array(
                'type' => 'array',
                'default' => array()
            )
        )
    ));
}
add_action('init', 'woomag_theme_one_register_pricing_tables_block');

// Pricing Tables Block Render
function woomag_theme_one_pricing_tables_render($attributes) {
    $title = isset($attributes['title']) ? $attributes['title'] : 'Choose Your Plan';
    $subtitle = isset($attributes['subtitle']) ? $attributes['subtitle'] : 'Select the perfect plan for your needs';
    $billing_toggle = isset($attributes['billingToggle']) ? $attributes['billingToggle'] : true;
    $currency = isset($attributes['currency']) ? $attributes['currency'] : '$';
    $style = isset($attributes['style']) ? $attributes['style'] : 'modern';
    $columns = isset($attributes['columns']) ? $attributes['columns'] : 3;
    $plans = isset($attributes['plans']) ? $attributes['plans'] : array();

    // Default plans if none provided
    if (empty($plans)) {
        $plans = array(
            array(
                'name' => 'Starter',
                'description' => 'Perfect for individuals and small projects',
                'monthlyPrice' => 9,
                'yearlyPrice' => 90,
                'featured' => false,
                'badge' => '',
                'features' => array(
                    array('text' => '5 Projects', 'included' => true),
                    array('text' => '10GB Storage', 'included' => true),
                    array('text' => 'Email Support', 'included' => true),
                    array('text' => 'Advanced Analytics', 'included' => false),
                    array('text' => 'Priority Support', 'included' => false),
                ),
                'buttonText' => 'Get Started',
                'buttonUrl' => '#',
                'buttonStyle' => 'secondary'
            ),
            array(
                'name' => 'Professional',
                'description' => 'Great for growing businesses and teams',
                'monthlyPrice' => 29,
                'yearlyPrice' => 290,
                'featured' => true,
                'badge' => 'Most Popular',
                'features' => array(
                    array('text' => '50 Projects', 'included' => true),
                    array('text' => '100GB Storage', 'included' => true),
                    array('text' => 'Email Support', 'included' => true),
                    array('text' => 'Advanced Analytics', 'included' => true),
                    array('text' => 'Priority Support', 'included' => true),
                ),
                'buttonText' => 'Start Free Trial',
                'buttonUrl' => '#',
                'buttonStyle' => 'primary'
            ),
            array(
                'name' => 'Enterprise',
                'description' => 'For large organizations with custom needs',
                'monthlyPrice' => 99,
                'yearlyPrice' => 990,
                'featured' => false,
                'badge' => '',
                'features' => array(
                    array('text' => 'Unlimited Projects', 'included' => true),
                    array('text' => '1TB Storage', 'included' => true),
                    array('text' => '24/7 Phone Support', 'included' => true),
                    array('text' => 'Advanced Analytics', 'included' => true),
                    array('text' => 'Custom Integrations', 'included' => true),
                ),
                'buttonText' => 'Contact Sales',
                'buttonUrl' => '#',
                'buttonStyle' => 'secondary'
            )
        );
    }

    // Column classes based on number
    $grid_classes = array(
        1 => 'grid-cols-1',
        2 => 'grid-cols-1 md:grid-cols-2',
        3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4'
    );

    $grid_class = isset($grid_classes[$columns]) ? $grid_classes[$columns] : $grid_classes[3];

    // Style classes
    $style_classes = array(
        'modern' => 'bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100',
        'minimal' => 'bg-white rounded-lg border-2 border-gray-200 hover:border-primary-300 transition-all duration-300',
        'gradient' => 'bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-md hover:shadow-lg transition-all duration-300',
        'outlined' => 'bg-white rounded-lg border-2 border-primary-200 hover:shadow-lg transition-all duration-300'
    );

    $base_card_class = isset($style_classes[$style]) ? $style_classes[$style] : $style_classes['modern'];

    // Generate unique ID for billing toggle
    $toggle_id = 'pricing-toggle-' . uniqid();

    ob_start();
    ?>
    <div class="pricing-tables-block py-12 md:py-16">
        <div class="container mx-auto px-4">

            <!-- Header -->
            <?php if (!empty($title) || !empty($subtitle) || $billing_toggle): ?>
                <div class="text-center mb-12">
                    <?php if (!empty($title)): ?>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            <?php echo esc_html($title); ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($subtitle)): ?>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                            <?php echo esc_html($subtitle); ?>
                        </p>
                    <?php endif; ?>

                    <!-- Billing Toggle -->
                    <?php if ($billing_toggle): ?>
                        <div class="billing-toggle inline-flex items-center bg-gray-100 rounded-full p-1">
                            <button class="billing-btn monthly-btn px-6 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-white text-gray-900 shadow-sm">
                                <?php _e('Monthly', 'woomag-theme'); ?>
                            </button>
                            <button class="billing-btn yearly-btn px-6 py-2 rounded-full text-sm font-medium transition-all duration-200 text-gray-600 hover:text-gray-900">
                                <?php _e('Yearly', 'woomag-theme'); ?>
                                <span class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                    <?php _e('Save 20%', 'woomag-theme'); ?>
                                </span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Pricing Grid -->
            <div class="pricing-grid grid <?php echo esc_attr($grid_class); ?> gap-8 max-w-7xl mx-auto">
                <?php foreach ($plans as $index => $plan): ?>
                    <?php
                    $is_featured = isset($plan['featured']) ? $plan['featured'] : false;
                    $card_class = $base_card_class;
                    if ($is_featured) {
                        $card_class .= ' ring-2 ring-primary-500 relative transform md:scale-105';
                    }
                    ?>

                    <div class="pricing-plan <?php echo esc_attr($card_class); ?> overflow-hidden">

                        <!-- Featured Badge -->
                        <?php if ($is_featured && !empty($plan['badge'])): ?>
                            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                <span class="bg-primary-600 text-white px-4 py-1 rounded-full text-sm font-medium">
                                    <?php echo esc_html($plan['badge']); ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <div class="p-8">
                            <!-- Plan Header -->
                            <div class="plan-header text-center mb-8">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    <?php echo esc_html($plan['name']); ?>
                                </h3>

                                <?php if (!empty($plan['description'])): ?>
                                    <p class="text-gray-600 text-sm">
                                        <?php echo esc_html($plan['description']); ?>
                                    </p>
                                <?php endif; ?>

                                <!-- Price -->
                                <div class="price-container mt-6">
                                    <div class="price-display">
                                        <span class="currency text-2xl font-medium text-gray-600"><?php echo esc_html($currency); ?></span>
                                        <span class="monthly-price text-5xl font-bold text-gray-900" <?php if (!$billing_toggle): ?>style="display: block;"<?php endif; ?>>
                                            <?php echo esc_html($plan['monthlyPrice']); ?>
                                        </span>
                                        <span class="yearly-price text-5xl font-bold text-gray-900" style="display: none;">
                                            <?php echo esc_html(isset($plan['yearlyPrice']) ? number_format($plan['yearlyPrice'] / 12, 0) : $plan['monthlyPrice']); ?>
                                        </span>
                                        <span class="period text-gray-600">
                                            <span class="monthly-period"><?php _e('/month', 'woomag-theme'); ?></span>
                                            <span class="yearly-period" style="display: none;"><?php _e('/month', 'woomag-theme'); ?></span>
                                        </span>
                                    </div>

                                    <?php if ($billing_toggle && isset($plan['yearlyPrice'])): ?>
                                        <div class="yearly-savings text-sm text-green-600 mt-2" style="display: none;">
                                            <?php
                                            $yearly_total = $plan['yearlyPrice'];
                                            $monthly_total = $plan['monthlyPrice'] * 12;
                                            $savings = $monthly_total - $yearly_total;
                                            printf(__('Save %s%d annually', 'woomag-theme'), $currency, $savings);
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Features List -->
                            <?php if (!empty($plan['features'])): ?>
                                <ul class="features-list space-y-4 mb-8">
                                    <?php foreach ($plan['features'] as $feature): ?>
                                        <li class="flex items-center">
                                            <?php if ($feature['included']): ?>
                                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            <?php else: ?>
                                                <svg class="w-5 h-5 text-gray-300 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            <?php endif; ?>
                                            <span class="<?php echo $feature['included'] ? 'text-gray-700' : 'text-gray-400 line-through'; ?>">
                                                <?php echo esc_html($feature['text']); ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <!-- CTA Button -->
                            <?php if (!empty($plan['buttonText'])): ?>
                                <?php
                                $button_style = isset($plan['buttonStyle']) ? $plan['buttonStyle'] : 'primary';
                                $button_classes = $button_style === 'primary'
                                    ? 'bg-primary-600 hover:bg-primary-700 text-white'
                                    : 'bg-gray-100 hover:bg-gray-200 text-gray-900';

                                if ($is_featured) {
                                    $button_classes = 'bg-primary-600 hover:bg-primary-700 text-white';
                                }
                                ?>
                                <a href="<?php echo esc_url($plan['buttonUrl']); ?>"
                                   class="cta-button block w-full text-center py-3 px-6 rounded-lg font-medium transition-colors duration-200 <?php echo esc_attr($button_classes); ?>">
                                    <?php echo esc_html($plan['buttonText']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Additional Info -->
            <div class="text-center mt-12">
                <p class="text-sm text-gray-600">
                    <?php _e('All plans include 30-day money-back guarantee', 'woomag-theme'); ?>
                </p>
            </div>
        </div>
    </div>

    <?php if ($billing_toggle): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const monthlyBtn = document.querySelector('.monthly-btn');
                const yearlyBtn = document.querySelector('.yearly-btn');
                const monthlyPrices = document.querySelectorAll('.monthly-price');
                const yearlyPrices = document.querySelectorAll('.yearly-price');
                const monthlyPeriods = document.querySelectorAll('.monthly-period');
                const yearlyPeriods = document.querySelectorAll('.yearly-period');
                const yearlySavings = document.querySelectorAll('.yearly-savings');

                function switchToMonthly() {
                    monthlyBtn.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
                    monthlyBtn.classList.remove('text-gray-600');
                    yearlyBtn.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
                    yearlyBtn.classList.add('text-gray-600');

                    monthlyPrices.forEach(el => el.style.display = 'inline');
                    yearlyPrices.forEach(el => el.style.display = 'none');
                    monthlyPeriods.forEach(el => el.style.display = 'inline');
                    yearlyPeriods.forEach(el => el.style.display = 'none');
                    yearlySavings.forEach(el => el.style.display = 'none');
                }

                function switchToYearly() {
                    yearlyBtn.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
                    yearlyBtn.classList.remove('text-gray-600');
                    monthlyBtn.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
                    monthlyBtn.classList.add('text-gray-600');

                    monthlyPrices.forEach(el => el.style.display = 'none');
                    yearlyPrices.forEach(el => el.style.display = 'inline');
                    monthlyPeriods.forEach(el => el.style.display = 'none');
                    yearlyPeriods.forEach(el => el.style.display = 'inline');
                    yearlySavings.forEach(el => el.style.display = 'block');
                }

                if (monthlyBtn && yearlyBtn) {
                    monthlyBtn.addEventListener('click', switchToMonthly);
                    yearlyBtn.addEventListener('click', switchToYearly);
                }
            });
        </script>
    <?php endif; ?>

    <style>
        .pricing-plan.ring-2 {
            position: relative;
            z-index: 1;
        }

        .pricing-plan .cta-button {
            position: relative;
            z-index: 2;
        }

        @media (max-width: 768px) {
            .pricing-plan.transform {
                transform: none !important;
            }
        }

        /* Dark mode support */
        .dark .pricing-plan {
            @apply bg-gray-800 border-gray-700;
        }

        .dark .pricing-plan h3,
        .dark .pricing-plan .text-gray-900 {
            @apply text-gray-100;
        }

        .dark .pricing-plan .text-gray-600 {
            @apply text-gray-400;
        }

        .dark .pricing-plan .text-gray-700 {
            @apply text-gray-300;
        }

        .dark .billing-toggle {
            @apply bg-gray-800;
        }
    </style>
    <?php
    return ob_get_clean();
}
?>