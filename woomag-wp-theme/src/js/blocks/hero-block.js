const { registerBlockType } = wp.blocks;
const { RichText, MediaUpload, InspectorControls } = wp.blockEditor;
const { PanelBody, Button, TextControl } = wp.components;
const { useState } = wp.element;

registerBlockType('woomag-theme/hero', {
title: 'Hero Section',
icon: 'format-image',
category: 'woomag-theme',
description: 'A beautiful hero section with background image, title, subtitle, and call-to-action button.',

attributes: {
title: {
type: 'string',
default: 'Welcome to Our Amazing Site'
},
subtitle: {
type: 'string',
default: 'This is where great stories begin. Join us on this incredible journey.'
},
buttonText: {
type: 'string',
default: 'Get Started'
},
buttonUrl: {
type: 'string',
default: '#'
},
backgroundImage: {
type: 'string',
default: ''
}
},

edit: function(props) {
const { attributes, setAttributes } = props;
const { title, subtitle, buttonText, buttonUrl, backgroundImage } = attributes;

return [
wp.element.createElement(InspectorControls, null,
wp.element.createElement(PanelBody, { title: 'Hero Settings' },
wp.element.createElement(TextControl, {
label: 'Button URL',
value: buttonUrl,
onChange: (value) => setAttributes({ buttonUrl: value })
}),
wp.element.createElement('div', { style: { marginBottom: '16px' } },
wp.element.createElement('label', null, 'Background Image'),
wp.element.createElement(MediaUpload, {
onSelect: (media) => setAttributes({ backgroundImage: media.url }),
allowedTypes: ['image'],
render: ({ open }) => wp.element.createElement(Button, {
onClick: open,
variant: 'secondary'
}, backgroundImage ? 'Change Image' : 'Select Image')
})
)
)
),

wp.element.createElement('div', {
className: 'hero-block-editor relative bg-gray-900 text-white p-8 rounded-lg',
style: {
backgroundImage: backgroundImage ? `linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url(${backgroundImage})` : 'none',
backgroundSize: 'cover',
backgroundPosition: 'center'
}
},
wp.element.createElement('div', { className: 'text-center' },
wp.element.createElement(RichText, {
tagName: 'h1',
className: 'text-4xl font-bold mb-4',
value: title,
onChange: (value) => setAttributes({ title: value }),
placeholder: 'Enter hero title...'
}),
wp.element.createElement(RichText, {
tagName: 'p',
className: 'text-xl mb-6 opacity-90',
value: subtitle,
onChange: (value) => setAttributes({ subtitle: value }),
placeholder: 'Enter hero subtitle...'
}),
wp.element.createElement(RichText, {
tagName: 'span',
className: 'inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg',
value: buttonText,
onChange: (value) => setAttributes({ buttonText: value }),
placeholder: 'Button text...'
})
)
)
];
},

save: function() {
// Server-side rendering
return null;
}
});