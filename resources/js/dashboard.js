import {
    onMessage,
    openSocket,
    initializeWebSocket,
} from "./services/websocket-client";

const ws = initializeWebSocket();

openSocket(ws, "analytics", "update-analytics");

onMessage(ws, "analytics", "update-analytics", (data) => {
    wsCallback(data);
});

function wsCallback(data) {
    setTotalRevenue(data.total_revenue);
    setOrdersCountInLastMinute(data.orders_count_in_last_minute);
    setRevenueChangeInLastMinute(data.revenue_change_in_last_minute);
    setTopProductsByQuantity(data.top_products_by_quantity);
}

const setTotalRevenue = (total_revenue) => {
    let e_total_revenue = document.getElementById("total-revenue");
    e_total_revenue.innerText =
        "$" + total_revenue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
};
const setOrdersCountInLastMinute = (count) => {
    let e_orders_count = document.getElementById("orders-count");
    e_orders_count.innerText = count;
};

const setRevenueChangeInLastMinute = (revenue_change_in_last_minute) => {
    let isIncrease = revenue_change_in_last_minute > 0;

    let e_revenue_change = document.getElementById("revenue-change");
    let e_revenue_change_indicator = document.getElementById(
        "revenue-change-indicator"
    );
    let e_revenue_change_label = document.getElementById(
        "revenue-change-label"
    );
    const _msg = " from the previous minute";
    if (isIncrease) {
        e_revenue_change.classList.remove("text-red-500");
        e_revenue_change.classList.add("text-green-500");
        e_revenue_change_indicator.classList.remove("text-red-500");
        e_revenue_change_indicator.classList.add("text-green-500");
        e_revenue_change_indicator.innerText = "▲";
        e_revenue_change_label.innerText = "Increased" + _msg;
    } else {
        e_revenue_change.classList.remove("text-green-500");
        e_revenue_change.classList.add("text-red-500");
        e_revenue_change_indicator.classList.remove("text-green-500");
        e_revenue_change_indicator.classList.add("text-red-500");
        e_revenue_change_indicator.innerText = "▼";
        e_revenue_change_label.innerText = "Decreased" + _msg;
    }
    e_revenue_change.innerText =
        "$" +
        Math.abs(revenue_change_in_last_minute)
            .toFixed(2)
            .replace(/\d(?=(\d{3})+\.)/g, "$&,");
};

const setTopProductsByQuantity = (products) => {
    let e_products_container = document.getElementById("top-products");

    const maxQuantity = Math.max(
        ...products.map((product) => product.total_quantity)
    );

    products.forEach((product) => {
        let e_product = document.getElementById(
            "top-product-" + product.product_id
        );

        if (e_product) {
            let e_product_quantity = document.getElementById(
                "top-product-quantity-" + product.product_id
            );
            let e_product_bar = document.getElementById(
                "top-product-bar-" + product.product_id
            );

            e_product_quantity.innerText = product.total_quantity;

            let barWidth = (product.total_quantity / maxQuantity) * 100 + "%";

            e_product_bar.style.width = barWidth;
        } else {
            let e_product_html = `
            <div id="top-product-${
                product.product_id
            }" class="product-container">
                <div class="flex justify-between text-sm text-gray-700 dark:text-gray-300">
                    <span>${product.product_name}</span>
                    <span id="top-product-quantity-${product.product_id}">${
                product.total_quantity
            }</span>
                </div>
                <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded">
                    <div class="h-2 bg-blue-500 rounded transition-all transform duration-500 ease-in-out" style="width: ${Math.min(
                        100,
                        (product.total_quantity / maxQuantity) * 100
                    )}%" id="top-product-bar-${product.product_id}">
                    </div>
                </div>
            </div>
            `;

            e_products_container.insertAdjacentHTML(
                "afterbegin",
                e_product_html
            );
        }
    });

    let existingProductIds = products.map((product) => product.product_id);
    let productElements =
        e_products_container.querySelectorAll(".product-container");

    productElements.forEach((element) => {
        let productId = parseInt(element.id.replace("top-product-", ""));
        if (!existingProductIds.includes(productId)) {
            element.remove();
        }
    });
};
