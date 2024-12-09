/**
 * Create and display a toast message
 *
 * @param {string} message
 * @param {string} type
 * @return {string}
 */
function toast(message, type = "success") {
    const main = document.querySelector("main");
    let toastContainer = main.querySelector(".toast-container");
    if (!toastContainer) {
        main.insertAdjacentHTML(
            "beforeend",
            `<div class="toast-container position-fixed bottom-0 end-0 p-3"></div>`
        );
        toastContainer = main.querySelector(".toast-container");
    }
    toastContainer.insertAdjacentHTML(
        "beforeend",
        `
        <div class="toast ${type} show fade" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="15000">
            <div class="toast-header">
                <strong class="me-auto">${
                    type.charAt(0).toUpperCase() + type.slice(1)
                }!</strong>
                <small class="text-muted">Just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <p class="paragraph">${message}</p>
            </div>
        </div>
        `
    );
}

/**
 * Funstion custom to fetch data from api
 * @param {string} url
 * @returns {Promise}
 */
async function fetchApi(url, method = "GET", options = {}) {
    const { headers, body: data } = options;
    try {
        const response = await fetch(url, {
            method,
            headers: {
                headers,
            },
            body: data ? JSON.stringify(data) : null,
        }).then((response) => response.json());

        // Display the toast message
        if (response.success) {
            toast(response.message, "success");
        } else {
            toast(response.message, "error");
        }

        // Return the response
        return response;
    } catch (error) {
        // Display the toast message
        toast(response.message, "error");

        console.error(error);
    }
}

(() => {
    "use strict";

    const imagePreview = new ImagePreview();

    let CSRF_TOKEN = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    document.querySelector("#toggleNavbar").addEventListener("click", () => {
        document.querySelector(".offcanvas-collapse").classList.toggle("open");
    });

    const tooltipTriggerList = document.querySelectorAll(
        '[data-bs-toggle="tooltip"]:not(:disabled)'
    );
    const tooltipList = [...tooltipTriggerList].map(
        (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
    );

    const btnSidebar = document.getElementById("toggleSidebar");
    const sidebar = document.getElementById("sidebar");

    if (btnSidebar && sidebar) {
        btnSidebar.addEventListener("click", () => {
            sidebar.classList.toggle("open");
        });
    }

    // Hide toast message
    document.addEventListener("hidden.bs.toast", function () {
        const toastContainer = document.querySelector(".toast-container");
        const toasts = toastContainer.querySelectorAll(".toast:not(.hide)");

        if (toasts.length === 0) {
            toastContainer.remove();
        }
    });
})();
