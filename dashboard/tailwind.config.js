import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'insight-aqua': '#01F5D1',
                'insight-aqua-dark': '#01E0BE',
                'insight-pale-cyan': '#BAECE5',
                'insight-light-gray': '#ACACAC',
                'insight-dark': '#0D0D0D',
            },
        },
    },

    plugins: [forms],
};
