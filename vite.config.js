import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import svgLoader from 'vite-svg-loader';

export default defineConfig({
    build: {
        chunkSizeWarningLimit: 3000, // in KB
        rollupOptions: {
            output: {
                manualChunks: {
                    'vendor': [
                        'vue',
                        '@inertiajs/vue3',
                        'chart.js',
                        'flowbite',
                        '@vuepic/vue-datepicker'
                    ]
                }
            }
        }
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
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
        svgLoader(),
    ],
    optimizeDeps: {
        include: ['vue', '@inertiajs/vue3', 'chart.js', 'flowbite', '@vuepic/vue-datepicker']
    },
    define: {
        'process.env': {}
    }
});
