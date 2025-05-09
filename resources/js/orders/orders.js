import {
    initializeWebSocket,
    openSocket,
    onMessage,
} from "./../services/websocket-client";
import { showToastr } from "./../utilities";

const ws = initializeWebSocket();

const channel = "orders";
const event = "order-created";

openSocket(ws, channel, event);

onMessage(ws, channel, event, (data) => {
    wsCallback(data);
});

const wsCallback = (data) => {
    updateOrdersList(data);
};

const updateOrdersList = (order) => {
    let no_orders = document.getElementById("no-orders");
    if (no_orders) {
        no_orders.remove();
    }

    let orders_container = document.getElementById("order-list");
    const price = order.price.toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
    let order_html = `
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
                        <div>${price}</div>

                        <div class="font-medium">Quantity:</div>
                        <div>${order.quantity}</div>

                        <div class="font-medium">Order Date:</div>
                        <div>${order.date}</div>
                    </div>
                </div>
            </div>`;

    orders_container.insertAdjacentHTML("afterbegin", order_html);

    showToastr("There is a new order created", "success");
};
