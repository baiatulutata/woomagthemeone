<?php get_header(); ?>

    <main class="main-content">
        <?php woomag_theme_one_breadcrumb(); ?>

        <div class="container mx-auto px-4 py-8">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('single-page max-w-4xl mx-auto'); ?>>

                    <header class="entry-header mb-8 text-center">
                        <?php the_title('<h1 class="entry-title text-4xl md:text-5xl font-bold text-gray-900 mb-6">', '</h1>'); ?>

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="featured-image mb-8">
                                <?php the_post_thumbnail('hero-image', array('class' => 'w-full h-auto rounded-lg shadow-lg')); ?>
                            </div>
                        <?php endif; ?>
                    </header>

                    <div class="entry-content prose lg:prose-lg max-w-none">
                        <?php the_content(); ?>

                        <?php wp_link_pages(array(
                            'before' => '<div class="page-links mt-8 pt-8 border-t border-gray-200"><span class="page-links-title font-medium text-gray-700">' . __('Pages:', 'woomag-theme') . '</span>',
                            'after'  => '</div>',
                            'link_before' => '<span class="inline-block px-4 py-2 ml-2 bg-gray-100 rounded-lg hover:bg-primary-600 hover:text-white transition-colors duration-200">',
                            'link_after'  => '</span>',
                        )); ?>
                    </div>

                    <?php if (get_edit_post_link()) : ?>
                        <footer class="entry-footer mt-12 pt-8 border-t border-gray-200">
                            <?php
                            edit_post_link(
                                sprintf(
                                    wp_kses(
                                        __('Edit <span class="screen-reader-text">%s</span>', 'woomag-theme'),
                                        array(
                                            'span' => array(
                                                'class' => array(),
                                            ),
                                        )
                                    ),
                                    get_the_title()
                                ),
                                '<span class="edit-link inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-primary-600 hover:text-white transition-colors duration-200">',
                                '</span>'
                            );
                            ?>
                        </footer>
                    <?php endif; ?>

                </article>

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    ?>
                    <div class="comments-section max-w-4xl mx-auto mt-12">
                        <?php comments_template(); ?>
                    </div>
                <?php
                endif;
                ?>

            <?php endwhile; ?>
        </div>
    </main>

<?php get_footer(); ?>