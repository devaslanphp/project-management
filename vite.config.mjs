import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/filament.scss',
                'resources/js/filament.js'
            ],
            refresh: true,
        }),
    ],
});
