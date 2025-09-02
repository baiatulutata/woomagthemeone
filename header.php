<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <!-- Preload fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

    <!-- Header Image -->
    <?php
    $header_image = get_theme_mod('header_image_setting');
    if ($header_image || has_header_image()) :
        $image_url = $header_image ? $header_image : get_header_image();
        ?>
        <div class="header-image relative">
            <img src="<?php echo esc_url($image_url); ?>"
                 alt="<?php bloginfo('name'); ?>"
                 class="w-full h-64 md:h-96 object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        </div>
    <?php endif; ?>

    <!-- Header with Logo and Navigation -->
    <header id="masthead" class="site-header sticky top-0 z-40 bg-white shadow-sm transition-all duration-300">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16 md:h-20">

                <!-- Logo -->
                <div class="site-branding flex items-center">
                    <?php if (has_custom_logo()) : ?>
                        <div class="custom-logo">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php else : ?>
                        <div class="text-logo">
                            <h1 class="site-title text-xl md:text-2xl font-bold text-gray-900">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-primary-600 transition-colors duration-200">
                                    <?php bloginfo('name'); ?>
                                </a>
                            </h1>
                            <?php
                            $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()) :
                                ?>
                                <p class="site-description text-sm text-gray-600 mt-1"><?php echo $description; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Desktop Navigation -->
                <nav id="site-navigation" class="main-navigation hidden md:block">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'nav-menu flex items-center space-x-1',
                        'walker'         => new Modern_Walker_Nav_Menu(),
                        'fallback_cb'    => function() {
                            echo '<ul class="nav-menu flex items-center space-x-1">';
                            echo '<li><a href="' . home_url('/') . '" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">' . __('Home', 'modern-theme') . '</a></li>';
                            echo '</ul>';
                        }
                    ));
                    ?>
                </nav>

                <!-- Mobile Menu Toggle -->
                <button id="mobile-menu-toggle" class="mobile-menu-toggle" aria-label="<?php _e('Toggle mobile menu', 'modern-theme'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="mobile-menu hidden">
            <div class="px-4 py-4">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900"><?php _e('Menu', 'modern-theme'); ?></h2>
                    <button id="mobile-menu-close" class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container'      => 'nav',
                    'container_class' => 'mobile-nav',
                    'menu_class'     => 'mobile-nav-menu space-y-2',
                    'link_before'    => '<span class="block px-4 py-3 text-gray-700 hover:bg-gray-100 hover:text-primary-600 rounded-md transition-colors duration-200">',
                    'link_after'     => '</span>',
                    'fallback_cb'    => function() {
                        echo '<nav class="mobile-nav">';
                        echo '<ul class="mobile-nav-menu space-y-2">';
                        echo '<li><a href="' . home_url('/') . '"><span class="block px-4 py-3 text-gray-700 hover:bg-gray-100 hover:text-primary-600 rounded-md transition-colors duration-200">' . __('Home', 'modern-theme') . '</span></a></li>';
                        echo '</ul>';
                        echo '</nav>';
                    }
                ));
                ?>
            </div>
        </div>
    </header>

    <div id="content" class="site-content"><?php // This div is closed in footer.php ?>