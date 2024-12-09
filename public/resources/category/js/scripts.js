(() => {
    "use strict";

    /**
     * Edit Category: logic
     * @type {{CREATE: boolean, UPDATE: boolean}}
     */
    const FORM_STATUS = {
        CREATE: true,
        UPDATE: false,
    };

    const categoryForm = document.getElementById("categoryForm");
    const btnCreateCategory = document.getElementById("btnCreateCategory");
    const btnUpdateCategory = document.getElementById("btnUpdateCategory");
    const btnResetForm = document.getElementById("btnResetForm");
    const btnEdit = document.querySelectorAll(".btn-edit");
    const formTitle = document.querySelector(".category-form .card-title");

    btnEdit.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();

            // Scroll to the form
            categoryForm.scrollIntoView({
                behavior: "smooth",
                block: "center",
                inline: "center",
            });

            if (FORM_STATUS.CREATE) {
                // Change action of form
                categoryForm.action = `/category/update/${btn
                    .closest("tr")
                    .querySelector("th[scope='row']")
                    .getAttribute("data-id")}`;

                btnUpdateCategory.disabled = false;
                btnUpdateCategory.ariaDisabled = false;
                btnCreateCategory.disabled = true;

                // Give class active to edit button
                btn.closest("tr").classList.add("table-active");

                // Change the title of the category form
                formTitle.textContent = "Form Update Category";

                // Change the title and text content of the reset button
                btnResetForm.attributes.title.value =
                    "Button: Click to reset the form and change the form status to create new category";
                btnResetForm.textContent = "Cancel";

                const categoryName = btn
                    .closest("tr")
                    .querySelector("td:nth-child(2)").textContent;

                categoryForm.name.value = categoryName;

                // Change form status
                FORM_STATUS.CREATE = false;
                FORM_STATUS.UPDATE = true;
            } else {
                const categoryFormSection =
                    document.querySelector(".category-form");
                categoryFormSection.classList.replace(
                    "border-light",
                    "border-danger"
                );

                // Change border color back to light after timeout
                setTimeout(() => {
                    categoryFormSection.classList.replace(
                        "border-danger",
                        "border-light"
                    );
                }, 1200); // Adjust this duration as needed
            }
        });
    });

    btnResetForm.addEventListener("click", (e) => {
        e.preventDefault();
        categoryForm.reset();

        // Scroll to the table
        document
            .querySelector('table[aria-label="Table: Categories"]')
            .scrollIntoView({
                behavior: "smooth",
                block: "center",
                inline: "center",
            });

        if (FORM_STATUS.UPDATE) {
            // Change action of form
            categoryForm.action = "/category/store";

            // Search and remove class active from edit button
            btnEdit.forEach((btn) => {
                if (btn.closest("tr").classList.contains("table-active")) {
                    btn.closest("tr").classList.remove("table-active");
                }
            });

            // Change the title of the category form
            formTitle.textContent = "Form Create Category";

            // Change the title and text content of the reset button
            btnResetForm.attributes.title.value =
                "Button: Click to reset the form";
            btnResetForm.textContent = "Reset";

            // Change form status
            FORM_STATUS.CREATE = true;
            FORM_STATUS.UPDATE = false;
        }
    });
})();
