<?php get_header(); ?>

    <main class="main-content">
        <?php woomag_theme_breadcrumb(); ?>

        <div class="container mx-auto px-4 py-8">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('single-post max-w-4xl mx-auto'); ?>>

                    <header class="entry-header mb-8 text-center">
                        <?php the_title('<h1 class="entry-title text-4xl md:text-5xl font-bold text-gray-900 mb-6">', '</h1>'); ?>

                        <div class="entry-meta text-gray-600 flex items-center justify-center space-x-6 text-sm">
                            <time datetime="<?php echo get_the_date('c'); ?>" class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <?php echo get_the_date(); ?>
                            </time>

                            <span class="author flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <?php the_author(); ?>
                        </span>

                            <?php if (get_the_category()) : ?>
                                <span class="categories flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <?php the_category(', '); ?>
                            </span>
                            <?php endif; ?>

                            <?php if (comments_open() || get_comments_number()) : ?>
                                <span class="comments-count flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <?php comments_number(__('0 Comments', 'woomag-theme'), __('1 Comment', 'woomag-theme'), __('% Comments', 'woomag-theme')); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </header>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="featured-image mb-8">
                            <?php the_post_thumbnail('hero-image', array('class' => 'w-full h-auto rounded-lg shadow-lg')); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content prose lg:prose-lg max-w-none">
                        <?php the_content(); ?>

                        <?php wp_link_pages(array(
                            'before' => '<div class="page-links mt-8 pt-8 border-t border-gray-200"><span class="page-links-title font-medium text-gray-700">' . __('Pages:', 'woomag-theme') . '</span>',
                            'after'  => '</div>',
                            'link_before' => '<span class="inline-block px-4 py-2 ml-2 bg-gray-100 rounded-lg hover:bg-primary-600 hover:text-white transition-colors duration-200">',
                            'link_after'  => '</span>',
                        )); ?>
                    </div>

                    <footer class="entry-footer mt-12 pt-8 border-t border-gray-200">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <?php if (get_the_tags()) : ?>
                                <div class="tags mb-4 md:mb-0">
                                    <span class="text-sm font-medium text-gray-600 mr-2"><?php _e('Tags:', 'woomag-theme'); ?></span>
                                    <div class="inline-flex flex-wrap gap-2">
                                        <?php the_tags('<span class="inline-block px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-full hover:bg-primary-100 hover:text-primary-700 transition-colors duration-200">', '</span><span class="inline-block px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-full hover:bg-primary-100 hover:text-primary-700 transition-colors duration-200">', '</span>'); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="share-buttons flex items-center space-x-3">
                                <span class="text-sm font-medium text-gray-600 mr-2"><?php _e('Share:', 'woomag-theme'); ?></span>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>"
                                   target="_blank"
                                   class="p-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors duration-200"
                                   aria-label="<?php _e('Share on Twitter', 'woomag-theme'); ?>">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                                   target="_blank"
                                   class="p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors duration-200"
                                   aria-label="<?php _e('Share on Facebook', 'woomag-theme'); ?>">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>"
                                   target="_blank"
                                   class="p-2 bg-blue-700 text-white rounded-full hover:bg-blue-800 transition-colors duration-200"
                                   aria-label="<?php _e('Share on LinkedIn', 'woomag-theme'); ?>">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </footer>

                </article>

                <?php
                // Author bio
                if (get_the_author_meta('description')) :
                    ?>
                    <div class="author-bio max-w-4xl mx-auto mt-12 p-6 bg-gray-50 rounded-lg">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <?php echo get_avatar(get_the_author_meta('ID'), 80, '', '', array('class' => 'rounded-full')); ?>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    <?php printf(__('About %s', 'woomag-theme'), get_the_author()); ?>
                                </h3>
                                <p class="text-gray-600"><?php the_author_meta('description'); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php
                // Post navigation
                $prev_post = get_previous_post();
                $next_post = get_next_post();

                if ($prev_post || $next_post) :
                    ?>
                    <nav class="post-navigation max-w-4xl mx-auto mt-12 pt-8 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php if ($prev_post) : ?>
                                <div class="prev-post">
                                    <a href="<?php echo get_permalink($prev_post); ?>" class="group block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow duration-200">
                                        <div class="flex items-center text-sm text-gray-600 mb-2">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                            <?php _e('Previous Post', 'woomag-theme'); ?>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary-600 transition-colors duration-200">
                                            <?php echo get_the_title($prev_post); ?>
                                        </h3>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if ($next_post) : ?>
                                <div class="next-post <?php echo !$prev_post ? 'md:col-start-2' : ''; ?>">
                                    <a href="<?php echo get_permalink($next_post); ?>" class="group block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow duration-200">
                                        <div class="flex items-center justify-end text-sm text-gray-600 mb-2">
                                            <?php _e('Next Post', 'woomag-theme'); ?>
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary-600 transition-colors duration-200 text-right">
                                            <?php echo get_the_title($next_post); ?>
                                        </h3>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </nav>
                <?php endif; ?>

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