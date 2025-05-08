import { showSuccessMessage, showValidationMessages } from "../utilities";
let base_url = import.meta.env.VITE_API_URL;

export const endpoints = {
    add_product: base_url + "/api/products/create",
    get_products: base_url + "/api/products/get",
    add_order: base_url + "/api/orders/create",
};

export const post = (url, form, callback = null) => {
    let formData = new FormData(form);
    let xhr = new XMLHttpRequest();

    xhr.open("POST", url);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("Accept", "application/json");

    let obj = {};
    formData.forEach((value, key) => {
        obj[key] = value;
    });

    xhr.send(JSON.stringify(obj));

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            let response = null;
            try {
                if (xhr.responseText) {
                    response = JSON.parse(xhr.responseText);
                }
            } catch (error) {
                console.error("Error parsing JSON:", error);
                response = { error: "Invalid JSON response" };
            }

            if (xhr.status === 422 && response?.errors) {
                showValidationMessages(response.errors);
            } else if (xhr.status === 200 && response) {
                form.reset();
                showSuccessMessage(form, response.message);
            }

            if (typeof callback === "function") {
                callback(response, xhr.status);
            }
        }
    };

    return xhr;
};

export const get = (url, callback = null) => {
    let xhr = new XMLHttpRequest();

    xhr.open("GET", url);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("Accept", "application/json");

    xhr.send();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            let response = null;
            try {
                if (xhr.responseText) {
                    response = JSON.parse(xhr.responseText);
                }
            } catch (error) {
                console.error("Error parsing JSON:", error);
                response = { error: "Invalid JSON response" };
            }

            if (xhr.status === 200 && response) {
                if (typeof callback === "function") {
                    callback(response, xhr.status);
                }
            }
        }
    };

    return xhr;
};
