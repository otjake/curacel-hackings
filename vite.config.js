import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

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
    ],
    optimizeDeps: {
        include: ['vue', '@inertiajs/vue3', 'chart.js', 'flowbite', '@vuepic/vue-datepicker']
    }
});
