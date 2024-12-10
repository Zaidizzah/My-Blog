(() => {
    "use strict";

    const FORM_STATUS = {
        reply: false,
    };

    let BUTTON_ELEMENT = null;

    const COMMENT_FORM_CONTAINER = document.querySelector(
        ".comment-form-container"
    );
    const COMMENT_FORM = document.querySelector(".comment-form");
    const COMMENT_FORM_DEFAULT_ACTION = COMMENT_FORM.action;
    const BTNREPLY = document.querySelectorAll(".comment-reply");
    const REPLYTO = document.querySelector("#replying-to span");

    if (BTNREPLY) {
        BTNREPLY.forEach((button) => {
            button.addEventListener("click", function () {
                if (!FORM_STATUS.reply) {
                    const COMMENT_ID =
                        this.closest(".comment").getAttribute(
                            "data-comment-id"
                        );

                    const COMMENT_WRITTER = this.closest(
                        ".comment"
                    ).getAttribute("data-comment-writter");

                    // Set active comment
                    this.closest(".comment").classList.add("active");

                    // Change text content to 'Cancel'
                    this.textContent = "Cancel";

                    // Change text content to 'Replying to: {writter name}'
                    REPLYTO.textContent = `Replying to: @${COMMENT_WRITTER}`;

                    // Change action form
                    COMMENT_FORM.action = `/comment/reply/${COMMENT_ID}`;

                    // Set focus to textarea
                    COMMENT_FORM.description.focus;

                    // Change form status to reply: true
                    FORM_STATUS.reply = true;

                    // Change BUTTON_ELEMENT value to this button
                    BUTTON_ELEMENT = this;
                } else {
                    if (this != BUTTON_ELEMENT) {
                        // Change color border from comment-form-container to red for a some time
                        COMMENT_FORM_CONTAINER.classList.add("border-danger");
                        setTimeout(() => {
                            COMMENT_FORM_CONTAINER.classList.remove(
                                "border-danger"
                            );
                        }, 3500);

                        toast("Cancel the reply first.", "warning");
                    } else {
                        // remove active comment
                        this.closest(".comment").classList.remove("active");

                        // Change text content to 'Reply'
                        this.textContent = "Reply";

                        // Change text content to 'Replying to:'
                        REPLYTO.textContent = "Replying to:";

                        // Change action form
                        COMMENT_FORM.action = COMMENT_FORM_DEFAULT_ACTION;

                        // Change form status to reply: false
                        FORM_STATUS.reply = false;
                    }
                }
            });
        });
    }
})();
