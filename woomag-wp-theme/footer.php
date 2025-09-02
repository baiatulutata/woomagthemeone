</div><!-- #content -->

<footer id="colophon" class="site-footer bg-gray-900 text-white">
    <div class="footer-content py-12">
        <div class="container mx-auto px-4">

            <?php if (is_active_sidebar('footer-widgets')) : ?>
                <div class="footer-widgets mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <?php dynamic_sidebar('footer-widgets'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Footer Navigation -->
            <?php if (has_nav_menu('footer')) : ?>
                <div class="footer-navigation mb-8">
                    <nav class="footer-nav">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'container'      => false,
                            'menu_class'     => 'footer-menu flex flex-wrap justify-center md:justify-start items-center space-x-6',
                            'link_before'    => '<span class="text-gray-300 hover:text-white transition-colors duration-200">',
                            'link_after'     => '</span>',
                            'fallback_cb'    => false,
                        ));
                        ?>
                    </nav>
                </div>
            <?php endif; ?>

            <!-- Site Info -->
            <div class="site-info text-center md:text-left border-t border-gray-700 pt-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="copyright mb-4 md:mb-0">
                        <p class="text-gray-400 text-sm">
                            <?php
                            printf(
                                esc_html__('© %1$s %2$s. All rights reserved.', 'woomagone-theme'),
                                date('Y'),
                                get_bloginfo('name')
                            );
                            ?>
                        </p>
                    </div>

                    <div class="theme-credit">
                        <p class="text-gray-400 text-sm">
                            <?php
                            printf(
                                esc_html__('Powered by %1$s', 'woomagone-theme'),
                                '<a href="' . esc_url(__('https://wordpress.org/', 'woomagone-theme')) . '" class="text-gray-300 hover:text-white transition-colors duration-200">WordPress</a>'
                            );
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="fixed bottom-8 right-8 bg-primary-600 hover:bg-primary-700 text-white p-3 rounded-full shadow-lg transform translate-y-16 transition-transform duration-300 opacity-0 hover:opacity-100 focus:opacity-100" aria-label="<?php esc_attr_e('Back to top', 'woomagone-theme'); ?>">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>
</footer>

</div><!-- #page -->

<?php wp_footer(); ?>

<script>
    // Back to top functionality
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.getElementById('back-to-top');

        if (backToTopButton) {
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopButton.style.transform = 'translateY(0)';
                    backToTopButton.style.opacity = '1';
                } else {
                    backToTopButton.style.transform = 'translateY(4rem)';
                    backToTopButton.style.opacity = '0';
                }
            });

            backToTopButton.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    });
</script>

</body>
</html>