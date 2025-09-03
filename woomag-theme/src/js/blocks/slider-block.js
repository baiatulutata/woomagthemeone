const { registerBlockType } = wp.blocks;
const { RichText, MediaUpload, InspectorControls } = wp.blockEditor;
const { PanelBody, Button, TextControl, ToggleControl, SelectControl, RangeControl, Card, CardBody } = wp.components;
const { useState, Fragment } = wp.element;

registerBlockType('woomag-theme/slider', {
    title: 'Swiper Slider',
    icon: 'images-alt2',
    category: 'woomag-theme',
    description: 'Create beautiful, responsive sliders with Swiper.js integration.',
    keywords: ['slider', 'carousel', 'swiper', 'slideshow', 'gallery'],

    attributes: {
        sliderType: {
            type: 'string',
            default: 'image'
        },
        autoplay: {
            type: 'boolean',
            default: true
        },
        autoplayDelay: {
            type: 'number',
            default: 5000
        },
        loop: {
            type: 'boolean',
            default: true
        },
        navigation: {
            type: 'boolean',
            default: true
        },
        pagination: {
            type: 'boolean',
            default: true
        },
        effect: {
            type: 'string',
            default: 'slide'
        },
        slidesPerView: {
            type: 'number',
            default: 1
        },
        spaceBetween: {
            type: 'number',
            default: 30
        },
        centeredSlides: {
            type: 'boolean',
            default: false
        },
        height: {
            type: 'string',
            default: 'medium'
        },
        slides: {
            type: 'array',
            default: [
                {
                    type: 'image',
                    image: '',
                    title: 'Welcome to WoomagOne',
                    subtitle: 'Premium WordPress Theme',
                    content: 'Create amazing websites with our modern, feature-rich theme.',
                    buttonText: 'Get Started',
                    buttonUrl: '#',
                    overlayOpacity: 50
                },
                {
                    type: 'image',
                    image: '',
                    title: 'Powerful Features',
                    subtitle: 'Built for Performance',
                    content: 'Tailwind CSS, dark mode, accessibility, and much more.',
                    buttonText: 'Learn More',
                    buttonUrl: '#',
                    overlayOpacity: 50
                },
                {
                    type: 'image',
                    image: '',
                    title: 'Ready to Launch',
                    subtitle: 'Start Building Today',
                    content: 'Everything you need to create professional websites.',
                    buttonText: 'Contact Us',
                    buttonUrl: '#',
                    overlayOpacity: 50
                }
            ]
        }
    },

    edit: function(props) {
        const { attributes, setAttributes } = props;
        const {
            sliderType, autoplay, autoplayDelay, loop, navigation, pagination,
            effect, slidesPerView, spaceBetween, centeredSlides, height, slides
        } = attributes;

        const [activeSlide, setActiveSlide] = useState(0);

        const updateSlide = (index, field, value) => {
            const newSlides = [...slides];
            newSlides[index] = { ...newSlides[index], [field]: value };
            setAttributes({ slides: newSlides });
        };

        const addSlide = () => {
            const newSlides = [...slides, {
                type: 'image',
                image: '',
                title: `Slide ${slides.length + 1} Title`,
                subtitle: 'Slide subtitle',
                content: 'Slide content goes here.',
                buttonText: 'Learn More',
                buttonUrl: '#',
                overlayOpacity: 50
            }];
            setAttributes({ slides: newSlides });
        };

        const removeSlide = (index) => {
            if (slides.length > 1) {
                const newSlides = slides.filter((_, i) => i !== index);
                setAttributes({ slides: newSlides });
                if (activeSlide >= newSlides.length) {
                    setActiveSlide(newSlides.length - 1);
                }
            }
        };

        const duplicateSlide = (index) => {
            const newSlides = [...slides];
            const duplicatedSlide = { ...slides[index], title: slides[index].title + ' (Copy)' };
            newSlides.splice(index + 1, 0, duplicatedSlide);
            setAttributes({ slides: newSlides });
        };

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Slider Settings', initialOpen: true },
                    wp.element.createElement(SelectControl, {
                        label: 'Slider Type',
                        value: sliderType,
                        options: [
                            { label: 'Image Slider', value: 'image' },
                            { label: 'Content Slider', value: 'content' }
                        ],
                        onChange: (value) => setAttributes({ sliderType: value })
                    }),

                    wp.element.createElement(SelectControl, {
                        label: 'Height',
                        value: height,
                        options: [
                            { label: 'Auto', value: 'auto' },
                            { label: 'Small', value: 'small' },
                            { label: 'Medium', value: 'medium' },
                            { label: 'Large', value: 'large' },
                            { label: 'Full Screen', value: 'fullscreen' }
                        ],
                        onChange: (value) => setAttributes({ height: value })
                    }),

                    wp.element.createElement(SelectControl, {
                        label: 'Effect',
                        value: effect,
                        options: [
                            { label: 'Slide', value: 'slide' },
                            { label: 'Fade', value: 'fade' },
                            { label: 'Cube', value: 'cube' },
                            { label: 'Coverflow', value: 'coverflow' },
                            { label: 'Flip', value: 'flip' }
                        ],
                        onChange: (value) => setAttributes({ effect: value })
                    }),

                    wp.element.createElement(RangeControl, {
                        label: 'Slides Per View',
                        value: slidesPerView,
                        onChange: (value) => setAttributes({ slidesPerView: value }),
                        min: 1,
                        max: 5,
                        help: 'How many slides to show at once'
                    }),

                    wp.element.createElement(RangeControl, {
                        label: 'Space Between Slides',
                        value: spaceBetween,
                        onChange: (value) => setAttributes({ spaceBetween: value }),
                        min: 0,
                        max: 100
                    }),

                    wp.element.createElement(ToggleControl, {
                        label: 'Centered Slides',
                        checked: centeredSlides,
                        onChange: (value) => setAttributes({ centeredSlides: value })
                    })
                ),

                wp.element.createElement(PanelBody, { title: 'Autoplay & Navigation', initialOpen: false },
                    wp.element.createElement(ToggleControl, {
                        label: 'Autoplay',
                        checked: autoplay,
                        onChange: (value) => setAttributes({ autoplay: value })
                    }),

                    autoplay && wp.element.createElement(RangeControl, {
                        label: 'Autoplay Delay (ms)',
                        value: autoplayDelay,
                        onChange: (value) => setAttributes({ autoplayDelay: value }),
                        min: 1000,
                        max: 10000,
                        step: 500
                    }),

                    wp.element.createElement(ToggleControl, {
                        label: 'Loop',
                        checked: loop,
                        onChange: (value) => setAttributes({ loop: value })
                    }),

                    wp.element.createElement(ToggleControl, {
                        label: 'Navigation Arrows',
                        checked: navigation,
                        onChange: (value) => setAttributes({ navigation: value })
                    }),

                    wp.element.createElement(ToggleControl, {
                        label: 'Pagination Dots',
                        checked: pagination,
                        onChange: (value) => setAttributes({ pagination: value })
                    })
                ),

                wp.element.createElement(PanelBody, { title: 'Slides', initialOpen: false },
                    wp.element.createElement('div', { className: 'flex justify-between items-center mb-4' },
                        wp.element.createElement(Button, {
                            variant: 'primary',
                            onClick: addSlide
                        }, 'Add Slide'),
                        wp.element.createElement('span', { className: 'text-sm text-gray-600' },
                            `${slides.length} slides`
                        )
                    ),

                    wp.element.createElement('div', { className: 'mb-4' },
                        wp.element.createElement('div', { className: 'flex space-x-2 overflow-x-auto pb-2' },
                            slides.map((slide, index) =>
                                wp.element.createElement('button', {
                                    key: index,
                                    className: `px-3 py-2 text-xs rounded transition-colors ${
                                        activeSlide === index
                                            ? 'bg-blue-600 text-white'
                                            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                                    }`,
                                    onClick: () => setActiveSlide(index)
                                }, `Slide ${index + 1}`)
                            )
                        )
                    ),

                    slides.length > 0 && wp.element.createElement(Card, { className: 'mb-4' },
                        wp.element.createElement(CardBody, null,
                            wp.element.createElement('div', { className: 'flex justify-between items-center mb-4' },
                                wp.element.createElement('h4', { className: 'text-sm font-medium' },
                                    `Slide ${activeSlide + 1} Settings`
                                ),
                                wp.element.createElement('div', { className: 'flex space-x-2' },
                                    wp.element.createElement(Button, {
                                        variant: 'secondary',
                                        isSmall: true,
                                        onClick: () => duplicateSlide(activeSlide)
                                    }, 'Duplicate'),
                                    slides.length > 1 && wp.element.createElement(Button, {
                                        variant: 'secondary',
                                        isSmall: true,
                                        isDestructive: true,
                                        onClick: () => removeSlide(activeSlide)
                                    }, 'Remove')
                                )
                            ),

                            // Image Upload
                            wp.element.createElement('div', { className: 'mb-4' },
                                wp.element.createElement('label', { className: 'block text-sm font-medium mb-2' }, 'Background Image'),
                                wp.element.createElement(MediaUpload, {
                                    onSelect: (media) => updateSlide(activeSlide, 'image', media.url),
                                    allowedTypes: ['image'],
                                    render: ({ open }) => wp.element.createElement(Button, {
                                        onClick: open,
                                        variant: slides[activeSlide]?.image ? 'secondary' : 'primary',
                                        className: 'mb-2'
                                    }, slides[activeSlide]?.image ? 'Change Image' : 'Select Image')
                                }),
                                slides[activeSlide]?.image && wp.element.createElement('img', {
                                    src: slides[activeSlide].image,
                                    alt: slides[activeSlide].title,
                                    className: 'w-full h-32 object-cover rounded mt-2'
                                })
                            ),

                            // Overlay Opacity
                            slides[activeSlide]?.image && wp.element.createElement(RangeControl, {
                                label: 'Overlay Opacity',
                                value: slides[activeSlide]?.overlayOpacity || 50,
                                onChange: (value) => updateSlide(activeSlide, 'overlayOpacity', value),
                                min: 0,
                                max: 100
                            }),

                            // Text Fields
                            wp.element.createElement(TextControl, {
                                label: 'Subtitle',
                                value: slides[activeSlide]?.subtitle || '',
                                onChange: (value) => updateSlide(activeSlide, 'subtitle', value)
                            }),

                            wp.element.createElement(TextControl, {
                                label: 'Title',
                                value: slides[activeSlide]?.title || '',
                                onChange: (value) => updateSlide(activeSlide, 'title', value)
                            }),

                            wp.element.createElement('label', { className: 'block text-sm font-medium mb-2' }, 'Content'),
                            wp.element.createElement('textarea', {
                                value: slides[activeSlide]?.content || '',
                                onChange: (e) => updateSlide(activeSlide, 'content', e.target.value),
                                rows: 3,
                                className: 'w-full px-3 py-2 border border-gray-300 rounded-md mb-4'
                            }),

                            wp.element.createElement(TextControl, {
                                label: 'Button Text',
                                value: slides[activeSlide]?.buttonText || '',
                                onChange: (value) => updateSlide(activeSlide, 'buttonText', value)
                            }),

                            wp.element.createElement(TextControl, {
                                label: 'Button URL',
                                value: slides[activeSlide]?.buttonUrl || '',
                                onChange: (value) => updateSlide(activeSlide, 'buttonUrl', value),
                                type: 'url'
                            })
                        )
                    )
                )
            ),

            // Editor Preview
            wp.element.createElement('div', {
                    className: 'woomag-slider-editor bg-gray-900 rounded-lg overflow-hidden',
                    style: { minHeight: '400px' }
                },
                wp.element.createElement('div', { className: 'relative h-full' },
                    // Slide Preview
                    slides.length > 0 && wp.element.createElement('div', {
                            className: 'relative h-full min-h-96 flex items-center justify-center',
                            style: {
                                backgroundImage: slides[activeSlide]?.image
                                    ? `linear-gradient(rgba(0,0,0,${(slides[activeSlide].overlayOpacity || 50) / 100}), rgba(0,0,0,${(slides[activeSlide].overlayOpacity || 50) / 100})), url(${slides[activeSlide].image})`
                                    : 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)',
                                backgroundSize: 'cover',
                                backgroundPosition: 'center'
                            }
                        },
                        wp.element.createElement('div', { className: 'text-center text-white px-6 max-w-4xl' },
                            slides[activeSlide]?.subtitle && wp.element.createElement('p', {
                                className: 'text-sm font-medium opacity-80 mb-4 uppercase tracking-wider'
                            }, slides[activeSlide].subtitle),

                            slides[activeSlide]?.title && wp.element.createElement('h2', {
                                className: 'text-4xl md:text-5xl font-bold mb-6 leading-tight'
                            }, slides[activeSlide].title),

                            slides[activeSlide]?.content && wp.element.createElement('p', {
                                className: 'text-lg opacity-90 mb-8 max-w-2xl mx-auto'
                            }, slides[activeSlide].content),

                            slides[activeSlide]?.buttonText && wp.element.createElement('button', {
                                className: 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors'
                            }, slides[activeSlide].buttonText)
                        )
                    ),

                    // Navigation Preview
                    navigation && wp.element.createElement(Fragment, null,
                        wp.element.createElement('button', {
                            className: 'absolute left-4 top-1/2 transform -translate-y-1/2 w-10 h-10 bg-black/20 text-white rounded-full flex items-center justify-center hover:bg-blue-600/80 transition-colors',
                            onClick: () => setActiveSlide(activeSlide > 0 ? activeSlide - 1 : slides.length - 1)
                        }, '‹'),
                        wp.element.createElement('button', {
                            className: 'absolute right-4 top-1/2 transform -translate-y-1/2 w-10 h-10 bg-black/20 text-white rounded-full flex items-center justify-center hover:bg-blue-600/80 transition-colors',
                            onClick: () => setActiveSlide(activeSlide < slides.length - 1 ? activeSlide + 1 : 0)
                        }, '›')
                    ),

                    // Pagination Preview
                    pagination && wp.element.createElement('div', {
                            className: 'absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2'
                        },
                        slides.map((_, index) =>
                            wp.element.createElement('button', {
                                key: index,
                                className: `w-3 h-3 rounded-full transition-colors ${
                                    index === activeSlide ? 'bg-blue-500' : 'bg-white/50'
                                }`,
                                onClick: () => setActiveSlide(index)
                            })
                        )
                    ),

                    // Settings Overlay
                    wp.element.createElement('div', {
                        className: 'absolute top-4 left-4 bg-black/50 text-white text-xs px-3 py-2 rounded-full'
                    }, `${effect} • ${slidesPerView} per view • ${autoplay ? 'Auto' : 'Manual'}`)
                )
            ),

            // Quick Settings
            wp.element.createElement('div', {
                    className: 'mt-4 p-4 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300'
                },
                wp.element.createElement('h4', { className: 'text-sm font-medium mb-3' }, 'Quick Settings'),
                wp.element.createElement('div', { className: 'grid grid-cols-2 md:grid-cols-4 gap-3' },
                    wp.element.createElement('label', { className: 'flex items-center space-x-2 text-sm' },
                        wp.element.createElement('input', {
                            type: 'checkbox',
                            checked: autoplay,
                            onChange: (e) => setAttributes({ autoplay: e.target.checked })
                        }),
                        wp.element.createElement('span', null, 'Autoplay')
                    ),
                    wp.element.createElement('label', { className: 'flex items-center space-x-2 text-sm' },
                        wp.element.createElement('input', {
                            type: 'checkbox',
                            checked: loop,
                            onChange: (e) => setAttributes({ loop: e.target.checked })
                        }),
                        wp.element.createElement('span', null, 'Loop')
                    ),
                    wp.element.createElement('label', { className: 'flex items-center space-x-2 text-sm' },
                        wp.element.createElement('input', {
                            type: 'checkbox',
                            checked: navigation,
                            onChange: (e) => setAttributes({ navigation: e.target.checked })
                        }),
                        wp.element.createElement('span', null, 'Navigation')
                    ),
                    wp.element.createElement('label', { className: 'flex items-center space-x-2 text-sm' },
                        wp.element.createElement('input', {
                            type: 'checkbox',
                            checked: pagination,
                            onChange: (e) => setAttributes({ pagination: e.target.checked })
                        }),
                        wp.element.createElement('span', null, 'Pagination')
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