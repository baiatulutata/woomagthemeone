const { registerBlockType } = wp.blocks;
const { RichText, InspectorControls } = wp.blockEditor;
const { PanelBody, Button, TextControl, ToggleControl, SelectControl, RangeControl, Card, CardBody } = wp.components;
const { useState, Fragment } = wp.element;

registerBlockType('woomag-theme/pricing-tables', {
    title: 'Pricing Tables',
    icon: 'money-alt',
    category: 'woomag-theme',
    description: 'Create beautiful pricing tables with monthly/yearly toggle and feature comparisons.',
    keywords: ['pricing', 'plans', 'tables', 'subscription', 'payment'],

    attributes: {
        title: {
            type: 'string',
            default: 'Choose Your Plan'
        },
        subtitle: {
            type: 'string',
            default: 'Select the perfect plan for your needs'
        },
        billingToggle: {
            type: 'boolean',
            default: true
        },
        currency: {
            type: 'string',
            default: '$'
        },
        style: {
            type: 'string',
            default: 'modern'
        },
        columns: {
            type: 'number',
            default: 3
        },
        plans: {
            type: 'array',
            default: [
                {
                    name: 'Starter',
                    description: 'Perfect for individuals and small projects',
                    monthlyPrice: 9,
                    yearlyPrice: 90,
                    featured: false,
                    badge: '',
                    features: [
                        { text: '5 Projects', included: true },
                        { text: '10GB Storage', included: true },
                        { text: 'Email Support', included: true },
                        { text: 'Advanced Analytics', included: false },
                        { text: 'Priority Support', included: false }
                    ],
                    buttonText: 'Get Started',
                    buttonUrl: '#',
                    buttonStyle: 'secondary'
                },
                {
                    name: 'Professional',
                    description: 'Great for growing businesses and teams',
                    monthlyPrice: 29,
                    yearlyPrice: 290,
                    featured: true,
                    badge: 'Most Popular',
                    features: [
                        { text: '50 Projects', included: true },
                        { text: '100GB Storage', included: true },
                        { text: 'Email Support', included: true },
                        { text: 'Advanced Analytics', included: true },
                        { text: 'Priority Support', included: true }
                    ],
                    buttonText: 'Start Free Trial',
                    buttonUrl: '#',
                    buttonStyle: 'primary'
                },
                {
                    name: 'Enterprise',
                    description: 'For large organizations with custom needs',
                    monthlyPrice: 99,
                    yearlyPrice: 990,
                    featured: false,
                    badge: '',
                    features: [
                        { text: 'Unlimited Projects', included: true },
                        { text: '1TB Storage', included: true },
                        { text: '24/7 Phone Support', included: true },
                        { text: 'Advanced Analytics', included: true },
                        { text: 'Custom Integrations', included: true }
                    ],
                    buttonText: 'Contact Sales',
                    buttonUrl: '#',
                    buttonStyle: 'secondary'
                }
            ]
        }
    },

    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { title, subtitle, billingToggle, currency, style, columns, plans } = attributes;

        const [activePlan, setActivePlan] = useState(0);
        const [showYearlyPrices, setShowYearlyPrices] = useState(false);

        const updatePlan = (index, field, value) => {
            const newPlans = [...plans];
            newPlans[index] = { ...newPlans[index], [field]: value };
            setAttributes({ plans: newPlans });
        };

        const updateFeature = (planIndex, featureIndex, field, value) => {
            const newPlans = [...plans];
            const newFeatures = [...newPlans[planIndex].features];
            newFeatures[featureIndex] = { ...newFeatures[featureIndex], [field]: value };
            newPlans[planIndex] = { ...newPlans[planIndex], features: newFeatures };
            setAttributes({ plans: newPlans });
        };

        const addPlan = () => {
            const newPlans = [...plans, {
                name: `Plan ${plans.length + 1}`,
                description: 'Plan description',
                monthlyPrice: 19,
                yearlyPrice: 190,
                featured: false,
                badge: '',
                features: [
                    { text: 'Feature 1', included: true },
                    { text: 'Feature 2', included: true },
                    { text: 'Feature 3', included: false }
                ],
                buttonText: 'Get Started',
                buttonUrl: '#',
                buttonStyle: 'secondary'
            }];
            setAttributes({ plans: newPlans });
        };

        const removePlan = (index) => {
            if (plans.length > 1) {
                const newPlans = plans.filter((_, i) => i !== index);
                setAttributes({ plans: newPlans });
                if (activePlan >= newPlans.length) {
                    setActivePlan(newPlans.length - 1);
                }
            }
        };

        const addFeature = (planIndex) => {
            const newPlans = [...plans];
            newPlans[planIndex].features.push({ text: 'New Feature', included: true });
            setAttributes({ plans: newPlans });
        };

        const removeFeature = (planIndex, featureIndex) => {
            const newPlans = [...plans];
            newPlans[planIndex].features = newPlans[planIndex].features.filter((_, i) => i !== featureIndex);
            setAttributes({ plans: newPlans });
        };

        const gridClasses = {
            1: 'repeat(1, 1fr)',
            2: 'repeat(auto-fit, minmax(300px, 1fr))',
            3: 'repeat(auto-fit, minmax(280px, 1fr))',
            4: 'repeat(auto-fit, minmax(250px, 1fr))'
        };

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Pricing Settings', initialOpen: true },
                    wp.element.createElement(SelectControl, {
                        label: 'Style',
                        value: style,
                        options: [
                            { label: 'Modern', value: 'modern' },
                            { label: 'Minimal', value: 'minimal' },
                            { label: 'Gradient', value: 'gradient' },
                            { label: 'Outlined', value: 'outlined' }
                        ],
                        onChange: (value) => setAttributes({ style: value })
                    }),

                    wp.element.createElement(RangeControl, {
                        label: 'Columns',
                        value: columns,
                        onChange: (value) => setAttributes({ columns: value }),
                        min: 1,
                        max: 4
                    }),

                    wp.element.createElement(TextControl, {
                        label: 'Currency Symbol',
                        value: currency,
                        onChange: (value) => setAttributes({ currency: value }),
                        help: 'e.g., $, €, £, ¥'
                    }),

                    wp.element.createElement(ToggleControl, {
                        label: 'Show Billing Toggle',
                        checked: billingToggle,
                        onChange: (value) => setAttributes({ billingToggle: value }),
                        help: 'Allow users to switch between monthly and yearly pricing'
                    })
                ),

                wp.element.createElement(PanelBody, { title: 'Plans Management', initialOpen: false },
                    wp.element.createElement('div', { className: 'flex justify-between items-center mb-4' },
                        wp.element.createElement(Button, {
                            variant: 'primary',
                            onClick: addPlan
                        }, 'Add Plan'),
                        wp.element.createElement('span', { className: 'text-sm text-gray-600' },
                            `${plans.length} plans`
                        )
                    ),

                    wp.element.createElement('div', { className: 'mb-4' },
                        wp.element.createElement('div', { className: 'flex space-x-2 overflow-x-auto pb-2' },
                            plans.map((plan, index) =>
                                wp.element.createElement('button', {
                                    key: index,
                                    className: `px-3 py-2 text-xs rounded transition-colors ${
                                        activePlan === index
                                            ? 'bg-blue-600 text-white'
                                            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                                    }`,
                                    onClick: () => setActivePlan(index)
                                }, plan.name || `Plan ${index + 1}`)
                            )
                        )
                    ),

                    plans.length > 0 && wp.element.createElement(Card, { className: 'mb-4' },
                        wp.element.createElement(CardBody, null,
                            wp.element.createElement('div', { className: 'flex justify-between items-center mb-4' },
                                wp.element.createElement('h4', { className: 'text-sm font-medium' },
                                    `${plans[activePlan]?.name || 'Plan'} Settings`
                                ),
                                plans.length > 1 && wp.element.createElement(Button, {
                                    variant: 'secondary',
                                    isSmall: true,
                                    isDestructive: true,
                                    onClick: () => removePlan(activePlan)
                                }, 'Remove Plan')
                            ),

                            // Basic Plan Settings
                            wp.element.createElement(TextControl, {
                                label: 'Plan Name',
                                value: plans[activePlan]?.name || '',
                                onChange: (value) => updatePlan(activePlan, 'name', value)
                            }),

                            wp.element.createElement('label', { className: 'block text-sm font-medium mb-2' }, 'Description'),
                            wp.element.createElement('textarea', {
                                value: plans[activePlan]?.description || '',
                                onChange: (e) => updatePlan(activePlan, 'description', e.target.value),
                                rows: 2,
                                className: 'w-full px-3 py-2 border border-gray-300 rounded-md mb-4'
                            }),

                            // Pricing
                            wp.element.createElement('div', { className: 'grid grid-cols-2 gap-4 mb-4' },
                                wp.element.createElement(TextControl, {
                                    label: 'Monthly Price',
                                    type: 'number',
                                    value: plans[activePlan]?.monthlyPrice || 0,
                                    onChange: (value) => updatePlan(activePlan, 'monthlyPrice', parseFloat(value))
                                }),
                                wp.element.createElement(TextControl, {
                                    label: 'Yearly Price',
                                    type: 'number',
                                    value: plans[activePlan]?.yearlyPrice || 0,
                                    onChange: (value) => updatePlan(activePlan, 'yearlyPrice', parseFloat(value))
                                })
                            ),

                            // Featured & Badge
                            wp.element.createElement('div', { className: 'grid grid-cols-2 gap-4 mb-4' },
                                wp.element.createElement(ToggleControl, {
                                    label: 'Featured Plan',
                                    checked: plans[activePlan]?.featured || false,
                                    onChange: (value) => updatePlan(activePlan, 'featured', value)
                                }),
                                wp.element.createElement(TextControl, {
                                    label: 'Badge Text',
                                    value: plans[activePlan]?.badge || '',
                                    onChange: (value) => updatePlan(activePlan, 'badge', value),
                                    help: 'e.g., "Most Popular", "Best Value"'
                                })
                            ),

                            // Button Settings
                            wp.element.createElement('div', { className: 'grid grid-cols-2 gap-4 mb-4' },
                                wp.element.createElement(TextControl, {
                                    label: 'Button Text',
                                    value: plans[activePlan]?.buttonText || '',
                                    onChange: (value) => updatePlan(activePlan, 'buttonText', value)
                                }),
                                wp.element.createElement(SelectControl, {
                                    label: 'Button Style',
                                    value: plans[activePlan]?.buttonStyle || 'primary',
                                    options: [
                                        { label: 'Primary', value: 'primary' },
                                        { label: 'Secondary', value: 'secondary' }
                                    ],
                                    onChange: (value) => updatePlan(activePlan, 'buttonStyle', value)
                                })
                            ),

                            wp.element.createElement(TextControl, {
                                label: 'Button URL',
                                type: 'url',
                                value: plans[activePlan]?.buttonUrl || '',
                                onChange: (value) => updatePlan(activePlan, 'buttonUrl', value)
                            }),

                            // Features
                            wp.element.createElement('div', { className: 'mt-6' },
                                wp.element.createElement('div', { className: 'flex justify-between items-center mb-3' },
                                    wp.element.createElement('h5', { className: 'text-sm font-medium' }, 'Features'),
                                    wp.element.createElement(Button, {
                                        variant: 'secondary',
                                        isSmall: true,
                                        onClick: () => addFeature(activePlan)
                                    }, 'Add Feature')
                                ),

                                plans[activePlan]?.features?.map((feature, featureIndex) =>
                                    wp.element.createElement('div', {
                                            key: featureIndex,
                                            className: 'flex items-center space-x-3 mb-3 p-3 bg-gray-50 rounded'
                                        },
                                        wp.element.createElement('input', {
                                            type: 'checkbox',
                                            checked: feature.included,
                                            onChange: (e) => updateFeature(activePlan, featureIndex, 'included', e.target.checked),
                                            className: 'rounded'
                                        }),
                                        wp.element.createElement('input', {
                                            type: 'text',
                                            value: feature.text,
                                            onChange: (e) => updateFeature(activePlan, featureIndex, 'text', e.target.value),
                                            className: 'flex-1 px-2 py-1 border border-gray-300 rounded text-sm'
                                        }),
                                        wp.element.createElement(Button, {
                                            variant: 'secondary',
                                            isSmall: true,
                                            isDestructive: true,
                                            onClick: () => removeFeature(activePlan, featureIndex)
                                        }, '×')
                                    )
                                )
                            )
                        )
                    )
                )
            ),

            // Editor Preview
            wp.element.createElement('div', {
                    className: 'pricing-tables-editor py-8 px-4 bg-gray-50 rounded-lg'
                },
                wp.element.createElement('div', { className: 'text-center mb-8' },
                    wp.element.createElement(RichText, {
                        tagName: 'h2',
                        className: 'text-3xl font-bold text-gray-900 mb-4',
                        value: title,
                        onChange: (value) => setAttributes({ title: value }),
                        placeholder: 'Pricing section title...'
                    }),
                    wp.element.createElement(RichText, {
                        tagName: 'p',
                        className: 'text-lg text-gray-600 mb-6',
                        value: subtitle,
                        onChange: (value) => setAttributes({ subtitle: value }),
                        placeholder: 'Pricing section subtitle...'
                    }),

                    // Billing Toggle Preview
                    billingToggle && wp.element.createElement('div', { className: 'inline-flex items-center bg-gray-100 rounded-full p-1' },
                        wp.element.createElement('button', {
                            className: `px-4 py-2 rounded-full text-sm font-medium transition-colors ${
                                !showYearlyPrices ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600'
                            }`,
                            onClick: () => setShowYearlyPrices(false)
                        }, 'Monthly'),
                        wp.element.createElement('button', {
                            className: `px-4 py-2 rounded-full text-sm font-medium transition-colors ${
                                showYearlyPrices ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600'
                            }`,
                            onClick: () => setShowYearlyPrices(true)
                        }, 'Yearly ', wp.element.createElement('span', {
                            className: 'ml-1 text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full'
                        }, 'Save 20%'))
                    )
                ),

                wp.element.createElement('div', {
                        className: 'grid gap-6 max-w-6xl mx-auto',
                        style: {
                            gridTemplateColumns: gridClasses[columns] || gridClasses[3]
                        }
                    },
                    plans.map((plan, index) =>
                        wp.element.createElement('div', {
                                key: index,
                                className: `pricing-card bg-white rounded-lg shadow-md p-6 border-2 border-dashed border-gray-300 relative ${
                                    plan.featured ? 'ring-2 ring-blue-500 transform scale-105' : ''
                                }`
                            },
                            // Featured Badge
                            plan.featured && plan.badge && wp.element.createElement('div', {
                                    className: 'absolute -top-3 left-1/2 transform -translate-x-1/2'
                                },
                                wp.element.createElement('span', {
                                    className: 'bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-medium'
                                }, plan.badge)
                            ),

                            // Plan Header
                            wp.element.createElement('div', { className: 'text-center mb-6' },
                                wp.element.createElement('h3', { className: 'text-xl font-bold text-gray-900 mb-2' },
                                    plan.name
                                ),
                                plan.description && wp.element.createElement('p', {
                                    className: 'text-gray-600 text-sm mb-4'
                                }, plan.description),

                                // Price Display
                                wp.element.createElement('div', { className: 'mb-4' },
                                    wp.element.createElement('span', { className: 'text-2xl font-medium text-gray-600' },
                                        currency
                                    ),
                                    wp.element.createElement('span', { className: 'text-4xl font-bold text-gray-900' },
                                        showYearlyPrices && billingToggle
                                            ? Math.round((plan.yearlyPrice || plan.monthlyPrice * 12) / 12)
                                            : plan.monthlyPrice
                                    ),
                                    wp.element.createElement('span', { className: 'text-gray-600 ml-1' },
                                        '/month'
                                    )
                                )
                            ),

                            // Features
                            wp.element.createElement('ul', { className: 'space-y-3 mb-6' },
                                plan.features?.slice(0, 5).map((feature, featureIndex) =>
                                    wp.element.createElement('li', {
                                            key: featureIndex,
                                            className: 'flex items-center text-sm'
                                        },
                                        wp.element.createElement('span', {
                                            className: `mr-3 ${feature.included ? 'text-green-500' : 'text-gray-300'}`
                                        }, feature.included ? '✓' : '×'),
                                        wp.element.createElement('span', {
                                            className: feature.included ? 'text-gray-700' : 'text-gray-400 line-through'
                                        }, feature.text)
                                    )
                                )
                            ),

                            // Button
                            wp.element.createElement('button', {
                                className: `w-full py-3 px-4 rounded-lg font-medium transition-colors ${
                                    plan.buttonStyle === 'primary' || plan.featured
                                        ? 'bg-blue-600 text-white hover:bg-blue-700'
                                        : 'bg-gray-100 text-gray-900 hover:bg-gray-200'
                                }`
                            }, plan.buttonText || 'Get Started')
                        )
                    )
                ),

                // Quick Settings
                wp.element.createElement('div', { className: 'mt-8 p-4 bg-white rounded-lg border-2 border-dashed border-gray-300' },
                    wp.element.createElement('h4', { className: 'text-sm font-medium mb-3' }, 'Quick Settings'),
                    wp.element.createElement('div', { className: 'grid grid-cols-2 md:grid-cols-4 gap-3' },
                        wp.element.createElement('label', { className: 'flex items-center space-x-2 text-sm' },
                            wp.element.createElement('input', {
                                type: 'checkbox',
                                checked: billingToggle,
                                onChange: (e) => setAttributes({ billingToggle: e.target.checked })
                            }),
                            wp.element.createElement('span', null, 'Billing Toggle')
                        ),
                        wp.element.createElement('div', { className: 'flex items-center space-x-2' },
                            wp.element.createElement('label', { className: 'text-sm' }, 'Columns:'),
                            wp.element.createElement('input', {
                                type: 'range',
                                min: 1,
                                max: 4,
                                value: columns,
                                onChange: (e) => setAttributes({ columns: parseInt(e.target.value) })
                            })
                        ),
                        wp.element.createElement('div', { className: 'flex items-center space-x-2' },
                            wp.element.createElement('label', { className: 'text-sm' }, 'Currency:'),
                            wp.element.createElement('input', {
                                type: 'text',
                                value: currency,
                                onChange: (e) => setAttributes({ currency: e.target.value }),
                                className: 'w-12 px-2 py-1 border border-gray-300 rounded text-sm'
                            })
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