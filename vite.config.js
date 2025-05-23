import { fileURLToPath, URL } from "node:url";
import { defineConfig } from "vite";

import { PrimeVueResolver } from "@primevue/auto-import-resolver";
import laravel from "laravel-vite-plugin";
// import tailwindcss from "@tailwindcss/vite";
import Components from "unplugin-vue-components/vite";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [],
            refresh: true,
        }),

        // tailwindcss(),
        Components({
            resolvers: [PrimeVueResolver()],
        }),
    ],
    build: {
        rollupOptions: {
            input: "resources/js/app.js", // or your main Vue entry
        },
    },
    resolve: {
        alias: {
            "@": fileURLToPath(new URL("./resources/js", import.meta.url)),
        },
    },
});
