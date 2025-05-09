import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/orders/orders.js",
                "resources/js/orders/add-order.js",
                "resources/js/dashboard.js",
                "resources/js/products.js",
                "resources/js/recommendations.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        allowedHosts: ["https://3f5b-102-191-171-218.ngrok-free.app"],
    },
});
