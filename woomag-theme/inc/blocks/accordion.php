<?php
/**
 * Accordion Block
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Accordion Block Render
function woomag_theme_one_accordion_render($attributes) {
    $title = isset($attributes['title']) ? $attributes['title'] : 'Frequently Asked Questions';
    $subtitle = isset($attributes['subtitle']) ? $attributes['subtitle'] : 'Find answers to common questions below';
    $style = isset($attributes['style']) ? $attributes['style'] : 'modern';
    $allowMultiple = isset($attributes['allowMultiple']) ? $attributes['allowMultiple'] : false;
    $openFirst = isset($attributes['openFirst']) ? $attributes['openFirst'] : true;
    $iconStyle = isset($attributes['iconStyle']) ? $attributes['iconStyle'] : 'plus';
    $items = isset($attributes['items']) ? $attributes['items'] : array();

    // Default items if none provided
    if (empty($items)) {
        $items = array(
            array(
                'question' => 'What makes WoomagOne different from other themes?',
                'answer' => 'WoomagOne is built with modern technologies like Tailwind CSS, Webpack, and includes advanced features like dark mode, accessibility tools, and custom blocks. It\'s designed for performance and user experience.',
                'isOpen' => $openFirst
            ),
            array(
                'question' => 'Is WoomagOne compatible with popular plugins?',
                'answer' => 'Yes! WoomagOne is tested with popular plugins including WooCommerce, Yoast SEO, Contact Form 7, and most page builders. We follow WordPress coding standards for maximum compatibility.',
                'isOpen' => false
            ),
            array(
                'question' => 'Do you provide support and documentation?',
                'answer' => 'Absolutely! We provide comprehensive documentation, video tutorials, and dedicated support. Our team is here to help you get the most out of WoomagOne.',
                'isOpen' => false
            ),
            array(
                'question' => 'Can I customize the theme colors and fonts?',
                'answer' => 'Yes, WoomagOne includes a powerful customizer with options for colors, fonts, layouts, and more. You can also use custom CSS for advanced customization.',
                'isOpen' => false
            ),
            array(
                'question' => 'Is the theme mobile responsive?',
                'answer' => 'WoomagOne is built with a mobile-first approach and is fully responsive across all devices. It also includes advanced mobile optimization features.',
                'isOpen' => false
            )
        );
    }

    // Style classes
    $style_classes = array(
        'modern' => 'bg-white rounded-lg shadow-md border border-gray-200 mb-4 overflow-hidden',
        'minimal' => 'border-b border-gray-200 mb-2 pb-2',
        'boxed' => 'bg-gray-50 rounded-lg p-4 mb-4 border border-gray-300',
        'outlined' => 'border-2 border-primary-200 rounded-lg mb-4 p-4'
    );

    $item_class = isset($style_classes[$style]) ? $style_classes[$style] : $style_classes['modern'];

    // Icon styles
    $icons = array(
        'plus' => array('closed' => '+', 'open' => '−'),
        'arrow' => array('closed' => '→', 'open' => '↓'),
        'chevron' => array('closed' => '›', 'open' => '‹')
    );

    $icon_set = isset($icons[$iconStyle]) ? $icons[$iconStyle] : $icons['plus'];

    // Generate unique ID for this accordion
    $accordion_id = 'woomag-accordion-' . uniqid();

    ob_start();
    ?>
    <div class="accordion-block py-12 md:py-16" id="<?php echo esc_attr($accordion_id); ?>">
        <div class="container mx-auto px-4 max-w-4xl">

            <!-- Header -->
            <?php if (!empty($title) || !empty($subtitle)): ?>
                <div class="text-center mb-12">
                    <?php if (!empty($title)): ?>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            <?php echo esc_html($title); ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($subtitle)): ?>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                            <?php echo esc_html($subtitle); ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Accordion Items -->
            <div class="accordion-items">
                <?php foreach ($items as $index => $item): ?>
                    <?php
                    $item_id = $accordion_id . '-item-' . $index;
                    $is_open = isset($item['isOpen']) ? $item['isOpen'] : false;
                    ?>

                    <div class="accordion-item <?php echo esc_attr($item_class); ?>" data-accordion-item>

                        <!-- Question/Header -->
                        <button class="accordion-header w-full text-left p-4 md:p-6 flex items-center justify-between hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset"
                                aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                                aria-controls="<?php echo esc_attr($item_id); ?>"
                                data-accordion-trigger>

                            <span class="accordion-question text-lg font-semibold text-gray-900 pr-4">
                                <?php echo esc_html($item['question']); ?>
                            </span>

                            <!-- Icon -->
                            <span class="accordion-icon text-xl font-bold text-primary-600 flex-shrink-0 transition-transform duration-200 <?php echo $is_open ? 'transform rotate-180' : ''; ?>">
                                <?php if ($iconStyle === 'chevron'): ?>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                <?php elseif ($iconStyle === 'plus'): ?>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path class="icon-horizontal" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        <path class="icon-vertical <?php echo $is_open ? 'hidden' : ''; ?>" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12"></path>
                                    </svg>
                                <?php else: // arrow ?>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h12"></path>
                                    </svg>
                                <?php endif; ?>
                            </span>
                        </button>

                        <!-- Answer/Content -->
                        <div class="accordion-content overflow-hidden transition-all duration-300 ease-in-out <?php echo $is_open ? 'max-h-screen opacity-100' : 'max-h-0 opacity-0'; ?>"
                             id="<?php echo esc_attr($item_id); ?>"
                             data-accordion-content>
                            <div class="accordion-answer p-4 md:p-6 pt-0 text-gray-700 leading-relaxed">
                                <?php echo wp_kses_post(wpautop($item['answer'])); ?>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Additional Info -->
            <div class="text-center mt-12 p-6 bg-primary-50 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    <?php _e('Still have questions?', 'woomag-theme'); ?>
                </h3>
                <p class="text-gray-600 mb-4">
                    <?php _e('Can\'t find the answer you\'re looking for? Please chat to our friendly team.', 'woomag-theme'); ?>
                </p>
                <a href="#contact" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200">
                    <?php _e('Get in Touch', 'woomag-theme'); ?>
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accordion = document.getElementById('<?php echo esc_js($accordion_id); ?>');
            if (!accordion) return;

            const allowMultiple = <?php echo $allowMultiple ? 'true' : 'false'; ?>;
            const items = accordion.querySelectorAll('[data-accordion-item]');

            items.forEach(function(item) {
                const trigger = item.querySelector('[data-accordion-trigger]');
                const content = item.querySelector('[data-accordion-content]');
                const icon = item.querySelector('.accordion-icon');

                if (!trigger || !content) return;

                trigger.addEventListener('click', function() {
                    const isOpen = trigger.getAttribute('aria-expanded') === 'true';

                    // If not allowing multiple, close all others first
                    if (!allowMultiple && !isOpen) {
                        items.forEach(function(otherItem) {
                            if (otherItem !== item) {
                                closeItem(otherItem);
                            }
                        });
                    }

                    // Toggle current item
                    if (isOpen) {
                        closeItem(item);
                    } else {
                        openItem(item);
                    }
                });
            });

            function openItem(item) {
                const trigger = item.querySelector('[data-accordion-trigger]');
                const content = item.querySelector('[data-accordion-content]');
                const icon = item.querySelector('.accordion-icon');
                const iconVertical = item.querySelector('.icon-vertical');

                trigger.setAttribute('aria-expanded', 'true');
                content.style.maxHeight = content.scrollHeight + 'px';
                content.classList.remove('opacity-0');
                content.classList.add('opacity-100');

                if (icon) {
                    icon.classList.add('transform', 'rotate-180');
                }

                if (iconVertical) {
                    iconVertical.classList.add('hidden');
                }

                // Add smooth transition after setting height
                setTimeout(function() {
                    content.style.maxHeight = 'none';
                }, 300);
            }

            function closeItem(item) {
                const trigger = item.querySelector('[data-accordion-trigger]');
                const content = item.querySelector('[data-accordion-content]');
                const icon = item.querySelector('.accordion-icon');
                const iconVertical = item.querySelector('.icon-vertical');

                trigger.setAttribute('aria-expanded', 'false');
                content.style.maxHeight = content.scrollHeight + 'px';

                // Force reflow
                content.offsetHeight;

                content.style.maxHeight = '0';
                content.classList.remove('opacity-100');
                content.classList.add('opacity-0');

                if (icon) {
                    icon.classList.remove('transform', 'rotate-180');
                }

                if (iconVertical) {
                    iconVertical.classList.remove('hidden');
                }
            }

            // Initialize proper heights for open items
            items.forEach(function(item) {
                const trigger = item.querySelector('[data-accordion-trigger]');
                const content = item.querySelector('[data-accordion-content]');

                if (trigger.getAttribute('aria-expanded') === 'true') {
                    content.style.maxHeight = 'none';
                }
            });
        });
    </script>

    <style>
        #<?php echo esc_attr($accordion_id); ?> .accordion-content {
            transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }

        #<?php echo esc_attr($accordion_id); ?> .accordion-icon {
                                                    transition: transform 0.2s ease-in-out;
                                                }

        #<?php echo esc_attr($accordion_id); ?> .accordion-header:hover .accordion-icon {
                                                    color: #2563eb;
                                                }

        /* Focus styles for accessibility */
        #<?php echo esc_attr($accordion_id); ?> .accordion-header:focus {
                                                    outline: 2px solid #3b82f6;
                                                    outline-offset: -2px;
                                                }

        /* Dark mode support */
        .dark #<?php echo esc_attr($accordion_id); ?> .accordion-item {
                                                          @apply bg-gray-800 border-gray-700;
                                                      }

        .dark #<?php echo esc_attr($accordion_id); ?> .accordion-question {
                                                          @apply text-gray-100;
                                                      }

        .dark #<?php echo esc_attr($accordion_id); ?> .accordion-answer {
                                                          @apply text-gray-300;
                                                      }

        .dark #<?php echo esc_attr($accordion_id); ?> .accordion-header:hover {
                                                          @apply bg-gray-700;
                                                      }

        /* Animation for plus icon */
        #<?php echo esc_attr($accordion_id); ?> .icon-horizontal,
        #<?php echo esc_attr($accordion_id); ?> .icon-vertical {
                                                    transition: opacity 0.2s ease-in-out;
                                                }

        /* Responsive adjustments */
        @media (max-width: 768px) {
        #<?php echo esc_attr($accordion_id); ?> .accordion-header {
            padding: 1rem;
        }

        #<?php echo esc_attr($accordion_id); ?> .accordion-answer {
                                                    padding: 1rem;
                                                    padding-top: 0;
                                                }

        #<?php echo esc_attr($accordion_id); ?> .accordion-question {
                                                    font-size: 1rem;
                                                }
        }
    </style>
    <?php
    return ob_get_clean();
}
?>