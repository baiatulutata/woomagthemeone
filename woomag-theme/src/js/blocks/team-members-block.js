const { registerBlockType } = wp.blocks;
const { RichText, MediaUpload, InspectorControls } = wp.blockEditor;
const { PanelBody, Button, TextControl, ToggleControl, SelectControl, RangeControl, Card, CardBody } = wp.components;
const { useState, Fragment } = wp.element;

registerBlockType('woomag-theme/team-members', {
    title: 'Team Members',
    icon: 'groups',
    category: 'woomag-theme',
    description: 'Showcase your team members with photos, bios, and social links.',
    keywords: ['team', 'members', 'staff', 'people'],

    attributes: {
        title: {
            type: 'string',
            default: 'Our Amazing Team'
        },
        subtitle: {
            type: 'string',
            default: 'Meet the talented people behind our success'
        },
        layout: {
            type: 'string',
            default: 'grid'
        },
        columns: {
            type: 'number',
            default: 3
        },
        showSocial: {
            type: 'boolean',
            default: true
        },
        cardStyle: {
            type: 'string',
            default: 'modern'
        },
        members: {
            type: 'array',
            default: [
                {
                    name: 'John Doe',
                    position: 'CEO & Founder',
                    bio: 'Passionate leader with 10+ years of experience in the industry.',
                    image: '',
                    email: 'john@company.com',
                    linkedin: '',
                    twitter: '',
                    instagram: ''
                },
                {
                    name: 'Jane Smith',
                    position: 'Lead Designer',
                    bio: 'Creative professional who brings ideas to life through design.',
                    image: '',
                    email: 'jane@company.com',
                    linkedin: '',
                    twitter: '',
                    instagram: ''
                },
                {
                    name: 'Mike Johnson',
                    position: 'Developer',
                    bio: 'Full-stack developer passionate about clean, efficient code.',
                    image: '',
                    email: 'mike@company.com',
                    linkedin: '',
                    twitter: '',
                    instagram: ''
                }
            ]
        }
    },

    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { title, subtitle, layout, columns, showSocial, cardStyle, members } = attributes;

        const updateMember = (index, field, value) => {
            const newMembers = [...members];
            newMembers[index] = { ...newMembers[index], [field]: value };
            setAttributes({ members: newMembers });
        };

        const addMember = () => {
            const newMembers = [...members, {
                name: 'New Member',
                position: 'Position',
                bio: 'Member bio...',
                image: '',
                email: '',
                linkedin: '',
                twitter: '',
                instagram: ''
            }];
            setAttributes({ members: newMembers });
        };

        const removeMember = (index) => {
            const newMembers = members.filter((_, i) => i !== index);
            setAttributes({ members: newMembers });
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
                wp.element.createElement(PanelBody, { title: 'Team Settings', initialOpen: true },
                    wp.element.createElement(SelectControl, {
                        label: 'Card Style',
                        value: cardStyle,
                        options: [
                            { label: 'Modern', value: 'modern' },
                            { label: 'Minimal', value: 'minimal' },
                            { label: 'Gradient', value: 'gradient' },
                            { label: 'Card', value: 'card' }
                        ],
                        onChange: (value) => setAttributes({ cardStyle: value })
                    }),
                    wp.element.createElement(RangeControl, {
                        label: 'Columns',
                        value: columns,
                        onChange: (value) => setAttributes({ columns: value }),
                        min: 1,
                        max: 5
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: 'Show Social Links',
                        checked: showSocial,
                        onChange: (value) => setAttributes({ showSocial: value })
                    })
                ),

                wp.element.createElement(PanelBody, { title: 'Team Members', initialOpen: false },
                    wp.element.createElement(Button, {
                        variant: 'primary',
                        onClick: addMember,
                        className: 'mb-4'
                    }, 'Add Team Member'),

                    members.map((member, index) =>
                        wp.element.createElement(Card, { key: index, className: 'mb-4' },
                            wp.element.createElement(CardBody, null,
                                wp.element.createElement('div', { className: 'flex justify-between items-start mb-3' },
                                    wp.element.createElement('h4', { className: 'text-sm font-medium' }, `Member ${index + 1}`),
                                    wp.element.createElement(Button, {
                                        variant: 'secondary',
                                        isSmall: true,
                                        isDestructive: true,
                                        onClick: () => removeMember(index)
                                    }, 'Remove')
                                ),

                                wp.element.createElement(TextControl, {
                                    label: 'Name',
                                    value: member.name,
                                    onChange: (value) => updateMember(index, 'name', value)
                                }),

                                wp.element.createElement(TextControl, {
                                    label: 'Position',
                                    value: member.position,
                                    onChange: (value) => updateMember(index, 'position', value)
                                }),

                                wp.element.createElement('div', { style: { marginBottom: '16px' } },
                                    wp.element.createElement('label', { className: 'block text-sm font-medium mb-2' }, 'Photo'),
                                    wp.element.createElement(MediaUpload, {
                                        onSelect: (media) => updateMember(index, 'image', media.url),
                                        allowedTypes: ['image'],
                                        render: ({ open }) => wp.element.createElement(Button, {
                                            onClick: open,
                                            variant: member.image ? 'secondary' : 'primary',
                                            className: 'mb-2'
                                        }, member.image ? 'Change Photo' : 'Select Photo'),
                                    }),
                                    member.image && wp.element.createElement('img', {
                                        src: member.image,
                                        alt: member.name,
                                        style: { width: '80px', height: '80px', objectFit: 'cover', borderRadius: '8px' }
                                    })
                                ),

                                wp.element.createElement('label', { className: 'block text-sm font-medium mb-2' }, 'Bio'),
                                wp.element.createElement('textarea', {
                                    value: member.bio,
                                    onChange: (e) => updateMember(index, 'bio', e.target.value),
                                    rows: 3,
                                    className: 'w-full px-3 py-2 border border-gray-300 rounded-md mb-3'
                                }),

                                showSocial && wp.element.createElement(Fragment, null,
                                    wp.element.createElement('h5', { className: 'text-sm font-medium mb-2' }, 'Social Links'),
                                    wp.element.createElement(TextControl, {
                                        label: 'Email',
                                        value: member.email,
                                        onChange: (value) => updateMember(index, 'email', value),
                                        type: 'email'
                                    }),
                                    wp.element.createElement(TextControl, {
                                        label: 'LinkedIn URL',
                                        value: member.linkedin,
                                        onChange: (value) => updateMember(index, 'linkedin', value),
                                        type: 'url'
                                    }),
                                    wp.element.createElement(TextControl, {
                                        label: 'Twitter URL',
                                        value: member.twitter,
                                        onChange: (value) => updateMember(index, 'twitter', value),
                                        type: 'url'
                                    }),
                                    wp.element.createElement(TextControl, {
                                        label: 'Instagram URL',
                                        value: member.instagram,
                                        onChange: (value) => updateMember(index, 'instagram', value),
                                        type: 'url'
                                    })
                                )
                            )
                        )
                    )
                )
            ),

            wp.element.createElement('div', {
                    className: 'woomag-team-members-editor py-8 px-4 bg-gray-50 rounded-lg'
                },
                wp.element.createElement('div', { className: 'text-center mb-8' },
                    wp.element.createElement(RichText, {
                        tagName: 'h2',
                        className: 'text-3xl font-bold text-gray-900 mb-4',
                        value: title,
                        onChange: (value) => setAttributes({ title: value }),
                        placeholder: 'Team section title...'
                    }),
                    wp.element.createElement(RichText, {
                        tagName: 'p',
                        className: 'text-lg text-gray-600',
                        value: subtitle,
                        onChange: (value) => setAttributes({ subtitle: value }),
                        placeholder: 'Team section subtitle...'
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
                    members.map((member, index) =>
                        wp.element.createElement('div', {
                                key: index,
                                className: 'bg-white rounded-lg shadow-md overflow-hidden border-2 border-dashed border-gray-300'
                            },
                            wp.element.createElement('div', { className: 'relative' },
                                member.image ?
                                    wp.element.createElement('img', {
                                        src: member.image,
                                        alt: member.name,
                                        className: 'w-full h-48 object-cover'
                                    }) :
                                    wp.element.createElement('div', {
                                            className: 'w-full h-48 bg-gray-200 flex items-center justify-center'
                                        },
                                        wp.element.createElement('svg', {
                                                className: 'w-16 h-16 text-gray-400',
                                                fill: 'none',
                                                stroke: 'currentColor',
                                                viewBox: '0 0 24 24'
                                            },
                                            wp.element.createElement('path', {
                                                strokeLinecap: 'round',
                                                strokeLinejoin: 'round',
                                                strokeWidth: 2,
                                                d: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
                                            })
                                        )
                                    )
                            ),

                            wp.element.createElement('div', { className: 'p-4' },
                                wp.element.createElement('h3', { className: 'text-lg font-bold text-gray-900 mb-1' }, member.name),
                                wp.element.createElement('p', { className: 'text-blue-600 font-medium text-sm mb-2' }, member.position),
                                wp.element.createElement('p', { className: 'text-gray-600 text-sm' }, member.bio)
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