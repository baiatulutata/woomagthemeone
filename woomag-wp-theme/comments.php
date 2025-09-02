<?php
/**
 * The template for displaying comments
 */

if (post_password_required()) {
    return;
}
?>

    <div id="comments" class="comments-area">

        <?php if (have_comments()) : ?>
            <h2 class="comments-title text-2xl font-bold text-gray-900 mb-8">
                <?php
                $comment_count = get_comments_number();
                if ($comment_count === 1) {
                    printf(_x('One comment on &ldquo;%s&rdquo;', 'comments title', 'woomag-theme'), get_the_title());
                } else {
                    printf(
                        _nx(
                            '%1$s comment on &ldquo;%2$s&rdquo;',
                            '%1$s comments on &ldquo;%2$s&rdquo;',
                            $comment_count,
                            'comments title',
                            'woomag-theme'
                        ),
                        number_format_i18n($comment_count),
                        get_the_title()
                    );
                }
                ?>
            </h2>

            <?php the_comments_navigation(array(
                'prev_text' => __('← Older Comments', 'woomag-theme'),
                'next_text' => __('Newer Comments →', 'woomag-theme'),
                'class'     => 'comments-navigation flex justify-between mb-8',
            )); ?>

            <ol class="comment-list space-y-8">
                <?php
                wp_list_comments(array(
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 60,
                    'callback'    => 'woomag_theme_one_comment_callback',
                ));
                ?>
            </ol>

            <?php the_comments_navigation(array(
                'prev_text' => __('← Older Comments', 'woomag-theme'),
                'next_text' => __('Newer Comments →', 'woomag-theme'),
                'class'     => 'comments-navigation flex justify-between mt-8',
            )); ?>

            <?php if (!comments_open()) : ?>
                <p class="no-comments text-gray-600 italic mt-8">
                    <?php _e('Comments are closed.', 'woomag-theme'); ?>
                </p>
            <?php endif; ?>

        <?php endif; ?>

        <?php
        comment_form(array(
            'title_reply'         => __('Leave a Reply', 'woomag-theme'),
            'title_reply_to'      => __('Leave a Reply to %s', 'woomag-theme'),
            'cancel_reply_link'   => __('Cancel Reply', 'woomag-theme'),
            'label_submit'        => __('Post Comment', 'woomag-theme'),
            'submit_button'       => '<input name="%1$s" type="submit" id="%2$s" class="%3$s btn-primary" value="%4$s" />',
            'comment_field'       => '<div class="mb-6"><label for="comment" class="block text-sm font-medium text-gray-700 mb-2">' . _x('Comment', 'noun', 'woomag-theme') . ' <span class="required text-red-500">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required></textarea></div>',
            'fields'              => array(
                'author' => '<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6"><div><label for="author" class="block text-sm font-medium text-gray-700 mb-2">' . __('Name', 'woomag-theme') . ' <span class="required text-red-500">*</span></label><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required /></div>',
                'email'  => '<div><label for="email" class="block text-sm font-medium text-gray-700 mb-2">' . __('Email', 'woomag-theme') . ' <span class="required text-red-500">*</span></label><input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required /></div></div>',
                'url'    => '<div class="mb-6"><label for="url" class="block text-sm font-medium text-gray-700 mb-2">' . __('Website', 'woomag-theme') . '</label><input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" /></div>',
            ),
            'class_form'          => 'comment-form mt-12 p-8 bg-gray-50 rounded-lg',
            'class_submit'        => 'btn-primary',
        ));
        ?>

    </div>

<?php
// Custom comment callback function
if (!function_exists('woomag_theme_one_comment_callback')) {
    function woomag_theme_one_comment_callback($comment, $args, $depth) {
        $tag = ($args['style'] === 'div') ? 'div' : 'li';
        ?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class('comment bg-white p-6 rounded-lg shadow-sm border border-gray-200'); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta flex items-start space-x-4 mb-4">
                <div class="comment-author-avatar flex-shrink-0">
                    <?php echo get_avatar($comment, $args['avatar_size'], '', '', array('class' => 'rounded-full')); ?>
                </div>
                <div class="comment-metadata flex-1">
                    <div class="comment-author-name">
                        <?php printf('<b class="fn text-gray-900">%s</b>', get_comment_author_link()); ?>
                    </div>
                    <div class="comment-date-time text-sm text-gray-600">
                        <a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>" class="hover:text-primary-600 transition-colors duration-200">
                            <time datetime="<?php comment_time('c'); ?>">
                                <?php printf(_x('%1$s at %2$s', '1: date, 2: time', 'woomag-theme'), get_comment_date('', $comment), get_comment_time()); ?>
                            </time>
                        </a>
                        <?php edit_comment_link(__('(Edit)', 'woomag-theme'), '<span class="edit-link ml-2">', '</span>'); ?>
                    </div>
                </div>
            </footer>

            <?php if ('0' == $comment->comment_approved) : ?>
                <div class="comment-awaiting-moderation bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-2 rounded mb-4">
                    <?php _e('Your comment is awaiting moderation.', 'woomag-theme'); ?>
                </div>
            <?php endif; ?>

            <div class="comment-content prose prose-sm max-w-none">
                <?php comment_text(); ?>
            </div>

            <div class="reply mt-4">
                <?php
                comment_reply_link(array_merge($args, array(
                    'add_below' => 'div-comment',
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<div class="reply-link">',
                    'after'     => '</div>',
                    'class'     => 'text-primary-600 hover:text-primary-700 text-sm font-medium transition-colors duration-200'
                )));
                ?>
            </div>
        </article>
        <?php
    }
}
?>