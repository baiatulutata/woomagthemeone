const { registerBlockType } = wp.blocks;
const { RichText, MediaUpload, InspectorControls } = wp.blockEditor;
const { PanelBody, Button, TextControl, SelectControl, ToggleControl } = wp.components;

registerBlockType('woomag-theme/testimonial', {
    title: 'Testimonial',
    icon: 'format-quote',
    category: 'woomag-theme',
    description: 'Add customer testimonials with photos and ratings.',
    keywords: ['testimonial', 'quote', 'review', 'customer'],

    attributes: {
        quote: {
            type: 'string',
            default: 'This is an amazing testimonial quote that showcases the excellent service and quality we received.'
        },
        author: {
            type: 'string',
            default: 'John Doe'
        },
        position: {
            type: 'string',
            default: 'CEO, Company Name'
        },
        avatar: {
            type: 'string',
            default: ''
        },
        rating: {
            type: 'number',
            default: 5
        },
        showRating: {
            type: 'boolean',
            default: true
        },
        style: {
            type: 'string',
            default: 'modern'
        },
        alignment: {
            type: 'string',
            default: 'center'
        }
    },

    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { quote, author, position, avatar, rating, showRating, style, alignment } = attributes;

        const renderStars = (count) => {
            const stars = [];
            for (let i = 1; i <= 5; i++) {
                stars.push(
                    wp.element.createElement('svg', {
                            key: i,
                            className: `w-5 h-5 ${i <= count ? 'text-yellow-400' : 'text-gray-300'}`,
                            fill: 'currentColor',
                            viewBox: '0 0 20 20'
                        },
                        wp.element.createElement('path', {
                            d: 'M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z'
                        })
                    )
                );
            }
            return stars;
        };

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Testimonial Settings', initialOpen: true },
                    wp.element.createElement(SelectControl, {
                        label: 'Style',
                        value: style,
                        options: [
                            { label: 'Modern', value: 'modern' },
                            { label: 'Minimal', value: 'minimal' },
                            { label: 'Card', value: 'card' },
                            { label: 'Quote', value: 'quote' }
                        ],
                        onChange: (value) => setAttributes({ style: value })
                    }),
                    wp.element.createElement(SelectControl, {
                        label: 'Alignment',
                        value: alignment,
                        options: [
                            { label: 'Left', value: 'left' },
                            { label: 'Center', value: 'center' },
                            { label: 'Right', value: 'right' }
                        ],
                        onChange: (value) => setAttributes({ alignment: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Show Rating',
                        checked: showRating,
                        onChange: (value) => setAttributes({ showRating: value })
                    }),
                    showRating && wp.element.createElement(SelectControl, {
                        label: 'Rating',
                        value: rating,
                        options: [
                            { label: '1 Star', value: 1 },
                            { label: '2 Stars', value: 2 },
                            { label: '3 Stars', value: 3 },
                            { label: '4 Stars', value: 4 },
                            { label: '5 Stars', value: 5 }
                        ],
                        onChange: (value) => setAttributes({ rating: parseInt(value) })
                    })
                ),
                wp.element.createElement(PanelBody, { title: 'Author Settings', initialOpen: false },
                    wp.element.createElement(TextControl, {
                        label: 'Author Name',
                        value: author,
                        onChange: (value) => setAttributes({ author: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'Position/Company',
                        value: position,
                        onChange: (value) => setAttributes({ position: value })
                    }),
                    wp.element.createElement('div', { style: { marginBottom: '16px' } },
                        wp.element.createElement('label', { className: 'block text-sm font-medium mb-2' }, 'Author Photo'),
                        wp.element.createElement(MediaUpload, {
                            onSelect: (media) => setAttributes({ avatar: media.url }),
                            allowedTypes: ['image'],
                            render: ({ open }) => wp.element.createElement(Button, {
                                onClick: open,
                                variant: avatar ? 'secondary' : 'primary',
                                className: 'mb-2'
                            }, avatar ? 'Change Photo' : 'Select Photo')
                        }),
                        avatar && wp.element.createElement('img', {
                            src: avatar,
                            alt: author,
                            style: { width: '60px', height: '60px', objectFit: 'cover', borderRadius: '50%' }
                        })
                    )
                )
            ),

            wp.element.createElement('div', {
                    className: `testimonial-editor p-8 bg-gray-50 rounded-lg text-${alignment}`,
                    style: { maxWidth: '600px', margin: '0 auto' }
                },
                wp.element.createElement('div', { className: 'mb-6' },
                    wp.element.createElement('div', { className: 'text-6xl text-primary-300 mb-4' }, '"'),
                    wp.element.createElement(RichText, {
                        tagName: 'blockquote',
                        className: 'text-lg italic text-gray-700 leading-relaxed',
                        value: quote,
                        onChange: (value) => setAttributes({ quote: value }),
                        placeholder: 'Enter testimonial quote...'
                    })
                ),

                showRating && wp.element.createElement('div', {
                        className: `flex ${alignment === 'center' ? 'justify-center' : alignment === 'right' ? 'justify-end' : 'justify-start'} mb-4`
                    },
                    wp.element.createElement('div', { className: 'flex' }, renderStars(rating))
                ),

                wp.element.createElement('div', {
                        className: `flex items-center ${alignment === 'center' ? 'justify-center' : alignment === 'right' ? 'justify-end' : 'justify-start'}`
                    },
                    avatar && wp.element.createElement('img', {
                        src: avatar,
                        alt: author,
                        className: 'w-12 h-12 rounded-full mr-4 object-cover'
                    }),
                    wp.element.createElement('div', { className: alignment === 'right' ? 'text-right' : '' },
                        wp.element.createElement(RichText, {
                            tagName: 'div',
                            className: 'font-semibold text-gray-900',
                            value: author,
                            onChange: (value) => setAttributes({ author: value }),
                            placeholder: 'Author name...'
                        }),
                        wp.element.createElement(RichText, {
                            tagName: 'div',
                            className: 'text-gray-600 text-sm',
                            value: position,
                            onChange: (value) => setAttributes({ position: value }),
                            placeholder: 'Position/Company...'
                        })
                    )
                )
            )
        ];
    },

    save: function() {
        // Server-side rendering
        return null;
    }
});