<?php
/**
 * Accessibility Features
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Skip to content link
function woomag_theme_skip_link() {
    echo '<a class="skip-link screen-reader-text sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 bg-primary-600 text-white px-4 py-2 rounded z-50" href="#main">' . __('Skip to content', 'woomag-theme') . '</a>';
}
add_action('wp_body_open', 'woomag_theme_skip_link');

// Add ARIA landmarks
function woomag_theme_aria_landmarks() {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add main landmark if not present
            const mainContent = document.querySelector('.main-content');
            if (mainContent && !mainContent.hasAttribute('role')) {
                mainContent.setAttribute('role', 'main');
                mainContent.setAttribute('id', 'main');
            }

            // Add navigation landmarks
            const navs = document.querySelectorAll('nav');
            navs.forEach(function(nav) {
                if (!nav.hasAttribute('aria-label') && !nav.hasAttribute('aria-labelledby')) {
                    if (nav.classList.contains('main-navigation')) {
                        nav.setAttribute('aria-label', '<?php echo esc_js(__('Main navigation', 'woomag-theme')); ?>');
                    } else if (nav.classList.contains('footer-nav')) {
                        nav.setAttribute('aria-label', '<?php echo esc_js(__('Footer navigation', 'woomag-theme')); ?>');
                    }
                }
            });

            // Improve form accessibility
            const forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                const inputs = form.querySelectorAll('input, textarea, select');
                inputs.forEach(function(input) {
                    if (!input.hasAttribute('aria-label') && !input.hasAttribute('aria-labelledby')) {
                        const label = form.querySelector('label[for="' + input.id + '"]');
                        if (!label) {
                            const placeholder = input.getAttribute('placeholder');
                            if (placeholder) {
                                input.setAttribute('aria-label', placeholder);
                            }
                        }
                    }
                });
            });
        });
    </script>
    <?php
}
add_action('wp_footer', 'woomag_theme_aria_landmarks');

// Accessibility customizer options
function woomag_theme_accessibility_customizer($wp_customize) {
    // Accessibility Section
    $wp_customize->add_section('accessibility_section', array(
        'title'    => __('Accessibility', 'woomag-theme'),
        'priority' => 40,
    ));

    // High contrast mode
    $wp_customize->add_setting('high_contrast_mode', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('high_contrast_mode', array(
        'label'   => __('High Contrast Mode', 'woomag-theme'),
        'section' => 'accessibility_section',
        'type'    => 'checkbox',
    ));

    // Focus indicators
    $wp_customize->add_setting('enhanced_focus', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('enhanced_focus', array(
        'label'   => __('Enhanced Focus Indicators', 'woomag-theme'),
        'section' => 'accessibility_section',
        'type'    => 'checkbox',
    ));

    // Reduced motion
    $wp_customize->add_setting('respect_reduced_motion', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('respect_reduced_motion', array(
        'label'       => __('Respect Reduced Motion Preference', 'woomag-theme'),
        'section'     => 'accessibility_section',
        'type'        => 'checkbox',
        'description' => __('Reduce animations for users who prefer reduced motion', 'woomag-theme'),
    ));
}
add_action('customize_register', 'woomag_theme_accessibility_customizer');

// Add accessibility CSS
function woomag_theme_accessibility_css() {
    ?>
    <style id="accessibility-styles">
        /* Screen reader text */
        .screen-reader-text,
        .sr-only {
            position: absolute !important;
            clip-path: inset(50%);
            width: 1px;
            height: 1px;
            overflow: hidden;
            white-space: nowrap;
        }

        .focus\:not-sr-only:focus {
            position: static !important;
            clip-path: none;
            width: auto;
            height: auto;
            overflow: visible;
            white-space: normal;
        }

        /* Enhanced focus indicators */
        <?php if (get_theme_mod('enhanced_focus', true)): ?>
        *:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        button:focus,
        a:focus,
        input:focus,
        textarea:focus,
        select:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }
        <?php endif; ?>

        /* High contrast mode */
        <?php if (get_theme_mod('high_contrast_mode', false)): ?>
        .high-contrast {
            filter: contrast(150%) brightness(110%);
        }

        .high-contrast a {
            text-decoration: underline;
        }

        .high-contrast button,
        .high-contrast .btn-primary {
            border: 2px solid currentColor;
        }
        <?php endif; ?>

        /* Reduced motion */
        <?php if (get_theme_mod('respect_reduced_motion', true)): ?>
        @media (prefers-reduced-motion: reduce) {
            *,
            ::before,
            ::after {
                animation-delay: -1ms !important;
                animation-duration: 1ms !important;
                animation-iteration-count: 1 !important;
                background-attachment: initial !important;
                scroll-behavior: auto !important;
                transition-duration: 0s !important;
                transition-delay: 0s !important;
            }
        }
        <?php endif; ?>

        /* Improve text readability */
        body {
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Ensure minimum touch target size */
        button,
        input[type="button"],
        input[type="submit"],
        input[type="reset"],
        a {
            min-height: 44px;
            min-width: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Improve table accessibility */
        table {
            border-collapse: collapse;
        }

        th {
            text-align: left;
        }

        /* Better form error states */
        .error,
        .invalid {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .error + .error-message,
        .invalid + .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
    <?php
}
add_action('wp_head', 'woomag_theme_accessibility_css');

// Add accessibility toolbar
function woomag_theme_accessibility_toolbar() {
    ?>
    <div id="accessibility-toolbar" class="accessibility-toolbar fixed top-0 right-0 z-50 bg-white dark:bg-gray-800 shadow-lg rounded-bl-lg p-2 transform translate-x-full transition-transform duration-300">
        <button id="accessibility-toggle" class="accessibility-toggle p-2 bg-primary-600 text-white rounded-lg mb-2" aria-label="<?php esc_attr_e('Toggle accessibility options', 'woomag-theme'); ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </button>

        <div id="accessibility-options" class="accessibility-options hidden space-y-2">
            <button id="increase-font" class="block w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded" title="<?php esc_attr_e('Increase font size', 'woomag-theme'); ?>">
                A+
            </button>
            <button id="decrease-font" class="block w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded" title="<?php esc_attr_e('Decrease font size', 'woomag-theme'); ?>">
                A-
            </button>
            <button id="toggle-contrast" class="block w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded" title="<?php esc_attr_e('Toggle high contrast', 'woomag-theme'); ?>">
                <?php _e('Contrast', 'woomag-theme'); ?>
            </button>
            <button id="reset-accessibility" class="block w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded" title="<?php esc_attr_e('Reset accessibility settings', 'woomag-theme'); ?>">
                <?php _e('Reset', 'woomag-theme'); ?>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toolbar = document.getElementById('accessibility-toolbar');
            const toggle = document.getElementById('accessibility-toggle');
            const options = document.getElementById('accessibility-options');
            const html = document.documentElement;

            let fontSize = 16;
            let isHighContrast = false;

            // Load saved settings
            const savedFontSize = localStorage.getItem('accessibility-font-size');
            const savedContrast = localStorage.getItem('accessibility-contrast');

            if (savedFontSize) {
                fontSize = parseInt(savedFontSize);
                html.style.fontSize = fontSize + 'px';
            }

            if (savedContrast === 'true') {
                isHighContrast = true;
                document.body.classList.add('high-contrast');
            }

            // Toggle toolbar
            toggle.addEventListener('click', function() {
                toolbar.classList.toggle('translate-x-full');
                options.classList.toggle('hidden');
            });

            // Font size controls
            document.getElementById('increase-font').addEventListener('click', function() {
                if (fontSize < 24) {
                    fontSize += 2;
                    html.style.fontSize = fontSize + 'px';
                    localStorage.setItem('accessibility-font-size', fontSize);
                }
            });

            document.getElementById('decrease-font').addEventListener('click', function() {
                if (fontSize > 12) {
                    fontSize -= 2;
                    html.style.fontSize = fontSize + 'px';
                    localStorage.setItem('accessibility-font-size', fontSize);
                }
            });

            // Contrast toggle
            document.getElementById('toggle-contrast').addEventListener('click', function() {
                isHighContrast = !isHighContrast;
                document.body.classList.toggle('high-contrast', isHighContrast);
                localStorage.setItem('accessibility-contrast', isHighContrast);
            });

            // Reset settings
            document.getElementById('reset-accessibility').addEventListener('click', function() {
                fontSize = 16;
                isHighContrast = false;
                html.style.fontSize = '';
                document.body.classList.remove('high-contrast');
                localStorage.removeItem('accessibility-font-size');
                localStorage.removeItem('accessibility-contrast');
            });

            // Show toolbar on tab navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    toolbar.classList.remove('translate-x-full');
                    setTimeout(() => {
                        toolbar.classList.add('translate-x-full');
                    }, 3000);
                }
            });
        });
    </script>
    <?php
}
add_action('wp_footer', 'woomag_theme_accessibility_toolbar');
?>