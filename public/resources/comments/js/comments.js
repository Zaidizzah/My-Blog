(() => {
    "use strict";

    /**
     * Expanded the comments container
     */
    const BTN_EXPAND_COMMENTS = document.querySelectorAll(
        ".btn-expand-comments"
    );

    if (BTN_EXPAND_COMMENTS) {
        BTN_EXPAND_COMMENTS.forEach((button) => {
            button.addEventListener("click", () => {
                const CONTAINER =
                    button.closest(".comments-container") ??
                    button.closest(".reported-comments-container");

                CONTAINER.classList.toggle("expanded");
            });
        });
    }

    /**
     * Comments: logic
     */

    const FORM_STATUS = {
        reply: false,
        report: false,
    };

    const COMMENTS_CONFIG = {
        offset: 10,
        limit: 10,
    };

    let BUTTON_REPLY_ELEMENT = null;

    const COMMENT_FORM_CONTAINER = document.querySelector(
        ".comment-form-container"
    );
    const COMMENT_FORM = document.querySelector(".comment-form");
    const COMMENT_FORM_DEFAULT_ACTION = COMMENT_FORM
        ? COMMENT_FORM.action
        : null;
    const BTNREPLY = document.querySelectorAll(".comment-reply");
    const REPLYTO = document.querySelector("#replying-to span");

    if (BTNREPLY) {
        BTNREPLY.forEach((button) => {
            button.addEventListener("click", function () {
                if (!FORM_STATUS.reply) {
                    const COMMENT_ID = button
                        .closest(".comment")
                        .getAttribute("data-comment-id");

                    const COMMENT_WRITTER = button
                        .closest(".comment")
                        .getAttribute("data-comment-writter");

                    // Set active comment
                    button.closest(".comment").classList.add("active");

                    COMMENT_FORM.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                        inline: "center",
                    });

                    // Change text content to 'Cancel'
                    button.textContent = "Cancel";

                    // Change text content to 'Replying to: {writter name}'
                    REPLYTO.textContent = `Replying to: @${COMMENT_WRITTER}`;

                    // Change action form
                    COMMENT_FORM.action = `/comments/reply/${COMMENT_ID}`;

                    // Set focus to textarea
                    COMMENT_FORM.description.focus;

                    // Change form status to reply: true
                    FORM_STATUS.reply = true;

                    // Change BUTTON_ELEMENT value to this button
                    BUTTON_REPLY_ELEMENT = button;
                } else {
                    if (button != BUTTON_REPLY_ELEMENT) {
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
                        button.closest(".comment").classList.remove("active");

                        // Change text content to 'Reply'
                        button.textContent = "Reply";

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

    /**
     * Reporting comments: Logic
     */

    const COMMENT_REPORT_MODAL = `
        <div class="modal fade show" id="reportCommentModal" tabindex="-1" aria-labelledby="reportCommentModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" style="display: block;" aria-hidden="false" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content border-dark shadow-lg">
                    <div class="modal-header border-dark">
                        <h1 class="modal-title text-dark fs-5" id="reportCommentModalLabel">Report Comment</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/comments/report" id="reportCommentForm" method="POST">
                        <div class="modal-body border-dark">
                            <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                            <div class="mb-3">
                                <label for="reportCommentModalReason" class="form-label text-dark">Reason</label>
                                <select class="form-select border-dark" name="type" id="reportCommentModalReason">
                                    <option value="spam">Spam</option>
                                    <option value="offensive">Offensive</option>
                                    <option value="abuse">Abuse</option>
                                    <option value="false_information">Hoax</option>
                                    <option value="other" selected>Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="reportCommentModalDescription" class="form-label text-dark">Description</label>
                                <textarea class="form-control border-dark" name="description" id="reportCommentModalDescription" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-dark">
                            <button type="button" class="btn btn-secondary border-dark" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger border-dark" onclick="return confirm('Are you sure want to report this comment?')">Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    const BTN_REPORT_COMMENT = document.querySelectorAll(".comment-report");
    let BUTTON_REPORT_ELEMENT = null;

    if (BTN_REPORT_COMMENT) {
        BTN_REPORT_COMMENT.forEach((button) => {
            button.addEventListener("click", () => {
                const CONTAINER = button.closest(".comment");

                if (
                    CONTAINER.getAttribute("data-comment-id") == null ||
                    CONTAINER.getAttribute("data-label-reported") == "true"
                ) {
                    toast("This comment has been reported.", "warning");
                    return;
                }
                if (BUTTON_REPORT_ELEMENT === null) {
                    BUTTON_REPORT_ELEMENT = button;
                }

                if (button != BUTTON_REPORT_ELEMENT) {
                    toast("Cancel the report first.", "warning");
                    return;
                }

                const COMMENT_ID = CONTAINER.getAttribute("data-comment-id");

                // adding active comment
                CONTAINER.classList.add("active");

                // SChange text content to 'Cancel'
                button.textContent = "Cancel";

                COMMENT_FORM_CONTAINER.insertAdjacentHTML(
                    "afterend",
                    COMMENT_REPORT_MODAL
                );

                // Change action form
                document.getElementById(
                    "reportCommentForm"
                ).action = `/comments/report/${COMMENT_ID}`;

                const MODAL = document.getElementById("reportCommentModal");
                const MODAL_BUTTON = MODAL.querySelectorAll(
                    'button[data-bs-dismiss="modal"]'
                );

                MODAL_BUTTON.forEach((Mbutton) => {
                    Mbutton.addEventListener("click", () => {
                        MODAL.remove();

                        // remove active comment
                        CONTAINER.classList.remove("active");

                        // Change text content to 'Report'
                        button.textContent = "Report";

                        // Change BUTTON_REPORT_ELEMENT value to null
                        BUTTON_REPORT_ELEMENT = null;
                    });
                });

                // Change form status to report: true
                FORM_STATUS.report = true;
            });
        });
    }

    /**
     * Reported comments action: Logic
     */

    const BTN_REPORTED_ACTION = document.querySelectorAll(
        ".comment[data-label-reported='true']"
    );

    if (BTN_REPORTED_ACTION) {
        BTN_REPORTED_ACTION.forEach((button) => {
            button.addEventListener("mouseenter", () => {});
        });
    }
})();
