import { post, endpoints } from "./services/api-service";
import { emptyErrorMessages } from "./utilities";
let add_form = document.getElementById("product-form");
let submit_button = document.getElementById("submit-button");

submit_button.addEventListener("click", () => {
    emptyErrorMessages();

    post(endpoints.add_product, add_form, (response, status) => {
        if (status === 200) {
            insertProduct(response.data);
        }
    });
});

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
            <p
                class="text-base font-bold ${
                    productObj.temp_category === "HOT"
                        ? "text-red-600"
                        : "text-blue-600"
                }">
                ${productObj.temp_category}
            </p>
        </div>
    </div>
    `;

    let products_container = document.getElementById("product-list");
    products_container.insertAdjacentHTML("afterbegin", product);
}
