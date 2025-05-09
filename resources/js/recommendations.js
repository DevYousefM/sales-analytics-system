import { endpoints, get } from "./services/api-service";
import { showToastr } from "./utilities";

document.addEventListener("DOMContentLoaded", () => {
    getRecommendations();
});

const getRecommendations = () => {
    get(endpoints.get_recommendations, (response, status) => {
        let products_container = document.getElementById("product-list");
        if (response.status === "failed") {
            products_container.classList.remove(
                "grid-cols-2",
                "lg:grid-cols-2"
            );
            products_container.classList.add("grid-cols-1");
            products_container.innerHTML = `
                <div class="flex items-start gap-4 p-4 border border-red-200 dark:border-red-700 rounded-2xl shadow-sm bg-white dark:bg-[#1e1e1e]">
                    <div class="flex flex-col gap-2 w-full">
                        <h3 class="text-lg font-semibold text-red-900 dark:text-white">
                            ${response.message}
                        </h3>
                    </div>
                </div>
            `;
            return;
        }
        products_container.innerHTML = "";
        response.data.forEach((product) => {
            insertProduct(product);
        });
    });
};

function insertProduct(productObj) {
    let product = `
    <div
        class="flex items-start gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm bg-white dark:bg-[#1e1e1e]">
        <div class="flex flex-col gap-2 w-full">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                ${productObj.name}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                ${productObj.description}
            </p>
            <p class="text-base font-bold text-green-600 dark:text-green-400">
                $${productObj.price}
            </p>
        </div>
    </div>
    `;

    let products_container = document.getElementById("product-list");
    products_container.insertAdjacentHTML("beforeend", product);
}
