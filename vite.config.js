import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';


export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/css/app-front.css',
                'resources/css/app.css',
                'resources/css/sp-theme.css',
                'resources/css/sp-admin-theme.css',
                'resources/js/app-front.js',
            ],
            refresh: true,
        }),
    ],
});
