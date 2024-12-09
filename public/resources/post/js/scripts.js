(() => {
    "use strict";

    /**
     * Post form logic
     */
    const POST_SECTION = document.querySelector(".post-form");
    const POST_FORM = document.querySelector(".post-form form");
    const btnCreatePost = document.getElementById("btnCreatePost");
    const btnUpdatePost = document.getElementById("btnUpdatePost");
    const btnResetForm = document.getElementById("btnResetForm");
    const formTitle = document.querySelector(".post-form .card-title");
    const inputImage = document.getElementById("image");
    const imagePreviewContainer = document.querySelector(
        ".image-preview-container"
    );

    const DEFAULT_IMAGE = "images/no-image-available.jpg";
    const FORM_STATUS = {
        CREATE: true,
        UPDATE: false,
    };

    /**
     * Cancel update post
     */
    btnResetForm.addEventListener("click", (e) => {
        e.preventDefault();
        POST_FORM.reset();

        POST_FORM.scrollIntoView({
            behavior: "smooth",
            block: "center",
            inline: "center",
        });

        // Change title and etc. of image preview container
        imagePreviewContainer.title = "Thumbnail: No image available";
        imagePreviewContainer.querySelector("span small").textContent =
            "No image available";
        imagePreviewContainer
            .querySelector(".image-preview")
            .setAttribute(
                "data-img-preview-title",
                "Thumbnail: No image available"
            );
        imagePreviewContainer.querySelector(".image-preview").src =
            DEFAULT_IMAGE;

        if (FORM_STATUS.UPDATE) {
            // Change action of form
            POST_FORM.action = "/post/store";

            // Change the title of the post form
            formTitle.textContent = "Form Create Post";

            // Change the title and text content of the reset button
            btnResetForm.attributes.title.value =
                "Button: Click to reset the form";
            btnResetForm.textContent = "Reset";

            // Change form status
            FORM_STATUS.CREATE = true;
            FORM_STATUS.UPDATE = false;
        }
    });

    /**
     * debounce function
     */
    const debounce = (func, wait) => {
        let timeout;
        return function (...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    };

    /**
     * Initialize TinyMCE to textarea for content Post
     */
    const TINY_MCE = tinymce.init({
        selector: "#content",
        placeholder: "Ketik disini...",
        plugins:
            "accordion advlist anchor autolink autosave charmap code codesample directionality emoticons fullscreen help importcss insertdatetime link lists media nonbreaking pagebreak preview quickbars save searchreplace table template visualblocks visualchars wordcount",
        toolbar:
            "undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat",
        setup: function (editor) {
            editor.on("SaveContent", function (e) {
                e.content = e.content.replace(/<p><\/p>/g, "");
            });

            editor.on("init", function (e) {
                editor.setContent(editor.getContent());
            });
        },
        promotion: false,
    });

    /**
     * save content from tinyMCE Editor to elemnt content (textarea) and remove <p> tag if empty
     */
    const validateTinyMCE = () => {
        const content = tinymce.activeEditor.getContent();

        return content.replace(/<p><\/p>/g, "") === "" ? false : true;
    };

    POST_FORM.addEventListener("submit", (e) => {
        e.preventDefault();

        const _e = confirm(
            `Are you sure to ${
                FORM_STATUS.CREATE ? "create" : "update"
            } this post?`
        );
        if (_e) {
            // Save content from tinyMCE Editor
            tinymce.triggerSave();

            if (validateTinyMCE()) {
                POST_FORM.submit();
            }

            // Scroll to the tinyMCE Editor
            document.querySelector(".tox-tinymce").scrollIntoView({
                behavior: "smooth",
                block: "center",
                inline: "center",
            });

            // Give class border-danger to tinyMCE Editor
            if (!validateTinyMCE()) {
                document
                    .querySelector(".tox-tinymce")
                    .classList.add("border-danger");

                // Change border color back to light after timeout
                setTimeout(() => {
                    document
                        .querySelector(".tox-tinymce")
                        .classList.remove("border-danger");
                }, 4500);
            }
        }
    });

    /**
     * Creat slug from title
     */
    const title = document.getElementById("title");
    const slug = document.getElementById("slug");

    const updateSlug = () => {
        slug.value = title.value
            .replace(/[^a-z0-9]+/gi, "-")
            .replace(/^-+|-+$/g, "");
    };

    title.addEventListener("input", debounce(updateSlug, 500));

    /**
     * Create and display edit and delete button element post logic
     *
     * @returns {string} - structure of edit and delete button
     */
    function createDisplayEditAndDeleteElement(POST_WRAPPER) {
        const POST_ID = POST_WRAPPER.getAttribute("data-post-id");

        // Create container for edit and delete button
        const BUTTON_CONTAINER = document.createElement("div");
        BUTTON_CONTAINER.classList.add("post-action-buttons");

        // Edit Button
        const EDITBUTTON = document.createElement("button");
        EDITBUTTON.innerHTML = `
        <i class="fa fa-edit fa-fw"></i>
    `;
        EDITBUTTON.classList.add("btn", "btn-warning", "border", "border-2");
        EDITBUTTON.setAttribute("role", "button");
        EDITBUTTON.setAttribute("title", "Button: to edit a post");
        EDITBUTTON.addEventListener("click", () => {
            TINY_MCE.then((editors) => {
                const URL = `/post/get?id=${POST_ID}`;
                /**
                 * Fetch data from server to populate form input fields when user click
                 * edit button. If the form status is not create, return a promise rejected
                 * with an object that has a success property set to false and a message
                 * property set to an error message.
                 *
                 * @returns {Promise} - A promise that resolves with an object that has a
                 * success property set to true and a data property set to the post data
                 * from the server. If the form status is not create, the promise is
                 * rejected with an object that has a success property set to false and a
                 * message property set to an error message.
                 */
                const DATA = () => {
                    if (FORM_STATUS.CREATE) {
                        return fetchApi(URL, "GET", {
                            headers: {
                                "Content-Type": "application/json",
                            },
                        });
                    }

                    return Promise.resolve({
                        success: false,
                        message:
                            "Invalid form status, Please cancel update post first.",
                        data: null,
                    });
                };
                DATA().then((response) => {
                    if (response.success) {
                        /**
                         * Handle form status
                         */
                        const {
                            body: POST_CONTENT,
                            category_id: POST_CATEGORY_ID,
                            id: POST_ID,
                            image: POST_IMAGE,
                            slug: POST_SLUG,
                            title: POST_TITLE,
                            user_id: POST_USER_ID,
                        } = response.data;

                        POST_FORM.scrollIntoView({
                            behavior: "smooth",
                            block: "center",
                            inline: "center",
                        });

                        if (FORM_STATUS.CREATE) {
                            // Change action of form
                            POST_FORM.action = `/post/update/${POST_ID}`;
                            btnUpdatePost.disabled = false;
                            btnUpdatePost.ariaDisabled = false;
                            btnCreatePost.disabled = true;

                            // Change the title of the post form
                            formTitle.textContent = "Form Update Post";

                            // Change the title and text content of the reset button
                            btnResetForm.attributes.title.value =
                                "Button: Click to reset the form and change the form status to create new post";
                            btnResetForm.textContent = "Cancel";

                            POST_FORM.title.value = POST_TITLE;
                            POST_FORM.slug.value = POST_SLUG;
                            imagePreviewContainer.title = `Thumbnail: ${POST_TITLE}`;
                            imagePreviewContainer.querySelector(
                                "span small"
                            ).textContent = POST_TITLE;
                            imagePreviewContainer
                                .querySelector(".image-preview")
                                .setAttribute(
                                    "data-img-preview-title",
                                    `Thumbnail: ${POST_TITLE}`
                                );
                            imagePreviewContainer.querySelector(
                                ".image-preview"
                            ).src = POST_IMAGE
                                ? `storage/images/posts/${POST_IMAGE}`
                                : DEFAULT_IMAGE;
                            tinymce
                                .get("content")
                                .setContent(POST_CONTENT, { format: "raw" });

                            const selectedOption =
                                POST_FORM.category.querySelector(
                                    "option[selected]"
                                );
                            if (selectedOption) {
                                selectedOption.selected = false;
                            }
                            const option = POST_FORM.category.querySelector(
                                `option[value='${POST_CATEGORY_ID}']`
                            );
                            if (option) {
                                option.selected = true;
                            }

                            // Change form status
                            FORM_STATUS.CREATE = false;
                            FORM_STATUS.UPDATE = true;
                        } else {
                            // Change form border color
                            const postFormSection =
                                document.querySelector(".post-form");
                            postFormSection.classList.replace(
                                "border-light",
                                "border-danger"
                            );

                            // Change border color back to light after timeout
                            setTimeout(() => {
                                postFormSection.classList.replace(
                                    "border-danger",
                                    "border-light"
                                );
                            }, 1200); // Adjust this duration as needed
                        }
                    } else {
                        toast(response.message, "error");
                        POST_SECTION.classList.replace(
                            "border-light",
                            "border-danger"
                        );

                        setTimeout(() => {
                            POST_SECTION.classList.replace(
                                "border-danger",
                                "border-light"
                            );
                        }, 3500);

                        // Set focus and scroll to button reset form
                        POST_FORM.querySelectorAll(".btn-group button").forEach(
                            (button) => {
                                button.classList.replace(
                                    "border-dark",
                                    "border-danger"
                                );
                            }
                        );
                        setTimeout(() => {
                            POST_FORM.querySelectorAll(
                                ".btn-group button"
                            ).forEach((button) => {
                                button.classList.replace(
                                    "border-danger",
                                    "border-dark"
                                );
                            });
                        }, 2200);
                        btnResetForm.scrollIntoView({
                            behavior: "smooth",
                            block: "center",
                            inline: "center",
                        });
                        btnResetForm.focus();
                    }
                });
            });
        });

        // Delete Button
        const DELETEBUTTON = document.createElement("a");
        DELETEBUTTON.setAttribute("href", `/post/delete/${POST_ID}`);
        DELETEBUTTON.innerHTML = `
        <i class="fa fa-trash fa-fw"></i>
    `;
        DELETEBUTTON.classList.add("btn", "btn-danger", "border", "border-2");
        DELETEBUTTON.setAttribute("role", "button");
        DELETEBUTTON.setAttribute("title", "Button: to delete a post");
        DELETEBUTTON.setAttribute(
            "onclick",
            "return confirm('Are you sure to delete this post?')"
        );

        // Append the buttons to container
        BUTTON_CONTAINER.appendChild(EDITBUTTON);
        BUTTON_CONTAINER.appendChild(DELETEBUTTON);

        return BUTTON_CONTAINER;
    }

    // Create and display edit and delete button element
    document.querySelectorAll(".--post-wrapper").forEach((postWrapper) => {
        const actionButtons = createDisplayEditAndDeleteElement(postWrapper);
        postWrapper.classList.add("position-relative");
        postWrapper.appendChild(actionButtons);
    });

    // Handle input file and preview image
    inputImage.addEventListener("change", () => {
        const VALIDATE_CONFIG = {
            type: [
                "image/jpeg",
                "image/png",
                "image/jpg",
                "image/webp",
                "image/gif",
                "image/svg+xml",
            ],
            size: 3.5 * 1024 * 1024,
            maxWidth: 1600,
            maxHeight: 2564,
            message: {
                type: "*Invalid image type",
                size: "*Image size is too large",
                maxWidth: "*Image width is too large",
                maxHeight: "*Image height is too large",
            },
        };

        const file = inputImage.files[0];
        if (file) {
            const validate = () => {
                const errors = [];

                if (!VALIDATE_CONFIG.type.includes(file.type)) {
                    errors.push(VALIDATE_CONFIG.message.type);
                }

                if (file.size > VALIDATE_CONFIG.size) {
                    errors.push(VALIDATE_CONFIG.message.size);
                }

                const image = new Image();
                image.src = URL.createObjectURL(file);
                image.onload = () => {
                    if (image.width > VALIDATE_CONFIG.maxWidth) {
                        errors.push(VALIDATE_CONFIG.message.maxWidth);
                    }

                    if (image.height > VALIDATE_CONFIG.maxHeight) {
                        errors.push(VALIDATE_CONFIG.message.maxHeight);
                    }

                    if (errors.length) {
                        // Change title and etc. of image preview container
                        imagePreviewContainer.title =
                            "Thumbnail: No image available";
                        imagePreviewContainer.querySelector(
                            "span small"
                        ).textContent = "No image available";
                        imagePreviewContainer
                            .querySelector(".image-preview")
                            .setAttribute(
                                "data-img-preview-title",
                                "Thumbnail: No image available"
                            );
                        imagePreviewContainer.querySelector(
                            ".image-preview"
                        ).src = DEFAULT_IMAGE;
                        // Reset input
                        inputImage.value = "";

                        errors.forEach((error) => toast(error, "error"));
                    } else {
                        const reader = new FileReader();

                        reader.onload = () => {
                            // Change title and etc. of image preview container
                            imagePreviewContainer.title = `Thumbnail: ${file.name}`;
                            imagePreviewContainer.querySelector(
                                "span small"
                            ).textContent = file.name;
                            imagePreviewContainer
                                .querySelector(".image-preview")
                                .setAttribute(
                                    "data-img-preview-title",
                                    file.name
                                );

                            imagePreviewContainer.querySelector(
                                ".image-preview"
                            ).src = reader.result;
                        };

                        reader.readAsDataURL(file);

                        // Revoke object URL
                        URL.revokeObjectURL(image.src);
                    }
                };
            };

            validate();
        } else {
            // Change title and etc. of image preview container
            imagePreviewContainer.title = "Thumbnail: No image available";
            imagePreviewContainer.querySelector("span small").textContent =
                "No image available";
            imagePreviewContainer
                .querySelector(".image-preview")
                .setAttribute(
                    "data-img-preview-title",
                    "Thumbnail: No image available"
                );
            imagePreviewContainer.querySelector(".image-preview").src =
                DEFAULT_IMAGE;
        }
    });
})();
