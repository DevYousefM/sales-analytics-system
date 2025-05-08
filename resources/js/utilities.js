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
