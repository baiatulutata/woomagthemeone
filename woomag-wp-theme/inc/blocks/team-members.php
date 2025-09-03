<?php
/**
 * Team Members Block
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register Team Members Block
function woomag_theme_one_register_team_members_block() {
    wp_register_script(
        'woomag-team-members-block',
        get_template_directory_uri() . '/assets/js/blocks/team-members-block.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
        filemtime(get_template_directory() . '/assets/js/blocks/team-members-block.js')
    );

    register_block_type('woomag-theme/team-members', array(
        'editor_script' => 'woomag-team-members-block',
        'render_callback' => 'woomag_theme_one_team_members_render',
        'attributes' => array(
            'title' => array(
                'type' => 'string',
                'default' => 'Our Amazing Team'
            ),
            'subtitle' => array(
                'type' => 'string',
                'default' => 'Meet the talented people behind our success'
            ),
            'layout' => array(
                'type' => 'string',
                'default' => 'grid'
            ),
            'columns' => array(
                'type' => 'number',
                'default' => 3
            ),
            'showSocial' => array(
                'type' => 'boolean',
                'default' => true
            ),
            'cardStyle' => array(
                'type' => 'string',
                'default' => 'modern'
            ),
            'members' => array(
                'type' => 'array',
                'default' => array()
            )
        )
    ));
}
add_action('init', 'woomag_theme_one_register_team_members_block');

// Team Members Block Render
function woomag_theme_one_team_members_render($attributes) {
    $title = isset($attributes['title']) ? $attributes['title'] : 'Our Amazing Team';
    $subtitle = isset($attributes['subtitle']) ? $attributes['subtitle'] : 'Meet the talented people behind our success';
    $layout = isset($attributes['layout']) ? $attributes['layout'] : 'grid';
    $columns = isset($attributes['columns']) ? $attributes['columns'] : 3;
    $showSocial = isset($attributes['showSocial']) ? $attributes['showSocial'] : true;
    $cardStyle = isset($attributes['cardStyle']) ? $attributes['cardStyle'] : 'modern';
    $members = isset($attributes['members']) ? $attributes['members'] : array();

    // Default members if none provided
    if (empty($members)) {
        $members = array(
            array(
                'name' => 'John Doe',
                'position' => 'CEO & Founder',
                'bio' => 'Passionate leader with 10+ years of experience in the industry.',
                'image' => '',
                'email' => 'john@company.com',
                'linkedin' => '',
                'twitter' => '',
                'instagram' => ''
            ),
            array(
                'name' => 'Jane Smith',
                'position' => 'Lead Designer',
                'bio' => 'Creative professional who brings ideas to life through design.',
                'image' => '',
                'email' => 'jane@company.com',
                'linkedin' => '',
                'twitter' => '',
                'instagram' => ''
            ),
            array(
                'name' => 'Mike Johnson',
                'position' => 'Developer',
                'bio' => 'Full-stack developer passionate about clean, efficient code.',
                'image' => '',
                'email' => 'mike@company.com',
                'linkedin' => '',
                'twitter' => '',
                'instagram' => ''
            )
        );
    }

    // Column classes based on number
    $grid_classes = array(
        1 => 'grid-cols-1',
        2 => 'grid-cols-1 md:grid-cols-2',
        3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
        5 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-5'
    );

    $grid_class = isset($grid_classes[$columns]) ? $grid_classes[$columns] : $grid_classes[3];

    // Card style classes
    $card_styles = array(
        'modern' => 'bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1',
        'minimal' => 'bg-white rounded-lg border border-gray-200 hover:border-primary-300 transition-all duration-300 overflow-hidden',
        'gradient' => 'bg-gradient-to-br from-primary-50 to-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden',
        'card' => 'bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100'
    );

    $card_class = isset($card_styles[$cardStyle]) ? $card_styles[$cardStyle] : $card_styles['modern'];

    ob_start();
    ?>
    <div class="team-members-block py-12 md:py-16">
        <div class="container mx-auto px-4">

            <?php if (!empty($title) || !empty($subtitle)): ?>
                <div class="text-center mb-12">
                    <?php if (!empty($title)): ?>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            <?php echo esc_html($title); ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($subtitle)): ?>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                            <?php echo esc_html($subtitle); ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="team-grid grid <?php echo esc_attr($grid_class); ?> gap-8">
                <?php foreach ($members as $member): ?>
                    <div class="team-member <?php echo esc_attr($card_class); ?>">

                        <!-- Member Photo -->
                        <div class="member-photo relative overflow-hidden">
                            <?php if (!empty($member['image'])): ?>
                                <img src="<?php echo esc_url($member['image']); ?>"
                                     alt="<?php echo esc_attr($member['name']); ?>"
                                     class="w-full h-64 md:h-72 object-cover">
                            <?php else: ?>
                                <div class="w-full h-64 md:h-72 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>

                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-primary-600 bg-opacity-90 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <?php if ($showSocial): ?>
                                    <div class="flex space-x-4">
                                        <?php if (!empty($member['email'])): ?>
                                            <a href="mailto:<?php echo esc_attr($member['email']); ?>"
                                               class="p-3 bg-white text-primary-600 rounded-full hover:bg-gray-100 transition-colors duration-200"
                                               aria-label="<?php printf(__('Email %s', 'woomag-theme'), esc_attr($member['name'])); ?>">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (!empty($member['linkedin'])): ?>
                                            <a href="<?php echo esc_url($member['linkedin']); ?>"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="p-3 bg-white text-primary-600 rounded-full hover:bg-gray-100 transition-colors duration-200"
                                               aria-label="<?php printf(__('%s on LinkedIn', 'woomag-theme'), esc_attr($member['name'])); ?>">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.338 16.338H13.67V12.16c0-.995-.017-2.277-1.387-2.277-1.39 0-1.601 1.086-1.601 2.207v4.248H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (!empty($member['twitter'])): ?>
                                            <a href="<?php echo esc_url($member['twitter']); ?>"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="p-3 bg-white text-primary-600 rounded-full hover:bg-gray-100 transition-colors duration-200"
                                               aria-label="<?php printf(__('%s on Twitter', 'woomag-theme'), esc_attr($member['name'])); ?>">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                                                </svg>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (!empty($member['instagram'])): ?>
                                            <a href="<?php echo esc_url($member['instagram']); ?>"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="p-3 bg-white text-primary-600 rounded-full hover:bg-gray-100 transition-colors duration-200"
                                               aria-label="<?php printf(__('%s on Instagram', 'woomag-theme'), esc_attr($member['name'])); ?>">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 1.8c2.67 0 2.987.01 4.04.058 2.71.123 3.977 1.409 4.1 4.1.048 1.054.058 1.37.058 4.04 0 2.67-.01 2.987-.058 4.04-.123 2.69-1.387 3.977-4.1 4.1-1.054.048-1.37.058-4.04.058-2.67 0-2.987-.01-4.04-.058-2.717-.123-3.977-1.416-4.1-4.1C1.81 12.987 1.8 12.67 1.8 10c0-2.67.01-2.987.058-4.04.124-2.69 1.387-3.977 4.1-4.1C7.013 1.81 7.33 1.8 10 1.8zm0-1.8C7.284 0 6.944.012 5.877.06 2.246.227.227 2.242.06 5.877.012 6.944 0 7.284 0 10s.012 3.057.06 4.123c.167 3.632 2.182 5.65 5.817 5.817C6.944 19.988 7.284 20 10 20s3.057-.012 4.123-.06c3.629-.167 5.652-2.182 5.817-5.817.048-1.066.06-1.406.06-4.123s-.012-3.057-.06-4.123C19.773 2.246 17.758.227 14.123.06 13.057.012 12.716 0 10 0zm0 4.865a5.135 5.135 0 100 10.27 5.135 5.135 0 000-10.27zm0 8.468a3.333 3.333 0 110-6.666 3.333 3.333 0 010 6.666zm5.338-9.87a1.2 1.2 0 100 2.4 1.2 1.2 0 000-2.4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Member Info -->
                        <div class="member-info p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                <?php echo esc_html($member['name']); ?>
                            </h3>

                            <p class="text-primary-600 font-medium mb-3">
                                <?php echo esc_html($member['position']); ?>
                            </p>

                            <?php if (!empty($member['bio'])): ?>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    <?php echo esc_html($member['bio']); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
?>