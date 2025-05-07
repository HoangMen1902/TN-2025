import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
    },
    resolve: {
        alias: {
          '@sass': path.resolve(__dirname, 'resources/scss'),
          '@modules': path.resolve(__dirname, 'Modules') 
        }
      }
});