const { registerBlockType } = wp.blocks;
const { RichText, InspectorControls } = wp.blockEditor;
const { PanelBody, Button, TextControl, ToggleControl, SelectControl, Card, CardBody } = wp.components;
const { useState, Fragment } = wp.element;

registerBlockType('woomag-theme/accordion', {
    title: 'Accordion / FAQ',
    icon: 'list-view',
    category: 'woomag-theme',
    description: 'Create collapsible accordion sections perfect for FAQs, documentation, and content organization.',
    keywords: ['accordion', 'faq', 'collapse', 'expand', 'questions'],

    attributes: {
        title: {
            type: 'string',
            default: 'Frequently Asked Questions'
        },
        subtitle: {
            type: 'string',
            default: 'Find answers to common questions below'
        },
        style: {
            type: 'string',
            default: 'modern'
        },
        allowMultiple: {
            type: 'boolean',
            default: false
        },
        openFirst: {
            type: 'boolean',
            default: true
        },
        iconStyle: {
            type: 'string',
            default: 'plus'
        },
        items: {
            type: 'array',
            default: [
                {
                    question: 'What makes WoomagOne different from other themes?',
                    answer: 'WoomagOne is built with modern technologies like Tailwind CSS, Webpack, and includes advanced features like dark mode, accessibility tools, and custom blocks. It\'s designed for performance and user experience.',
                    isOpen: true
                },
                {
                    question: 'Is WoomagOne compatible with popular plugins?',
                    answer: 'Yes! WoomagOne is tested with popular plugins including WooCommerce, Yoast SEO, Contact Form 7, and most page builders. We follow WordPress coding standards for maximum compatibility.',
                    isOpen: false
                },
                {
                    question: 'Do you provide support and documentation?',
                    answer: 'Absolutely! We provide comprehensive documentation, video tutorials, and dedicated support. Our team is here to help you get the most out of WoomagOne.',
                    isOpen: false
                },
                {
                    question: 'Can I customize the theme colors and fonts?',
                    answer: 'Yes, WoomagOne includes a powerful customizer with options for colors, fonts, layouts, and more. You can also use custom CSS for advanced customization.',
                    isOpen: false
                },
                {
                    question: 'Is the theme mobile responsive?',
                    answer: 'WoomagOne is built with a mobile-first approach and is fully responsive across all devices. It also includes advanced mobile optimization features.',
                    isOpen: false
                }
            ]
        }
    },

    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { title, subtitle, style, allowMultiple, openFirst, iconStyle, items } = attributes;

        const [activeItem, setActiveItem] = useState(0);

        const updateItem = (index, field, value) => {
            const newItems = [...items];
            newItems[index] = { ...newItems[index], [field]: value };
            setAttributes({ items: newItems });
        };

        const addItem = () => {
            const newItems = [...items, {
                question: `Question ${items.length + 1}`,
                answer: 'Your answer goes here. You can add detailed explanations, lists, and formatting.',
                isOpen: false
            }];
            setAttributes({ items: newItems });
        };

        const removeItem = (index) => {
            if (items.length > 1) {
                const newItems = items.filter((_, i) => i !== index);
                setAttributes({ items: newItems });
                if (activeItem >= newItems.length) {
                    setActiveItem(newItems.length - 1);
                }
            }
        };

        const duplicateItem = (index) => {
            const newItems = [...items];
            const duplicatedItem = {
                ...items[index],
                question: items[index].question + ' (Copy)',
                isOpen: false
            };
            newItems.splice(index + 1, 0, duplicatedItem);
            setAttributes({ items: newItems });
        };

        const moveItem = (index, direction) => {
            const newItems = [...items];
            const newIndex = direction === 'up' ? index - 1 : index + 1;

            if (newIndex >= 0 && newIndex < items.length) {
                [newItems[index], newItems[newIndex]] = [newItems[newIndex], newItems[index]];
                setAttributes({ items: newItems });
                setActiveItem(newIndex);
            }
        };

        const toggleItem = (index) => {
            const newItems = [...items];

            if (!allowMultiple) {
                // Close all others first
                newItems.forEach((item, i) => {
                    newItems[i].isOpen = i === index ? !item.isOpen : false;
                });
            } else {
                newItems[index].isOpen = !newItems[index].isOpen;
            }

            setAttributes({ items: newItems });
        };

        const getIconPreview = (iconType) => {
            switch (iconType) {
                case 'chevron':
                    return '›';
                case 'arrow':
                    return '→';
                case 'plus':
                default:
                    return '+';
            }
        };

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Accordion Settings', initialOpen: true },
                    wp.element.createElement(SelectControl, {
                        label: 'Style',
                        value: style,
                        options: [
                            { label: 'Modern', value: 'modern' },
                            { label: 'Minimal', value: 'minimal' },
                            { label: 'Boxed', value: 'boxed' },
                            { label: 'Outlined', value: 'outlined' }
                        ],
                        onChange: (value) => setAttributes({ style: value })
                    }),

                    wp.element.createElement(SelectControl, {
                        label: 'Icon Style',
                        value: iconStyle,
                        options: [
                            { label: 'Plus/Minus', value: 'plus' },
                            { label: 'Chevron', value: 'chevron' },
                            { label: 'Arrow', value: 'arrow' }
                        ],
                        onChange: (value) => setAttributes({ iconStyle: value })
                    }),

                    wp.element.createElement(ToggleControl, {
                        label: 'Allow Multiple Open',
                        checked: allowMultiple,
                        onChange: (value) => setAttributes({ allowMultiple: value }),
                        help: 'Allow multiple accordion items to be open simultaneously'
                    }),

                    wp.element.createElement(ToggleControl, {
                        label: 'Open First Item by Default',
                        checked: openFirst,
                        onChange: (value) => {
                            setAttributes({ openFirst: value });
                            if (value) {
                                const newItems = [...items];
                                newItems[0] = { ...newItems[0], isOpen: true };
                                setAttributes({ items: newItems });
                            }
                        },
                        help: 'Automatically open the first accordion item'
                    })
                ),

                wp.element.createElement(PanelBody, { title: 'Accordion Items', initialOpen: false },
                    wp.element.createElement('div', { className: 'flex justify-between items-center mb-4' },
                        wp.element.createElement(Button, {
                            variant: 'primary',
                            onClick: addItem
                        }, 'Add Item'),
                        wp.element.createElement('span', { className: 'text-sm text-gray-600' },
                            `${items.length} items`
                        )
                    ),

                    wp.element.createElement('div', { className: 'mb-4' },
                        wp.element.createElement('div', { className: 'space-y-2 max-h-48 overflow-y-auto' },
                            items.map((item, index) =>
                                wp.element.createElement('button', {
                                        key: index,
                                        className: `w-full text-left px-3 py-2 text-sm rounded border transition-colors ${
                                            activeItem === index
                                                ? 'bg-blue-50 border-blue-300 text-blue-900'
                                                : 'bg-gray-50 border-gray-200 text-gray-700 hover:bg-gray-100'
                                        }`,
                                        onClick: () => setActiveItem(index)
                                    },
                                    wp.element.createElement('div', { className: 'flex items-center justify-between' },
                                        wp.element.createElement('span', { className: 'truncate flex-1' },
                                            item.question || `Item ${index + 1}`
                                        ),
                                        wp.element.createElement('div', { className: 'flex items-center space-x-1 ml-2' },
                                            item.isOpen && wp.element.createElement('span', {
                                                className: 'text-xs bg-green-100 text-green-800 px-1 rounded'
                                            }, 'Open'),
                                            wp.element.createElement('span', {
                                                className: 'text-gray-400'
                                            }, getIconPreview(iconStyle))
                                        )
                                    )
                                )
                            )
                        )
                    ),

                    items.length > 0 && wp.element.createElement(Card, { className: 'mb-4' },
                        wp.element.createElement(CardBody, null,
                            wp.element.createElement('div', { className: 'flex justify-between items-start mb-4' },
                                wp.element.createElement('h4', { className: 'text-sm font-medium' },
                                    `Item ${activeItem + 1} Settings`
                                ),
                                wp.element.createElement('div', { className: 'flex space-x-1' },
                                    activeItem > 0 && wp.element.createElement(Button, {
                                        variant: 'secondary',
                                        isSmall: true,
                                        onClick: () => moveItem(activeItem, 'up'),
                                        title: 'Move up'
                                    }, '↑'),
                                    activeItem < items.length - 1 && wp.element.createElement(Button, {
                                        variant: 'secondary',
                                        isSmall: true,
                                        onClick: () => moveItem(activeItem, 'down'),
                                        title: 'Move down'
                                    }, '↓'),
                                    wp.element.createElement(Button, {
                                        variant: 'secondary',
                                        isSmall: true,
                                        onClick: () => duplicateItem(activeItem),
                                        title: 'Duplicate'
                                    }, 'Copy'),
                                    items.length > 1 && wp.element.createElement(Button, {
                                        variant: 'secondary',
                                        isSmall: true,
                                        isDestructive: true,
                                        onClick: () => removeItem(activeItem),
                                        title: 'Remove'
                                    }, '×')
                                )
                            ),

                            wp.element.createElement(TextControl, {
                                label: 'Question',
                                value: items[activeItem]?.question || '',
                                onChange: (value) => updateItem(activeItem, 'question', value),
                                placeholder: 'Enter your question here...'
                            }),

                            wp.element.createElement('label', { className: 'block text-sm font-medium mb-2 mt-4' }, 'Answer'),
                            wp.element.createElement('textarea', {
                                value: items[activeItem]?.answer || '',
                                onChange: (e) => updateItem(activeItem, 'answer', e.target.value),
                                rows: 4,
                                className: 'w-full px-3 py-2 border border-gray-300 rounded-md mb-4',
                                placeholder: 'Enter the answer or explanation here...'
                            }),

                            wp.element.createElement(ToggleControl, {
                                label: 'Open by Default',
                                checked: items[activeItem]?.isOpen || false,
                                onChange: (value) => updateItem(activeItem, 'isOpen', value),
                                help: 'This item will be open when the page loads'
                            })
                        )
                    )
                )
            ),

            // Editor Preview
            wp.element.createElement('div', {
                    className: 'accordion-editor py-8 px-4 bg-gray-50 rounded-lg'
                },
                wp.element.createElement('div', { className: 'text-center mb-8 max-w-4xl mx-auto' },
                    wp.element.createElement(RichText, {
                        tagName: 'h2',
                        className: 'text-3xl font-bold text-gray-900 mb-4',
                        value: title,
                        onChange: (value) => setAttributes({ title: value }),
                        placeholder: 'Accordion section title...'
                    }),
                    wp.element.createElement(RichText, {
                        tagName: 'p',
                        className: 'text-lg text-gray-600',
                        value: subtitle,
                        onChange: (value) => setAttributes({ subtitle: value }),
                        placeholder: 'Accordion section subtitle...'
                    })
                ),

                wp.element.createElement('div', {
                        className: 'accordion-preview max-w-4xl mx-auto space-y-4'
                    },
                    items.map((item, index) =>
                        wp.element.createElement('div', {
                                key: index,
                                className: `accordion-item-preview bg-white rounded-lg shadow-sm border-2 border-dashed border-gray-300 overflow-hidden ${
                                    activeItem === index ? 'ring-2 ring-blue-500' : ''
                                }`
                            },
                            wp.element.createElement('button', {
                                    className: 'w-full text-left p-4 flex items-center justify-between hover:bg-gray-50 transition-colors',
                                    onClick: () => {
                                        setActiveItem(index);
                                        toggleItem(index);
                                    }
                                },
                                wp.element.createElement('span', {
                                    className: 'font-medium text-gray-900 flex-1 pr-4'
                                }, item.question || `Question ${index + 1}`),

                                wp.element.createElement('div', { className: 'flex items-center space-x-2' },
                                    activeItem === index && wp.element.createElement('span', {
                                        className: 'text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded'
                                    }, 'Editing'),
                                    wp.element.createElement('span', {
                                        className: `text-lg font-bold text-blue-600 transition-transform duration-200 ${
                                            item.isOpen ? 'transform rotate-180' : ''
                                        }`
                                    }, getIconPreview(iconStyle))
                                )
                            ),

                            // Answer Preview
                            item.isOpen && wp.element.createElement('div', {
                                    className: 'px-4 pb-4'
                                },
                                wp.element.createElement('div', {
                                    className: 'text-gray-700 bg-gray-50 p-4 rounded border-t border-gray-200'
                                }, item.answer || 'Answer content will appear here...')
                            )
                        )
                    )
                ),

                // Quick Settings
                wp.element.createElement('div', {
                        className: 'mt-8 p-4 bg-white rounded-lg border-2 border-dashed border-gray-300'
                    },
                    wp.element.createElement('h4', { className: 'text-sm font-medium mb-3' }, 'Quick Settings'),
                    wp.element.createElement('div', { className: 'grid grid-cols-2 md:grid-cols-4 gap-3' },
                        wp.element.createElement('label', { className: 'flex items-center space-x-2 text-sm' },
                            wp.element.createElement('input', {
                                type: 'checkbox',
                                checked: allowMultiple,
                                onChange: (e) => setAttributes({ allowMultiple: e.target.checked })
                            }),
                            wp.element.createElement('span', null, 'Multiple Open')
                        ),
                        wp.element.createElement('label', { className: 'flex items-center space-x-2 text-sm' },
                            wp.element.createElement('input', {
                                type: 'checkbox',
                                checked: openFirst,
                                onChange: (e) => setAttributes({ openFirst: e.target.checked })
                            }),
                            wp.element.createElement('span', null, 'Open First')
                        ),
                        wp.element.createElement('div', { className: 'flex items-center space-x-2' },
                            wp.element.createElement('label', { className: 'text-sm' }, 'Style:'),
                            wp.element.createElement('select', {
                                    value: style,
                                    onChange: (e) => setAttributes({ style: e.target.value }),
                                    className: 'text-sm border border-gray-300 rounded px-2 py-1'
                                },
                                wp.element.createElement('option', { value: 'modern' }, 'Modern'),
                                wp.element.createElement('option', { value: 'minimal' }, 'Minimal'),
                                wp.element.createElement('option', { value: 'boxed' }, 'Boxed'),
                                wp.element.createElement('option', { value: 'outlined' }, 'Outlined')
                            )
                        ),
                        wp.element.createElement('div', { className: 'flex items-center space-x-2' },
                            wp.element.createElement('label', { className: 'text-sm' }, 'Icon:'),
                            wp.element.createElement('select', {
                                    value: iconStyle,
                                    onChange: (e) => setAttributes({ iconStyle: e.target.value }),
                                    className: 'text-sm border border-gray-300 rounded px-2 py-1'
                                },
                                wp.element.createElement('option', { value: 'plus' }, 'Plus'),
                                wp.element.createElement('option', { value: 'chevron' }, 'Chevron'),
                                wp.element.createElement('option', { value: 'arrow' }, 'Arrow')
                            )
                        )
                    ),

                    wp.element.createElement('div', { className: 'mt-4 pt-3 border-t border-gray-200' },
                        wp.element.createElement('div', { className: 'flex items-center justify-between text-sm text-gray-600' },
                            wp.element.createElement('span', null, `${items.length} items total`),
                            wp.element.createElement('span', null, `${items.filter(item => item.isOpen).length} open by default`)
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