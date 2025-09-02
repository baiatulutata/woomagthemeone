/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './*.php',
        './template-parts/**/*.php',
        './templates/**/*.php',
        './src/**/*.js',
        './src/**/*.css'
    ],
    theme: {
        extend: {
            fontFamily: {
                'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                'serif': ['Merriweather', 'ui-serif', 'Georgia'],
            },
            colors: {
                primary: {
                    50: '#eff6ff',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    900: '#1e3a8a'
                }
            },
            container: {
                center: true,
                padding: '1rem',
                screens: {
                    sm: '640px',
                    md: '768px',
                    lg: '1024px',
                    xl: '1280px',
                    '2xl': '1400px'
                }
            }
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
}