<?php
/**
 * Dark Mode Support
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add dark mode customizer controls
function woomag_theme_dark_mode_customizer($wp_customize) {
    // Dark Mode Section
    $wp_customize->add_section('dark_mode_section', array(
        'title'    => __('Dark Mode', 'woomag-theme'),
        'priority' => 35,
    ));

    // Enable Dark Mode
    $wp_customize->add_setting('enable_dark_mode', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('enable_dark_mode', array(
        'label'   => __('Enable Dark Mode Toggle', 'woomag-theme'),
        'section' => 'dark_mode_section',
        'type'    => 'checkbox',
    ));

    // Auto Dark Mode
    $wp_customize->add_setting('auto_dark_mode', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('auto_dark_mode', array(
        'label'       => __('Auto Dark Mode (based on system preference)', 'woomag-theme'),
        'section'     => 'dark_mode_section',
        'type'        => 'checkbox',
        'description' => __('Automatically switch to dark mode based on user\'s system preference', 'woomag-theme'),
    ));

    // Dark Mode Colors
    $wp_customize->add_setting('dark_bg_color', array(
        'default'           => '#1a1a1a',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dark_bg_color', array(
        'label'   => __('Dark Background Color', 'woomag-theme'),
        'section' => 'dark_mode_section',
    )));

    $wp_customize->add_setting('dark_text_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dark_text_color', array(
        'label'   => __('Dark Text Color', 'woomag-theme'),
        'section' => 'dark_mode_section',
    )));
}
add_action('customize_register', 'woomag_theme_dark_mode_customizer');

// Add dark mode toggle to header
function woomag_theme_dark_mode_toggle() {
    if (get_theme_mod('enable_dark_mode', false)) {
        ?>
        <button id="dark-mode-toggle" class="dark-mode-toggle p-2 rounded-lg text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition-colors duration-200" aria-label="<?php esc_attr_e('Toggle dark mode', 'woomag-theme'); ?>">
            <svg class="w-5 h-5 dark-mode-sun" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <svg class="w-5 h-5 dark-mode-moon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
        </button>
        <?php
    }
}

// Add dark mode CSS variables
function woomag_theme_dark_mode_css() {
    if (get_theme_mod('enable_dark_mode', false)) {
        $dark_bg = get_theme_mod('dark_bg_color', '#1a1a1a');
        $dark_text = get_theme_mod('dark_text_color', '#ffffff');
        $auto_dark = get_theme_mod('auto_dark_mode', false);
        ?>
        <style id="dark-mode-styles">
            :root {
                --dark-bg: <?php echo esc_attr($dark_bg); ?>;
                --dark-text: <?php echo esc_attr($dark_text); ?>;
            }

            <?php if ($auto_dark): ?>
            @media (prefers-color-scheme: dark) {
                :root {
                    --bg-color: var(--dark-bg);
                    --text-color: var(--dark-text);
                }

                body {
                    background-color: var(--dark-bg);
                    color: var(--dark-text);
                }
            }
            <?php endif; ?>

            /* Dark mode classes */
            .dark {
                --bg-color: var(--dark-bg);
                --text-color: var(--dark-text);
            }

            .dark body {
                background-color: var(--dark-bg);
                color: var(--dark-text);
            }

            .dark .bg-white {
                background-color: #2d2d2d;
            }

            .dark .bg-gray-50 {
                background-color: #262626;
            }

            .dark .bg-gray-100 {
                background-color: #404040;
            }

            .dark .text-gray-900 {
                color: #f3f4f6;
            }

            .dark .text-gray-700 {
                color: #d1d5db;
            }

            .dark .text-gray-600 {
                color: #9ca3af;
            }

            .dark .border-gray-200 {
                border-color: #404040;
            }

            .dark .border-gray-300 {
                border-color: #525252;
            }

            .dark .shadow-md {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
            }

            .dark .shadow-lg {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
            }
        </style>

        <script>
            // Dark mode functionality
            (function() {
                const darkModeToggle = document.getElementById('dark-mode-toggle');
                const html = document.documentElement;
                const sunIcon = document.querySelector('.dark-mode-sun');
                const moonIcon = document.querySelector('.dark-mode-moon');

                // Check for saved theme preference or default to auto mode
                const savedTheme = localStorage.getItem('theme');
                const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                function setDarkMode(isDark) {
                    if (isDark) {
                        html.classList.add('dark');
                        if (sunIcon && moonIcon) {
                            sunIcon.classList.add('hidden');
                            moonIcon.classList.remove('hidden');
                        }
                    } else {
                        html.classList.remove('dark');
                        if (sunIcon && moonIcon) {
                            sunIcon.classList.remove('hidden');
                            moonIcon.classList.add('hidden');
                        }
                    }
                }

                // Initialize theme
                if (savedTheme === 'dark' || (!savedTheme && systemDark && <?php echo $auto_dark ? 'true' : 'false'; ?>)) {
                    setDarkMode(true);
                } else {
                    setDarkMode(false);
                }

                // Toggle functionality
                if (darkModeToggle) {
                    darkModeToggle.addEventListener('click', function() {
                        const isDark = html.classList.contains('dark');
                        setDarkMode(!isDark);
                        localStorage.setItem('theme', !isDark ? 'dark' : 'light');
                    });
                }

                // Listen for system theme changes
                <?php if ($auto_dark): ?>
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                    if (!localStorage.getItem('theme')) {
                        setDarkMode(e.matches);
                    }
                });
                <?php endif; ?>
            })();
        </script>
        <?php
    }
}
add_action('wp_head', 'woomag_theme_dark_mode_css');

// Add dark mode toggle to navigation
function woomag_theme_add_dark_mode_to_nav($items, $args) {
    if ($args->theme_location == 'primary' && get_theme_mod('enable_dark_mode', false)) {
        $items .= '<li class="menu-item dark-mode-toggle-item">';
        ob_start();
        woomag_theme_dark_mode_toggle();
        $items .= ob_get_clean();
        $items .= '</li>';
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'woomag_theme_add_dark_mode_to_nav', 10, 2);
?>