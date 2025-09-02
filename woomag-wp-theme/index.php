<?php get_header(); ?>

    <main class="main-content">
        <?php woomag_theme_one_breadcrumb(); ?>

        <div class="container mx-auto px-4 py-8">
            <?php if (have_posts()) : ?>

                <?php if (is_home() && !is_front_page()) : ?>
                    <header class="page-header mb-8">
                        <h1 class="page-title text-3xl font-bold text-gray-900"><?php single_post_title(); ?></h1>
                    </header>
                <?php endif; ?>

                <div class="posts-grid grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <?php while (have_posts()) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300'); ?>>

                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>" class="block">
                                        <?php the_post_thumbnail('card-image', array('class' => 'w-full h-48 object-cover')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="post-content p-6">
                                <header class="entry-header mb-4">
                                    <?php if (is_singular()) : ?>
                                        <?php the_title('<h1 class="entry-title text-2xl font-bold text-gray-900 mb-2">', '</h1>'); ?>
                                    <?php else : ?>
                                        <?php the_title('<h2 class="entry-title text-xl font-bold text-gray-900 mb-2"><a href="' . esc_url(get_permalink()) . '" class="hover:text-primary-600 transition-colors duration-200">', '</a></h2>'); ?>
                                    <?php endif; ?>

                                    <div class="entry-meta text-sm text-gray-600 flex items-center space-x-4">
                                        <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                                        <?php if (get_the_category()) : ?>
                                            <span class="categories">
                                            <?php the_category(', '); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </header>

                                <div class="entry-content">
                                    <?php if (is_singular()) : ?>
                                        <div class="prose lg:prose-lg max-w-none">
                                            <?php the_content(); ?>
                                        </div>
                                        <?php wp_link_pages(array(
                                            'before' => '<div class="page-links mt-6"><span class="page-links-title font-medium">' . __('Pages:', 'woomag-theme') . '</span>',
                                            'after'  => '</div>',
                                            'link_before' => '<span class="inline-block px-3 py-1 ml-2 bg-gray-100 rounded hover:bg-primary-600 hover:text-white transition-colors duration-200">',
                                            'link_after'  => '</span>',
                                        )); ?>
                                    <?php else : ?>
                                        <?php the_excerpt(); ?>
                                        <a href="<?php the_permalink(); ?>" class="read-more inline-block mt-4 text-primary-600 hover:text-primary-700 font-medium transition-colors duration-200">
                                            <?php _e('Read More', 'woomag-theme'); ?> →
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <?php if (is_singular() && (get_the_tags() || comments_open() || get_comments_number())) : ?>
                                    <footer class="entry-footer mt-6 pt-6 border-t border-gray-200">
                                        <?php if (get_the_tags()) : ?>
                                            <div class="tags mb-4">
                                                <span class="text-sm font-medium text-gray-600 mr-2"><?php _e('Tags:', 'woomag-theme'); ?></span>
                                                <?php the_tags('<span class="inline-block px-2 py-1 text-xs bg-gray-100 rounded mr-2 mb-2">', '</span><span class="inline-block px-2 py-1 text-xs bg-gray-100 rounded mr-2 mb-2">', '</span>'); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (comments_open() || get_comments_number()) : ?>
                                            <div class="comments-link">
                                                <?php comments_popup_link(
                                                    __('Leave a Comment', 'woomag-theme'),
                                                    __('1 Comment', 'woomag-theme'),
                                                    __('% Comments', 'woomag-theme'),
                                                    'text-primary-600 hover:text-primary-700 font-medium transition-colors duration-200'
                                                ); ?>
                                            </div>
                                        <?php endif; ?>
                                    </footer>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php
                // Pagination
                the_posts_pagination(array(
                    'prev_text' => __('← Previous', 'woomag-theme'),
                    'next_text' => __('Next →', 'woomag-theme'),
                    'before_page_number' => '<span class="sr-only">' . __('Page', 'woomag-theme') . ' </span>',
                    'class' => 'pagination flex justify-center mt-12 space-x-2',
                ));
                ?>

            <?php else : ?>

                <div class="no-posts text-center py-16">
                    <h1 class="text-2xl font-bold text-gray-900 mb-4"><?php _e('Nothing Found', 'woomag-theme'); ?></h1>
                    <p class="text-gray-600 mb-8">
                        <?php if (is_search()) : ?>
                            <?php printf(__('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'woomag-theme')); ?>
                        <?php else : ?>
                            <?php _e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'woomag-theme'); ?>
                        <?php endif; ?>
                    </p>
                    <?php get_search_form(); ?>
                </div>

            <?php endif; ?>
        </div>
    </main>

<?php get_footer(); ?>