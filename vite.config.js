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
            '@js': '/resources/js',
            '@css': '/resources/css',
            '@src': '/resources/src',
            '@views': '/resources/views',
        },
    },
    css: {
        postcss: {
            plugins: [
                autoprefixer({}),
                postcssNesting
            ],
        },
    },
});
