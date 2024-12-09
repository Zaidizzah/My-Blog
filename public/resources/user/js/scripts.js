(() => {
    "use strict";

    /**
     * Edit User: logic
     * @type {{CREATE: boolean, UPDATE: boolean}}
     */
    const FORM_STATUS = {
        CREATE: true,
        UPDATE: false,
    };

    const userForm = document.getElementById("userForm");
    const btnCreateUser = document.getElementById("btnCreateUser");
    const btnUpdateUser = document.getElementById("btnUpdateUser");
    const btnResetForm = document.getElementById("btnResetForm");
    const btnEdit = document.querySelectorAll(".btn-edit");
    const formTitle = document.querySelector(".user-form .card-title");

    btnEdit.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();

            // Scroll to the form
            userForm.scrollIntoView({
                behavior: "smooth",
                block: "center",
                inline: "center",
            });

            if (FORM_STATUS.CREATE) {
                // Change action of form
                userForm.action = `/user/update/${btn
                    .closest("tr")
                    .querySelector("th[scope='row']")
                    .getAttribute("data-id")}`;

                btnUpdateUser.disabled = false;
                btnUpdateUser.ariaDisabled = false;
                btnCreateUser.disabled = true;

                // Give class active to edit button
                btn.closest("tr").classList.add("table-active");

                // Change the title of the user form
                formTitle.textContent = "Form Update User";

                // Change the title and text content of the reset button
                btnResetForm.attributes.title.value =
                    "Button: Click to reset the form and change the form status to create new user";
                btnResetForm.textContent = "Cancel";

                const username = btn
                    .closest("tr")
                    .querySelector("td:nth-child(2)").textContent;
                const name = btn
                    .closest("tr")
                    .querySelector("td:nth-child(3)").textContent;
                const email = btn
                    .closest("tr")
                    .querySelector("td:nth-child(4)").textContent;

                userForm.username.value = username;

                // Give attribut disabled to username and password input field
                userForm.username.disabled = true;
                userForm.password.disabled = true;

                userForm.name.value = name;
                userForm.email.value = email;

                // Change form status
                FORM_STATUS.CREATE = false;
                FORM_STATUS.UPDATE = true;
            } else {
                // Change form border color
                const userFormSection = document.querySelector(".user-form");
                userFormSection.classList.replace(
                    "border-light",
                    "border-danger"
                );

                // Change border color back to light after timeout
                setTimeout(() => {
                    userFormSection.classList.replace(
                        "border-danger",
                        "border-light"
                    );
                }, 1200); // Adjust this duration as needed
            }
        });
    });

    btnResetForm.addEventListener("click", (e) => {
        e.preventDefault();
        userForm.reset();

        // Scroll to the table
        document
            .querySelector('table[aria-label="Table: Users"]')
            .scrollIntoView({
                behavior: "smooth",
                block: "center",
                inline: "center",
            });

        if (FORM_STATUS.UPDATE) {
            // Change action of form
            userForm.action = "/user/store";

            // Search and remove class active from edit button
            btnEdit.forEach((btn) => {
                if (btn.closest("tr").classList.contains("table-active")) {
                    btn.closest("tr").classList.remove("table-active");
                }
            });

            // Change the title of the user form
            formTitle.textContent = "Form Create User";

            // Change the title and text content of the reset button
            btnResetForm.attributes.title.value =
                "Button: Click to reset the form";
            btnResetForm.textContent = "Reset";

            // deactivate attribut disabled from username and password input field
            userForm.username.disabled = false;
            userForm.password.disabled = false;

            // Change form status
            FORM_STATUS.CREATE = true;
            FORM_STATUS.UPDATE = false;
        }
    });
})();
