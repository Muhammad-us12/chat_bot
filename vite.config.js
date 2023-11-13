import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

if (
    process.env.npm_lifecycle_event === "build" &&
    !process.env.CI &&
    !process.env.SHOPIFY_APP_KEY
) {
    console.warn(
        "\nBuilding the frontend app without an API key. The frontend build will not run without an API key. Set the SHOPIFY_APP_KEY environment variable when running the build command.\n"
    );
}

export default ({ mode }) => {
    process.env = {...process.env, ...loadEnv(mode, process.cwd())};

    return defineConfig({
        plugins: [
            laravel({
                input: ['resources/js/app.js'],
                refresh: true,
            }),
            react(),
        ],
        define: {
            "process.env.SHOPIFY_API_KEY": JSON.stringify(process.env.VITE_SHOPIFY_APP_KEY),
        },
    });
}