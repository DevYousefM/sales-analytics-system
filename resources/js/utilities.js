export const showValidationMessages = (errors) => {
    Object.entries(errors).forEach(([key, value]) => {
        let input_element = document.querySelector(
            'input[name="' + key + '"], textarea[name="' + key + '"]'
        );
        let message_container = `<p class="text-xs text-red-500 error_msg">${value[0]}</p>`;

        input_element.insertAdjacentHTML("afterend", message_container);
    });
};
export const emptyErrorMessages = () => {
    document.querySelectorAll(".error_msg").forEach((element) => {
        element.remove();
    });
};
export const showSuccessMessage = (form, message) => {
    let message_container = `<p class="text-sm text-green-500 success_msg text-center">${message}</p>`;
    form.insertAdjacentHTML("beforeend", message_container);
};
export const showToastr = (message, type = "info", milliseconds) => {
    const types = {
        success: { bg: "bg-green-500", title: "Success" },
        error: { bg: "bg-red-500", title: "Error" },
        info: { bg: "bg-blue-500", title: "Info" },
        warning: { bg: "bg-yellow-400 text-black", title: "Warning" },
    };

    const selected = types[type] || types.info;

    const toast = document.createElement("div");
    toast.innerHTML = `
        <div
            class="toast ${selected.bg} text-white rounded-lg shadow-lg px-4 py-3 flex items-start space-x-3 w-80 animate-fade-in">
            <div class="flex justify-between w-full">
                <span class="text-sm leading-6">${message}</span>
                <button class="text-white hover:text-gray-200" onclick="this.parentElement.remove()">×</button>
            </div>
        </div>
    `;

    let container = document.getElementById("toast-container");
    if (!container) {
        const e_container = document.createElement("div");
        e_container.id = "toast-container";
        e_container.className =
            "fixed top-5 right-5 flex flex-col space-y-3 z-50";
        document.body.appendChild(e_container);
        container = e_container;
    }
    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add("opacity-0");
        setTimeout(() => toast.remove(), 300);
    }, milliseconds || 2000);
};
