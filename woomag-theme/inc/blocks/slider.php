<?php
/**
 * Swiper Slider Block
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register Swiper Slider Block
function woomag_theme_register_slider_block() {

    // Enqueue Swiper CSS and JS
    wp_register_style(
        'swiper-css',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        array(),
        '11.0.0'
    );

    wp_register_script(
        'swiper-js',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        array(),
        '11.0.0',
        true
    );

    wp_register_script(
        'woomag-slider-block',
        get_template_directory_uri() . '/assets/js/blocks.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
        filemtime(get_template_directory() . '/assets/js/blocks.js')
    );

    register_block_type('woomag-theme/slider', array(
        'editor_script' => 'woomag-slider-block',
        'render_callback' => 'woomag_theme_slider_render',
        'attributes' => array(
            'sliderType' => array(
                'type' => 'string',
                'default' => 'image'
            ),
            'autoplay' => array(
                'type' => 'boolean',
                'default' => true
            ),
            'autoplayDelay' => array(
                'type' => 'number',
                'default' => 5000
            ),
            'loop' => array(
                'type' => 'boolean',
                'default' => true
            ),
            'navigation' => array(
                'type' => 'boolean',
                'default' => true
            ),
            'pagination' => array(
                'type' => 'boolean',
                'default' => true
            ),
            'effect' => array(
                'type' => 'string',
                'default' => 'slide'
            ),
            'slidesPerView' => array(
                'type' => 'number',
                'default' => 1
            ),
            'spaceBetween' => array(
                'type' => 'number',
                'default' => 30
            ),
            'centeredSlides' => array(
                'type' => 'boolean',
                'default' => false
            ),
            'height' => array(
                'type' => 'string',
                'default' => 'auto'
            ),
            'slides' => array(
                'type' => 'array',
                'default' => array()
            )
        )
    ));
}
add_action('init', 'woomag_theme_register_slider_block');

// Swiper Slider Block Render
function woomag_theme_slider_render($attributes) {

    // Enqueue Swiper assets on frontend
    wp_enqueue_style('swiper-css');
    wp_enqueue_script('swiper-js');

    $slider_type = isset($attributes['sliderType']) ? $attributes['sliderType'] : 'image';
    $autoplay = isset($attributes['autoplay']) ? $attributes['autoplay'] : true;
    $autoplay_delay = isset($attributes['autoplayDelay']) ? $attributes['autoplayDelay'] : 5000;
    $loop = isset($attributes['loop']) ? $attributes['loop'] : true;
    $navigation = isset($attributes['navigation']) ? $attributes['navigation'] : true;
    $pagination = isset($attributes['pagination']) ? $attributes['pagination'] : true;
    $effect = isset($attributes['effect']) ? $attributes['effect'] : 'slide';
    $slides_per_view = isset($attributes['slidesPerView']) ? $attributes['slidesPerView'] : 1;
    $space_between = isset($attributes['spaceBetween']) ? $attributes['spaceBetween'] : 30;
    $centered_slides = isset($attributes['centeredSlides']) ? $attributes['centeredSlides'] : false;
    $height = isset($attributes['height']) ? $attributes['height'] : 'auto';
    $slides = isset($attributes['slides']) ? $attributes['slides'] : array();

    // Default slides if none provided
    if (empty($slides)) {
        $slides = array(
            array(
                'type' => 'image',
                'image' => '',
                'title' => 'Slide 1 Title',
                'subtitle' => 'This is the first slide subtitle',
                'content' => 'Slide content goes here. You can add any text or description.',
                'buttonText' => 'Learn More',
                'buttonUrl' => '#',
                'overlayOpacity' => 50
            ),
            array(
                'type' => 'image',
                'image' => '',
                'title' => 'Slide 2 Title',
                'subtitle' => 'This is the second slide subtitle',
                'content' => 'Another slide with different content and call-to-action.',
                'buttonText' => 'Get Started',
                'buttonUrl' => '#',
                'overlayOpacity' => 50
            ),
            array(
                'type' => 'image',
                'image' => '',
                'title' => 'Slide 3 Title',
                'subtitle' => 'This is the third slide subtitle',
                'content' => 'Final slide showcasing your amazing content and services.',
                'buttonText' => 'Contact Us',
                'buttonUrl' => '#',
                'overlayOpacity' => 50
            )
        );
    }

    // Generate unique ID for this slider
    $slider_id = 'woomag-slider-' . uniqid();

    // Height class
    $height_class = '';
    switch ($height) {
        case 'small':
            $height_class = 'h-64 md:h-80';
            break;
        case 'medium':
            $height_class = 'h-80 md:h-96';
            break;
        case 'large':
            $height_class = 'h-96 md:h-screen-75';
            break;
        case 'fullscreen':
            $height_class = 'h-screen';
            break;
        default:
            $height_class = 'h-auto min-h-96';
    }

    ob_start();
    ?>
    <div class="woomag-slider-block relative">
        <div id="<?php echo esc_attr($slider_id); ?>" class="swiper <?php echo esc_attr($height_class); ?>">
            <div class="swiper-wrapper">
                <?php foreach ($slides as $index => $slide): ?>
                    <div class="swiper-slide relative <?php echo esc_attr($height_class); ?>">
                        <?php if ($slider_type === 'image' && !empty($slide['image'])): ?>
                            <div class="slide-background absolute inset-0">
                                <img src="<?php echo esc_url($slide['image']); ?>"
                                     alt="<?php echo esc_attr($slide['title']); ?>"
                                     class="w-full h-full object-cover">
                                <div class="slide-overlay absolute inset-0 bg-black"
                                     style="opacity: <?php echo esc_attr($slide['overlayOpacity'] / 100); ?>"></div>
                            </div>
                        <?php elseif ($slider_type === 'image'): ?>
                            <div class="slide-background absolute inset-0 bg-gradient-to-br from-primary-600 to-primary-800"></div>
                        <?php endif; ?>

                        <div class="slide-content relative z-10 h-full flex items-center justify-center text-center px-6">
                            <div class="max-w-4xl mx-auto">
                                <?php if (!empty($slide['subtitle'])): ?>
                                    <p class="slide-subtitle text-sm md:text-base font-medium text-white/80 mb-4 uppercase tracking-wider">
                                        <?php echo esc_html($slide['subtitle']); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($slide['title'])): ?>
                                    <h2 class="slide-title text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                                        <?php echo esc_html($slide['title']); ?>
                                    </h2>
                                <?php endif; ?>

                                <?php if (!empty($slide['content'])): ?>
                                    <p class="slide-content-text text-lg md:text-xl text-white/90 mb-8 max-w-2xl mx-auto leading-relaxed">
                                        <?php echo esc_html($slide['content']); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($slide['buttonText'])): ?>
                                    <a href="<?php echo esc_url($slide['buttonUrl']); ?>"
                                       class="slide-button inline-block bg-primary-600 hover:bg-primary-700 text-white font-bold py-4 px-8 rounded-lg text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                        <?php echo esc_html($slide['buttonText']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($pagination): ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>

            <?php if ($navigation): ?>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = new Swiper('#<?php echo esc_js($slider_id); ?>', {
                <?php if ($effect !== 'slide'): ?>
                effect: '<?php echo esc_js($effect); ?>',
                <?php endif; ?>

                slidesPerView: <?php echo esc_js($slides_per_view); ?>,
                spaceBetween: <?php echo esc_js($space_between); ?>,

                <?php if ($centered_slides): ?>
                centeredSlides: true,
                <?php endif; ?>

                <?php if ($loop): ?>
                loop: true,
                <?php endif; ?>

                <?php if ($autoplay): ?>
                autoplay: {
                    delay: <?php echo esc_js($autoplay_delay); ?>,
                    disableOnInteraction: false,
                },
                <?php endif; ?>

                <?php if ($pagination): ?>
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                },
                <?php endif; ?>

                <?php if ($navigation): ?>
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                <?php endif; ?>

                breakpoints: {
                    640: {
                        slidesPerView: <?php echo esc_js($slides_per_view > 1 ? min($slides_per_view, 2) : 1); ?>,
                        spaceBetween: <?php echo esc_js($space_between); ?>,
                    },
                    768: {
                        slidesPerView: <?php echo esc_js($slides_per_view > 2 ? min($slides_per_view, 3) : $slides_per_view); ?>,
                        spaceBetween: <?php echo esc_js($space_between); ?>,
                    },
                    1024: {
                        slidesPerView: <?php echo esc_js($slides_per_view); ?>,
                        spaceBetween: <?php echo esc_js($space_between); ?>,
                    }
                },

                on: {
                    init: function() {
                        // Add custom animations or effects here
                        this.slides.forEach(function(slide, index) {
                            const content = slide.querySelector('.slide-content');
                            if (content) {
                                content.style.opacity = index === 0 ? '1' : '0';
                                content.style.transform = index === 0 ? 'translateY(0)' : 'translateY(30px)';
                                content.style.transition = 'all 0.6s ease';
                            }
                        });
                    },
                    slideChange: function() {
                        // Animate slide content
                        this.slides.forEach(function(slide, index) {
                            const content = slide.querySelector('.slide-content');
                            if (content) {
                                const isActive = slide.classList.contains('swiper-slide-active');
                                content.style.opacity = isActive ? '1' : '0';
                                content.style.transform = isActive ? 'translateY(0)' : 'translateY(30px)';
                            }
                        });
                    }
                }
            });

            // Pause autoplay on hover
            const sliderEl = document.getElementById('<?php echo esc_js($slider_id); ?>');
            if (sliderEl && slider.autoplay) {
                sliderEl.addEventListener('mouseenter', () => {
                    slider.autoplay.stop();
                });
                sliderEl.addEventListener('mouseleave', () => {
                    slider.autoplay.start();
                });
            }
        });
    </script>

    <style>
        #<?php echo esc_attr($slider_id); ?> .swiper-button-next,
        #<?php echo esc_attr($slider_id); ?> .swiper-button-prev {
                                                 @apply text-white w-12 h-12 bg-black/20 rounded-full backdrop-blur-sm;
                                             }

        #<?php echo esc_attr($slider_id); ?> .swiper-button-next:after,
        #<?php echo esc_attr($slider_id); ?> .swiper-button-prev:after {
                                                 @apply text-sm;
                                             }

        #<?php echo esc_attr($slider_id); ?> .swiper-button-next:hover,
        #<?php echo esc_attr($slider_id); ?> .swiper-button-prev:hover {
                                                 @apply bg-primary-600/80;
                                             }

        #<?php echo esc_attr($slider_id); ?> .swiper-pagination-bullet {
                                                 @apply bg-white/50 w-3 h-3;
                                             }

        #<?php echo esc_attr($slider_id); ?> .swiper-pagination-bullet-active {
                                                 @apply bg-primary-500;
                                             }

        <?php if ($effect === 'fade'): ?>
        #<?php echo esc_attr($slider_id); ?> .swiper-slide {
                                                 @apply opacity-0 transition-opacity duration-500;
                                             }

        #<?php echo esc_attr($slider_id); ?> .swiper-slide-active {
                                                 @apply opacity-100;
                                             }
        <?php endif; ?>

        /* Dark mode support */
        .dark #<?php echo esc_attr($slider_id); ?> .swiper-button-next,
                                                   .dark #<?php echo esc_attr($slider_id); ?> .swiper-button-prev {
                                                                                                  @apply bg-white/20;
                                                                                              }
    </style>
    <?php
    return ob_get_clean();
}
?>