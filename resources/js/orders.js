import { post, get, endpoints } from "./services/api-service";
import { emptyErrorMessages } from "./utilities";

const dropdownToggle = document.getElementById("dropdownToggle");
const productsList = document.getElementById("products_list");
const productForm = document.getElementById("product-form");
const productIdField = document.getElementById("product_id");

function toggleDropdown() {
    productsList.classList.toggle("hidden");

    if (!productsList.classList.contains("hidden")) {
        console.log("Fetching Products");

        fetchProducts();
    }
}

function fetchProducts() {
    get(endpoints.get_products, (response, status) => {
        if (status === 200) {
            renderProducts(response.data);
        }
    });
}

function renderProducts(products) {
    productsList.innerHTML = products
        .map(
            (product) =>
                `<li data-id="${product.id}" class="py-2 px-4 cursor-pointer">${product.name}</li>`
        )
        .join("");

    const productItems = productsList.querySelectorAll("li");
    productItems.forEach((item) => {
        item.addEventListener("click", () => {
            selectProduct(item);
        });
    });
}
dropdownToggle.addEventListener("click", (e) => {
    e.stopPropagation();
    toggleDropdown();
});

function selectProduct(product) {
    const productId = product.getAttribute("data-id");
    const productName = product.textContent;

    dropdownToggle.textContent = productName;

    productIdField.value = productId;

    productsList.classList.add("hidden");
}

const productItems = productsList.querySelectorAll("li");
productItems.forEach((item) => {
    item.addEventListener("click", () => {
        selectProduct(item);
    });
});

document.addEventListener("click", (e) => {
    if (!e.target.closest(".relative")) {
        productsList.classList.add("hidden");
    }
});

productsList.addEventListener("click", (e) => {
    e.stopPropagation();
});

let add_form = document.getElementById("order-form");
let submit_button = document.getElementById("submit-button");

submit_button.addEventListener("click", () => {
    emptyErrorMessages();

    post(endpoints.add_order, add_form, (response, status) => {
        if (status === 200) {
            insertOrder(response.data);
            console.log("Order Added", response.data);
        }
    });
});

const insertOrder = (order) => {
    console.log(order);

    const orderHtml = `
        <div
            class="flex items-start gap-4 p-6 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-md bg-white dark:bg-[#1e1e1e] transition hover:shadow-lg">
            <div class="flex flex-col gap-3 w-full">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    Product: ${order.product.name}
                </h3>
                <div class="grid grid-cols-2 gap-y-2 text-sm text-gray-700 dark:text-gray-300">
                    <div class="font-medium">Product ID:</div>
                    <div>${order.product_id}</div>

                    <div class="font-medium">Price:</div>
                    <div>${order.price}</div>

                    <div class="font-medium">Quantity:</div>
                    <div>${order.quantity}</div>

                    <div class="font-medium">Order Date:</div>
                    <div>${order.date}</div>
                </div>
            </div>
        </div>
    `;

    let orderContainer = document.getElementById("order-list");
    orderContainer.insertAdjacentHTML("afterbegin", orderHtml);
};
