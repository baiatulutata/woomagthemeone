const { registerBlockType } = wp.blocks;
const { RichText, InspectorControls } = wp.blockEditor;
const { PanelBody, Button, TextControl, ToggleControl, SelectControl, Card, CardBody } = wp.components;
const { useState, Fragment } = wp.element;

registerBlockType('woomag-theme/contact-form', {
    title: 'Contact Form',
    icon: 'email-alt',
    category: 'woomag-theme',
    description: 'Create customizable contact forms with validation, AJAX submission, and spam protection.',
    keywords: ['contact', 'form', 'email', 'message', 'inquiry'],

    attributes: {
        title: {
            type: 'string',
            default: 'Get In Touch'
        },
        subtitle: {
            type: 'string',
            default: 'We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.'
        },
        style: {
            type: 'string',
            default: 'modern'
        },
        layout: {
            type: 'string',
            default: 'single'
        },
        submitText: {
            type: 'string',
            default: 'Send Message'
        },
        successMessage: {
            type: 'string',
            default: 'Thank you for your message! We\'ll get back to you soon.'
        },
        emailTo: {
            type: 'string',
            default: ''
        },
        showLabels: {
            type: 'boolean',
            default: true
        },
        fields: {
            type: 'array',
            default: [
                {
                    type: 'text',
                    name: 'name',
                    label: 'Full Name',
                    placeholder: 'Enter your full name',
                    required: true,
                    width: 50
                },
                {
                    type: 'email',
                    name: 'email',
                    label: 'Email Address',
                    placeholder: 'Enter your email address',
                    required: true,
                    width: 50
                },
                {
                    type: 'text',
                    name: 'subject',
                    label: 'Subject',
                    placeholder: 'What is this regarding?',
                    required: false,
                    width: 100
                },
                {
                    type: 'textarea',
                    name: 'message',
                    label: 'Message',
                    placeholder: 'Tell us more about your inquiry...',
                    required: true,
                    width: 100
                }
            ]
        }
    },

    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { title, subtitle, style, layout, submitText, successMessage, emailTo, showLabels, fields } = attributes;

        const [activeField, setActiveField] = useState(0);

        const updateField = (index, field, value) => {
            const newFields = [...fields];
            newFields[index] = { ...newFields[index], [field]: value };
            setAttributes({ fields: newFields });
        };

        const addField = (fieldType = 'text') => {
            const fieldTypes = {
                text: { type: 'text', label: 'Text Field', placeholder: 'Enter text' },
                email: { type: 'email', label: 'Email Field', placeholder: 'Enter email' },
                tel: { type: 'tel', label: 'Phone Field', placeholder: 'Enter phone number' },
                textarea: { type: 'textarea', label: 'Message Field', placeholder: 'Enter message' },
                select: { type: 'select', label: 'Dropdown Field', placeholder: 'Choose option', options: ['Option 1', 'Option 2', 'Option 3'] },
                checkbox: { type: 'checkbox', label: 'Checkbox Field', placeholder: '' }
            };

            const newField = {
                ...fieldTypes[fieldType],
                name: `field_${fields.length + 1}`,
                required: false,
                width: 100
            };

            setAttributes({ fields: [...fields, newField] });
            setActiveField(fields.length);
        };

        const removeField = (index) => {
            if (fields.length > 1) {
                const newFields = fields.filter((_, i) => i !== index);
                setAttributes({ fields: newFields });
                if (activeField >= newFields.length) {
                    setActiveField(newFields.length - 1);
                }
            }
        };

        const duplicateField = (index) => {
            const newFields = [...fields];
            const duplicatedField = {
                ...fields[index],
                name: fields[index].name + '_copy',
                label: fields[index].label + ' (Copy)'
            };
            newFields.splice(index + 1, 0, duplicatedField);
            setAttributes({ fields: newFields });
        };

        const moveField = (index, direction) => {
            const newFields = [...fields];
            const newIndex = direction === 'up' ? index - 1 : index + 1;

            if (newIndex >= 0 && newIndex < fields.length) {
                [newFields[index], newFields[newIndex]] = [newFields[newIndex], newFields[index]];
                setAttributes({ fields: newFields });
                setActiveField(newIndex);
            }
        };

        const addFieldOption = (fieldIndex) => {
            const newFields = [...fields];
            if (!newFields[fieldIndex].options) {
                newFields[fieldIndex].options = [];
            }
            newFields[fieldIndex].options.push(`Option ${newFields[fieldIndex].options.length + 1}`);
            setAttributes({ fields: newFields });
        };

        const updateFieldOption = (fieldIndex, optionIndex, value) => {
            const newFields = [...fields];
            newFields[fieldIndex].options[optionIndex] = value;
            setAttributes({ fields: newFields });
        };

        const removeFieldOption = (fieldIndex, optionIndex) => {
            const newFields = [...fields];
            newFields[fieldIndex].options.splice(optionIndex, 1);
            setAttributes({ fields: newFields });
        };

        const getFieldIcon = (type) => {
            const icons = {
                text: '📝',
                email: '📧',
                tel: '📞',
                textarea: '📄',
                select: '📋',
                checkbox: '☑️'
            };
            return icons[type] || '📝';
        };

        const renderFieldPreview = (field, index) => {
            const baseClasses = 'w-full px-4 py-3 border border-gray-300 rounded-lg bg-white';

            switch (field.type) {
                case 'textarea':
                    return wp.element.createElement('textarea', {
                        className: baseClasses,
                        placeholder: field.placeholder,
                        rows: 3,
                        disabled: true
                    });

                case 'select':
                    return wp.element.createElement('select', {
                            className: baseClasses,
                            disabled: true
                        },
                        wp.element.createElement('option', null, field.placeholder),
                        field.options?.map((option, i) =>
                            wp.element.createElement('option', { key: i }, option)
                        )
                    );

                case 'checkbox':
                    return wp.element.createElement('div', { className: 'flex items-center' },
                        wp.element.createElement('input', {
                            type: 'checkbox',
                            className: 'rounded border-gray-300 text-blue-600 mr-3',
                            disabled: true
                        }),
                        wp.element.createElement('span', { className: 'text-gray-700' }, field.label)
                    );

                default:
                    return wp.element.createElement('input', {
                        type: field.type,
                        className: baseClasses,
                        placeholder: field.placeholder,
                        disabled: true
                    });
            }
        };

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Form Settings', initialOpen: true },
                    wp.element.createElement(SelectControl, {
                        label: 'Style',
                        value: style,
                        options: [
                            { label: 'Modern', value: 'modern' },
                            { label: 'Minimal', value: 'minimal' },
                            { label: 'Bordered', value: 'bordered' },
                            { label: 'Gradient', value: 'gradient' }
                        ],
                        onChange: (value) => setAttributes({ style: value })
                    }),

                    wp.element.createElement(SelectControl, {
                        label: 'Layout',
                        value: layout,
                        options: [
                            { label: 'Single Column', value: 'single' },
                            { label: 'Split (Form + Contact Info)', value: 'split' }
                        ],
                        onChange: (value) => setAttributes({ layout: value })
                    }),

                    wp.element.createElement(TextControl, {
                        label: 'Submit Button Text',
                        value: submitText,
                        onChange: (value) => setAttributes({ submitText: value })
                    }),

                    wp.element.createElement(TextControl, {
                        label: 'Email To',
                        type: 'email',
                        value: emailTo,
                        onChange: (value) => setAttributes({ emailTo: value }),
                        help: 'Leave empty to use admin email'
                    }),

                    wp.element.createElement(ToggleControl, {
                        label: 'Show Field Labels',
                        checked: showLabels,
                        onChange: (value) => setAttributes({ showLabels: value }),
                        help: 'Show labels above fields (recommended for accessibility)'
                    })
                ),

                wp.element.createElement(PanelBody, { title: 'Messages', initialOpen: false },
                    wp.element.createElement(TextControl, {
                        label: 'Success Message',
                        value: successMessage,
                        onChange: (value) => setAttributes({ successMessage: value }),
                        help: 'Message shown after successful form submission'
                    })
                ),

                wp.element.createElement(PanelBody, { title: 'Form Fields', initialOpen: false },
                    wp.element.createElement('div', { className: 'flex flex-wrap gap-2 mb-4' },
                        wp.element.createElement(Button, {
                            variant: 'secondary',
                            isSmall: true,
                            onClick: () => addField('text')
                        }, '+ Text'),
                        wp.element.createElement(Button, {
                            variant: 'secondary',
                            isSmall: true,
                            onClick: () => addField('email')
                        }, '+ Email'),
                        wp.element.createElement(Button, {
                            variant: 'secondary',
                            isSmall: true,
                            onClick: () => addField('tel')
                        }, '+ Phone'),
                        wp.element.createElement(Button, {
                            variant: 'secondary',
                            isSmall: true,
                            onClick: () => addField('textarea')
                        }, '+ Textarea'),
                        wp.element.createElement(Button, {
                            variant: 'secondary',
                            isSmall: true,
                            onClick: () => addField('select')
                        }, '+ Select'),
                        wp.element.createElement(Button, {
                            variant: 'secondary',
                            isSmall: true,
                            onClick: () => addField('checkbox')
                        }, '+ Checkbox')
                    ),

                    wp.element.createElement('div', { className: 'mb-4' },
                        wp.element.createElement('div', { className: 'space-y-2 max-h-48 overflow-y-auto' },
                            fields.map((field, index) =>
                                wp.element.createElement('button', {
                                        key: index,
                                        className: `w-full text-left px-3 py-2 text-sm rounded border transition-colors ${
                                            activeField === index
                                                ? 'bg-blue-50 border-blue-300 text-blue-900'
                                                : 'bg-gray-50 border-gray-200 text-gray-700 hover:bg-gray-100'
                                        }`,
                                        onClick: () => setActiveField(index)
                                    },
                                    wp.element.createElement('div', { className: 'flex items-center justify-between' },
                                        wp.element.createElement('div', { className: 'flex items-center flex-1' },
                                            wp.element.createElement('span', { className: 'mr-2' }, getFieldIcon(field.type)),
                                            wp.element.createElement('span', { className: 'truncate' }, field.label || field.name),
                                            field.required && wp.element.createElement('span', {
                                                className: 'ml-2 text-xs text-red-600'
                                            }, '*')
                                        ),
                                        wp.element.createElement('span', {
                                            className: 'text-xs text-gray-500 ml-2'
                                        }, `${field.width}%`)
                                    )
                                )
                            )
                        )
                    ),

                    fields.length > 0 && wp.element.createElement(Card, { className: 'mb-4' },
                        wp.element.createElement(CardBody, null,
                            wp.element.createElement('div', { className: 'flex justify-between items-start mb-4' },
                                wp.element.createElement('h4', { className: 'text-sm font-medium' },
                                    `${fields[activeField]?.label || 'Field'} Settings`
                                ),
                                wp.element.createElement('div', { className: 'flex space-x-1' },
                                    activeField > 0 && wp.element.createElement(Button, {
                                        variant: 'secondary',
                                        isSmall: true,
                                        onClick: () => moveField(activeField, 'up'),
                                        title: 'Move up'
                                    }, '↑'),
                                    activeField < fields.length - 1 && wp.element.createElement(Button, {
                                        variant: 'secondary',
                                        isSmall: true,
                                        onClick: () => moveField(activeField, 'down'),
                                        title: 'Move down'
                                    }, '↓'),
                                    wp.element.createElement(Button, {
                                        variant: 'secondary',
                                        isSmall: true,
                                        onClick: () => duplicateField(activeField),
                                        title: 'Duplicate'
                                    }, 'Copy'),
                                    fields.length > 1 && wp.element.createElement(Button, {
                                        variant: 'secondary',
                                        isSmall: true,
                                        isDestructive: true,
                                        onClick: () => removeField(activeField),
                                        title: 'Remove'
                                    }, '×')
                                )
                            ),

                            // Basic Field Settings
                            wp.element.createElement('div', { className: 'space-y-4' },
                                wp.element.createElement(SelectControl, {
                                    label: 'Field Type',
                                    value: fields[activeField]?.type || 'text',
                                    options: [
                                        { label: 'Text Input', value: 'text' },
                                        { label: 'Email Input', value: 'email' },
                                        { label: 'Phone Input', value: 'tel' },
                                        { label: 'Text Area', value: 'textarea' },
                                        { label: 'Select Dropdown', value: 'select' },
                                        { label: 'Checkbox', value: 'checkbox' }
                                    ],
                                    onChange: (value) => updateField(activeField, 'type', value)
                                }),

                                wp.element.createElement(TextControl, {
                                    label: 'Field Name (ID)',
                                    value: fields[activeField]?.name || '',
                                    onChange: (value) => updateField(activeField, 'name', value.replace(/[^a-z0-9_]/gi, '_').toLowerCase()),
                                    help: 'Used for form processing (letters, numbers, underscores only)'
                                }),

                                wp.element.createElement(TextControl, {
                                    label: 'Label',
                                    value: fields[activeField]?.label || '',
                                    onChange: (value) => updateField(activeField, 'label', value)
                                }),

                                wp.element.createElement(TextControl, {
                                    label: 'Placeholder',
                                    value: fields[activeField]?.placeholder || '',
                                    onChange: (value) => updateField(activeField, 'placeholder', value)
                                }),

                                wp.element.createElement('div', { className: 'grid grid-cols-2 gap-4' },
                                    wp.element.createElement(ToggleControl, {
                                        label: 'Required Field',
                                        checked: fields[activeField]?.required || false,
                                        onChange: (value) => updateField(activeField, 'required', value)
                                    }),
                                    wp.element.createElement(SelectControl, {
                                        label: 'Width',
                                        value: fields[activeField]?.width?.toString() || '100',
                                        options: [
                                            { label: '25%', value: '25' },
                                            { label: '33%', value: '33' },
                                            { label: '50%', value: '50' },
                                            { label: '66%', value: '66' },
                                            { label: '75%', value: '75' },
                                            { label: '100%', value: '100' }
                                        ],
                                        onChange: (value) => updateField(activeField, 'width', parseInt(value))
                                    })
                                )
                            ),

                            // Options for select fields
                            fields[activeField]?.type === 'select' && wp.element.createElement('div', { className: 'mt-6 pt-4 border-t border-gray-200' },
                                wp.element.createElement('div', { className: 'flex justify-between items-center mb-3' },
                                    wp.element.createElement('h5', { className: 'text-sm font-medium' }, 'Options'),
                                    wp.element.createElement(Button, {
                                        variant: 'secondary',
                                        isSmall: true,
                                        onClick: () => addFieldOption(activeField)
                                    }, 'Add Option')
                                ),

                                fields[activeField]?.options?.map((option, optionIndex) =>
                                    wp.element.createElement('div', {
                                            key: optionIndex,
                                            className: 'flex items-center space-x-2 mb-2'
                                        },
                                        wp.element.createElement('input', {
                                            type: 'text',
                                            value: option,
                                            onChange: (e) => updateFieldOption(activeField, optionIndex, e.target.value),
                                            className: 'flex-1 px-3 py-2 border border-gray-300 rounded text-sm',
                                            placeholder: 'Option text...'
                                        }),
                                        wp.element.createElement(Button, {
                                            variant: 'secondary',
                                            isSmall: true,
                                            isDestructive: true,
                                            onClick: () => removeFieldOption(activeField, optionIndex)
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
                    className: 'contact-form-editor py-8 px-4 bg-gray-50 rounded-lg'
                },
                wp.element.createElement('div', { className: 'max-w-4xl mx-auto' },
                    wp.element.createElement('div', { className: 'text-center mb-8' },
                        wp.element.createElement(RichText, {
                            tagName: 'h2',
                            className: 'text-3xl font-bold text-gray-900 mb-4',
                            value: title,
                            onChange: (value) => setAttributes({ title: value }),
                            placeholder: 'Form title...'
                        }),
                        wp.element.createElement(RichText, {
                            tagName: 'p',
                            className: 'text-lg text-gray-600',
                            value: subtitle,
                            onChange: (value) => setAttributes({ subtitle: value }),
                            placeholder: 'Form subtitle...'
                        })
                    ),

                    wp.element.createElement('div', {
                            className: layout === 'split' ? 'grid md:grid-cols-2 gap-8' : ''
                        },
                        // Contact info (split layout only)
                        layout === 'split' && wp.element.createElement('div', {
                                className: 'space-y-4 p-6 bg-gray-100 rounded-lg'
                            },
                            wp.element.createElement('h3', { className: 'text-lg font-semibold mb-4' }, 'Contact Information'),
                            wp.element.createElement('div', { className: 'text-sm text-gray-600' }, 'Contact details will appear here...'),
                            wp.element.createElement('div', { className: 'text-sm text-gray-600' }, 'Social links will appear here...')
                        ),

                        // Form preview
                        wp.element.createElement('div', {
                                className: 'bg-white p-6 rounded-lg shadow-md border-2 border-dashed border-gray-300'
                            },
                            wp.element.createElement('div', { className: 'space-y-4' },
                                fields.map((field, index) => {
                                    const isActive = activeField === index;
                                    const widthClass = `w-${field.width === 100 ? 'full' : field.width === 50 ? '1/2' : field.width === 33 ? '1/3' : field.width === 25 ? '1/4' : 'full'}`;

                                    return wp.element.createElement('div', {
                                            key: index,
                                            className: `form-field-preview ${isActive ? 'ring-2 ring-blue-500 rounded p-2' : ''}`,
                                            onClick: () => setActiveField(index),
                                            style: { cursor: 'pointer' }
                                        },
                                        isActive && wp.element.createElement('div', {
                                            className: 'text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded mb-2'
                                        }, `Editing: ${field.label || field.name}`),

                                        showLabels && field.type !== 'checkbox' && wp.element.createElement('label', {
                                                className: 'block text-sm font-medium text-gray-700 mb-2'
                                            },
                                            field.label,
                                            field.required && wp.element.createElement('span', { className: 'text-red-500 ml-1' }, '*')
                                        ),

                                        renderFieldPreview(field, index)
                                    );
                                })
                            ),

                            // Submit button preview
                            wp.element.createElement('div', { className: 'mt-6' },
                                wp.element.createElement('button', {
                                    className: 'w-full bg-blue-600 text-white font-medium py-3 px-6 rounded-lg',
                                    disabled: true
                                }, submitText)
                            ),

                            // Form info
                            wp.element.createElement('div', { className: 'mt-4 pt-4 border-t border-gray-200 text-center text-sm text-gray-600' },
                                `${fields.length} fields • ${fields.filter(f => f.required).length} required • Email to: ${emailTo || 'admin email'}`
                            )
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