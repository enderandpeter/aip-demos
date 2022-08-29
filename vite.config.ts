import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import fs from 'fs';
import path from 'path';
import { certPath, keyPath, host } from './resources/js/config'

const getServerConfig = () => {
    if (!fs.existsSync(keyPath)) {
        console.log('Site key not found')
        return {}
    }

    if (!fs.existsSync(certPath)) {
        console.log('Site cert not found')
        return {}
    }

    return {
        host,
        https: {
            key: fs.readFileSync(keyPath),
            cert: fs.readFileSync(certPath),
        },
    }
}

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/main.ts', 'search-my-backyard.ts'],
            refresh: true,
        }),
        react(),
    ],
    resolve: {
        alias: {
           '@': path.resolve(__dirname, 'resources/js'),
           '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        },
    },

    server: getServerConfig(),
});
