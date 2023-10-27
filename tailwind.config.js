import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    purge: {
        content: [
          './vendor/wire-elements/modal/resources/views/*.blade.php',
          './storage/framework/views/*.php',
          './resources/views/**/*.blade.php',
        ],
        options: {
            safelist: [
              'sm:max-w-2xl'
            ]
        }
    },
    darkMode: 'class',
    presets: [
        require("./vendor/power-components/livewire-powergrid/tailwind.config.js"),
        require('./vendor/wireui/wireui/tailwind.config.js'),
    ],
    content: [
        './app/Http/Livewire/**/*Table.php',
        './vendor/power-components/livewire-powergrid/resources/views/**/*.php',
        './vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/wireui/wireui/resources/**/*.blade.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/View/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
