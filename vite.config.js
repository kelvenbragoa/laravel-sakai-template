import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { PrimeVueResolver } from '@primevue/auto-import-resolver';
import Components from 'unplugin-vue-components/vite';
import { fileURLToPath, URL } from 'node:url';
import path from "path"

export default defineConfig({
    base: '/cgate1x/', // Define o caminho base corretamente
    optimizeDeps: {
        noDiscovery: true
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: '/cgate1x/',
                    includeAbsolute: false,
                },
            },
        }),
        Components({
            resolvers: [PrimeVueResolver()]
        })
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
            "~": path.join(__dirname, "/node_modules/"),
            '@': fileURLToPath(new URL('./resources/js/', import.meta.url))
        },
    },
});