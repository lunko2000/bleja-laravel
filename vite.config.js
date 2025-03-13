import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path';
import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/build', // Ensure output directory is correct
        manifest: true, // Ensure manifest.json is generated
        emptyOutDir: true, // Clear old files before building
    },
    // Hook to move manifest.json out of .vite and into public/build
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources'),
        },
    },
    esbuild: {
        minify: true,
    },
});

process.on('exit', () => {
    const viteManifestPath = 'public/build/.vite/manifest.json';
    const correctManifestPath = 'public/build/manifest.json';

    if (fs.existsSync(viteManifestPath)) {
        fs.renameSync(viteManifestPath, correctManifestPath);
        console.log('✅ Moved manifest.json to the correct location.');
    }
});