import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        'node_modules/flowbite-vue/**/*.{js,jsx,ts,tsx}',
        'node_modules/flowbite/**/*.{js,jsx,ts,tsx}'
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            backgroundColor: {
                'soft-blue': '#F5F9FF',
                'card-blue': '#050538',
                'icon-blue': '#DDE7F6',
            },
            colors: {
                'pay-blue': '#1A1AFF',
                'pay-failed-red': '#EC2D20',
                'pay-neutral': '#F6F7F9',
                'pay-dark-neutral': '#030124',
                'field-header': '#5e626a',
                'field-value': '#030124'
            },
        },
    },

    plugins: [
        forms,
        typography,
        require('flowbite/plugin'),
    ],
};
