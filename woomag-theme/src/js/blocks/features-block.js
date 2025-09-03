const { registerBlockType } = wp.blocks;
const { RichText, InspectorControls } = wp.blockEditor;
const { PanelBody, Button, TextControl, SelectControl, RangeControl, Card, CardBody } = wp.components;
const { Fragment } = wp.element;

registerBlockType('woomag-theme/features', {
    title: 'Feature Grid',
    icon: 'grid-view',
    category: 'woomag-theme',
    description: 'Showcase features with icons, titles, and descriptions in a responsive grid.',
    keywords: ['features', 'grid', 'services', 'benefits'],

    attributes: {
        title: {
            type: 'string',
            default: 'Amazing Features'
        },
        subtitle: {
            type: 'string',
            default: 'Discover what makes us different'
        },
        columns: {
            type: 'number',
            default: 3
        },
        style: {
            type: 'string',
            default: 'modern'
        },
        features: {
            type: 'array',
            default: [
                {
                    title: 'Lightning Fast',
                    description: 'Optimized for speed and performance with modern technologies.',
                    icon: '⚡'
                },
                {
                    title: 'Secure & Reliable',
                    description: 'Built with security best practices and reliable infrastructure.',
                    icon: '🔒'
                },
                {
                    title: '24/7 Support',
                    description: 'Round-the-clock customer support to help you succeed.',
                    icon: '🎧'
                }
            ]
        }
    },

    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { title, subtitle, columns, style, features } = attributes;

        const updateFeature = (index, field, value) => {
            const newFeatures = [...features];
            newFeatures[index] = { ...newFeatures[index], [field]: value };
            setAttributes({ features: newFeatures });
        };

        const addFeature = () => {
            const newFeatures = [...features, {
                title: 'New Feature',
                description: 'Feature description...',
                icon: '✨'
            }];
            setAttributes({ features: newFeatures });
        };

        const removeFeature = (index) => {
            const newFeatures = features.filter((_, i) => i !== index);
            setAttributes({ features: newFeatures });
        };

        const gridClasses = {
            1: 'repeat(1, 1fr)',
            2: 'repeat(auto-fit, minmax(300px, 1fr))',
            3: 'repeat(auto-fit, minmax(280px, 1fr))',
            4: 'repeat(auto-fit, minmax(250px, 1fr))',
            5: 'repeat(auto-fit, minmax(220px, 1fr))'
        };

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Feature Grid Settings', initialOpen: true },
                    wp.element.createElement(SelectControl, {
                        label: 'Style',
                        value: style,
                        options: [
                            { label: 'Modern', value: 'modern' },
                            { label: 'Minimal', value: 'minimal' },
                            { label: 'Card', value: 'card' },
                            { label: 'Bordered', value: 'bordered' }
                        ],
                        onChange: (value) => setAttributes({ style: value })
                    }),
                    wp.element.createElement(RangeControl, {
                        label: 'Columns',
                        value: columns,
                        onChange: (value) => setAttributes({ columns: value }),
                        min: 1,
                        max: 5
                    })
                ),

                wp.element.createElement(PanelBody, { title: 'Features', initialOpen: false },
                    wp.element.createElement(Button, {
                        variant: 'primary',
                        onClick: addFeature,
                        className: 'mb-4'
                    }, 'Add Feature'),

                    features.map((feature, index) =>
                        wp.element.createElement(Card, { key: index, className: 'mb-4' },
                            wp.element.createElement(CardBody, null,
                                wp.element.createElement('div', { className: 'flex justify-between items-start mb-3' },
                                    wp.element.createElement('h4', { className: 'text-sm font-medium' }, `Feature ${index + 1}`),
                                    wp.element.createElement(Button, {
                                        variant: 'secondary',
                                        isSmall: true,
                                        isDestructive: true,
                                        onClick: () => removeFeature(index)
                                    }, 'Remove')
                                ),

                                wp.element.createElement(TextControl, {
                                    label: 'Icon (Emoji or Text)',
                                    value: feature.icon,
                                    onChange: (value) => updateFeature(index, 'icon', value),
                                    help: 'Use emojis like ⚡ 🚀 💎 or text'
                                }),

                                wp.element.createElement(TextControl, {
                                    label: 'Title',
                                    value: feature.title,
                                    onChange: (value) => updateFeature(index, 'title', value)
                                }),

                                wp.element.createElement('label', { className: 'block text-sm font-medium mb-2' }, 'Description'),
                                wp.element.createElement('textarea', {
                                    value: feature.description,
                                    onChange: (e) => updateFeature(index, 'description', e.target.value),
                                    rows: 3,
                                    className: 'w-full px-3 py-2 border border-gray-300 rounded-md'
                                })
                            )
                        )
                    )
                )
            ),

            wp.element.createElement('div', {
                    className: 'features-editor py-8 px-4 bg-gray-50 rounded-lg'
                },
                wp.element.createElement('div', { className: 'text-center mb-8' },
                    wp.element.createElement(RichText, {
                        tagName: 'h2',
                        className: 'text-3xl font-bold text-gray-900 mb-4',
                        value: title,
                        onChange: (value) => setAttributes({ title: value }),
                        placeholder: 'Features section title...'
                    }),
                    wp.element.createElement(RichText, {
                        tagName: 'p',
                        className: 'text-lg text-gray-600',
                        value: subtitle,
                        onChange: (value) => setAttributes({ subtitle: value }),
                        placeholder: 'Features section subtitle...'
                    })
                ),

                wp.element.createElement('div', {
                        className: 'grid gap-6',
                        style: {
                            gridTemplateColumns: gridClasses[columns] || gridClasses[3],
                            maxWidth: '1200px',
                            margin: '0 auto'
                        }
                    },
                    features.map((feature, index) =>
                        wp.element.createElement('div', {
                                key: index,
                                className: 'bg-white p-6 rounded-lg shadow-md text-center border-2 border-dashed border-gray-300'
                            },
                            wp.element.createElement('div', { className: 'text-4xl mb-4' }, feature.icon),
                            wp.element.createElement('h3', { className: 'text-xl font-semibold mb-3 text-gray-900' }, feature.title),
                            wp.element.createElement('p', { className: 'text-gray-600' }, feature.description)
                        )
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