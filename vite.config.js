import { fileURLToPath, URL } from 'node:url';
import { defineConfig } from 'vite';
import autoprefixer from 'autoprefixer';
import laravel from 'laravel-vite-plugin';
import postcssNesting from 'postcss-nesting';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources', import.meta.url)),
            '@js': fileURLToPath(new URL('./resources/js', import.meta.url)),
            '@css': fileURLToPath(new URL('./resources/css', import.meta.url)),
            '@images': fileURLToPath(new URL('./resources/images', import.meta.url)),
            '@src': fileURLToPath(new URL('./resources/src', import.meta.url)),
            '@svg': fileURLToPath(new URL('./resources/svg', import.meta.url)),
            '@views': fileURLToPath(new URL('./resources/views', import.meta.url))
        },
    }
});
