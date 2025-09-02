<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="relative max-w-md mx-auto">
        <label for="search-field" class="sr-only">
            <?php _e('Search for:', 'woomagone-theme'); ?>
        </label>
        <input
            type="search"
            id="search-field"
            class="search-field w-full px-4 py-3 pl-12 pr-4 text-gray-900 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            placeholder="<?php echo esc_attr_x('Search...', 'placeholder', 'woomagone-theme'); ?>"
            value="<?php echo get_search_query(); ?>"
            name="s"
        />
        <button
            type="submit"
            class="search-submit absolute left-0 top-0 bottom-0 px-4 text-gray-400 hover:text-primary-600 focus:text-primary-600 focus:outline-none"
            aria-label="<?php echo esc_attr_x('Search', 'submit button', 'woomagone-theme'); ?>"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </button>
    </div>
</form>