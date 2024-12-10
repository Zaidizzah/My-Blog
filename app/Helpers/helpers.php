<?php

if (!function_exists('highlight')) {

    /**
     * Highlight the search term
     * 
     * @param string $text
     * @param string $search
     * @return string
     */
    function highlight(string $text, ?string $search): string
    {
        if (empty($search)) {
            return $text;
        }

        return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark>$1</mark>', $text);
    }
}

if (!function_exists('route_check')) {

    /** 
     * Check if route is active
     * 
     * @param string|array $route
     * @return bool
     */
    function route_check(string|array ...$route): bool
    {
        return request()->routeIs(...$route);
    }
}

if (!function_exists('set_active')) {

    /**
     * Set active class for current route
     * 
     * @param string|array $route
     * @return string
     */
    function set_active(string|array ...$route): string
    {
        return request()->routeIs(...$route) ? 'active' : '';
    }
}

if (!function_exists('is_active_query')) {

    /**
     * Set active class for current route
     * 
     * @param string|array $route
     * @return string
     */
    function is_active_query(string|array ...$route): string
    {
        return request()->fullUrlIs(...$route) ? 'active' : '';
    }
}

if (!function_exists('breadcrumb_builder')) {

    /**
     * Generate breadcrumb
     * 
     * @param array $breadcrumb
     * @return string
     */
    function breadcrumb_builder(array $breadcrumb, string $subtitle): string
    {
        $html = '
            <section class="breadcrumb-section bg-light my-3" aria-label="breadcrumb">  
                <h3 class="h3">' . $subtitle . '</h3>
                <ol class="breadcrumb">
        ';
        foreach ($breadcrumb as $key => $value) {
            if (is_string($key) && is_string($value)) {
                $html .= '
                    <li class="breadcrumb-item"><a class="no-permalink text-black-50" href="' . $value . '">' . $key . '</a></li>
                ';
            } elseif (is_string($key) && is_array($value)) {
                foreach ($value as $file) {
                    $html .= '
                    <li class="breadcrumb-item"><a class="no-permalink text-black-50" href="' . $file . '">' . $key . '</a></li>
                    ';
                }
            } elseif (is_numeric($key) && is_string($value)) {
                $html .= '
                    <li class="breadcrumb-item active"><a class="no-permalink text-black-50" href="javascript:void(0)" aria-current="page">' . $value . '</a></li>
                ';
            }
        }
        $html .= '
                </ol>
            </section>
        ';
        return $html;
    }
}

if (!function_exists('toast')) {

    /**
     * Display a toast message
     * 
     * @param string $message
     * @param string $type
     * @return string
     */
    function toast(string $message, string $type = 'success'): string
    {
        return "<div class=\"toast $type show fade\" role=\"alert\" aria-live=\"assertive\" aria-atomic=\"true\" data-bs-autohide=\"true\" data-bs-delay=\"15000\">
            <div class=\"toast-header\">
                <strong class=\"me-auto\">" . ucfirst($type) . "!</strong>
                <small class=\"text-muted\">Just now</small>
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"toast\" aria-label=\"Close\"></button>
            </div>
            <div class=\"toast-body\">
                <p class=\"m-0 lh-2\">$message</p>
            </div>
        </div>";
    }
}

if (!function_exists('generate_tags')) {

    /**
     * Generate HTML tags for scripts or links based on the provided configuration.
     *
     * @param string $type The type of tags to generate ('link' for CSS, 'script' for JS).
     * @param array $tags An array of configurations for each tag.
     *                     Each configuration should be an associative array with
     *                     keys corresponding to the attributes of the tag.
     *                     For 'link', valid keys are 'href', 'rel', 'type', 'media',
     *                     'base_path', 'integrity', 'crossorigin'.
     *                     For 'script', valid keys are 'src', 'async', 'defer', 'type',
     *                     'integrity', 'crossorigin', 'base_path', 'inline'.
     *
     * @return string Returns a string of concatenated HTML tags.
     *                Returns an empty string if no valid tags are provided.
     */
    function generate_tags(string $type, ?array $tags): string
    {
        // Validate input
        if (empty($tags)) {
            return '';
        }

        // Default configuration for link (CSS)
        $linkDefaults = [
            'href' => null,       // Source CSS
            'rel' => 'stylesheet', // Default rel for stylesheet
            'type' => 'text/css', // CSS type
            'media' => 'all',     // Target media
            'base_path' => '',    // Base path for local files
            'integrity' => null,  // Resource integrity
            'crossorigin' => null, // Cross-origin settings
        ];

        // Default configuration for script
        $scriptDefaults = [
            'src' => null,        // Source script
            'async' => false,     // Async loading
            'defer' => false,     // Defer loading
            'type' => 'text/javascript', // Script type
            'integrity' => null,  // Script integrity
            'crossorigin' => null, // Cross-origin settings
            'base_path' => '',    // Base path for local scripts
            'inline' => null,     // Inline content
        ];

        // Select default configuration based on type
        $defaultConfig = $type === 'link' ? $linkDefaults : $scriptDefaults;

        // Container for generated tags
        $generatedTags = [];

        // Process each tag configuration
        foreach ($tags as $tagConfig) {
            // Merge default config with provided configuration
            $config = array_merge($defaultConfig, $tagConfig);

            // Process link tags
            if ($type === 'link') {
                // Validate href source
                if (empty($config['href'])) {
                    continue;
                }

                // Determine URL
                $url = $config['href'];
                if (!filter_var($url, FILTER_VALIDATE_URL)) {
                    // If not an absolute URL, append base path
                    $url = rtrim($config['base_path'], '/') . '/' . ltrim($url, '/');
                }

                // Prepare additional attributes
                $additionalAttributes = [];

                if ($config['integrity']) {
                    $additionalAttributes[] = sprintf('integrity="%s"', htmlspecialchars($config['integrity']));
                }

                if ($config['crossorigin']) {
                    $additionalAttributes[] = sprintf('crossorigin="%s"', htmlspecialchars($config['crossorigin']));
                }

                // Create link tag
                $generatedTags[] = sprintf(
                    '<link rel="%s" type="%s" href="%s" media="%s"%s>',
                    htmlspecialchars($config['rel']),
                    htmlspecialchars($config['type']),
                    htmlspecialchars($url),
                    htmlspecialchars($config['media']),
                    $additionalAttributes ? ' ' . implode(' ', $additionalAttributes) : ''
                );
            }
            // Process script tags
            else {
                // Prioritize inline script if present
                if (isset($config['inline']) && $config['inline'] !== null) {
                    $generatedTags[] = sprintf(
                        '<script type="%s">%s</script>',
                        htmlspecialchars($config['type']),
                        $config['inline']
                    );
                    continue;
                }

                // Validate script source
                if (empty($config['src'])) {
                    continue;
                }

                // Determine URL
                $url = $config['src'];
                if (!filter_var($url, FILTER_VALIDATE_URL)) {
                    // If not an absolute URL, append base path
                    $url = rtrim($config['base_path'], '/') . '/' . ltrim($url, '/');
                }

                // Prepare additional attributes
                $additionalAttributes = [];

                if ($config['async']) {
                    $additionalAttributes[] = 'async';
                }

                if ($config['defer']) {
                    $additionalAttributes[] = 'defer';
                }

                if ($config['integrity']) {
                    $additionalAttributes[] = sprintf('integrity="%s"', htmlspecialchars($config['integrity']));
                }

                if ($config['crossorigin']) {
                    $additionalAttributes[] = sprintf('crossorigin="%s"', htmlspecialchars($config['crossorigin']));
                }

                // Create script tag
                $generatedTags[] = sprintf(
                    '<script type="%s" src="%s"%s></script>',
                    htmlspecialchars($config['type']),
                    htmlspecialchars($url),
                    $additionalAttributes ? ' ' . implode(' ', $additionalAttributes) : ''
                );
            }
        }

        // Return concatenated tags
        return implode("\n", $generatedTags);
    }
}

if (!function_exists('generate_comments_section') && !function_exists('render_root_comment')) {

    /**
     * Generate comments section.
     *
     * @param \Illuminate\Support\Collection $comments
     * @return string
     */
    function generate_comments_section(object $comments)
    {
        if ($comments->isEmpty()) {
            return '<div class="no-comments text-center py-4">No comments yet.</div>';
        }

        // Group comments by parent_id
        $commentGroups = $comments->groupBy('parent_id');

        // Generate HTML untuk root comments
        $rootComments = $commentGroups->get(null, collect());
        $commentsHtml = '';
        foreach ($rootComments as $comment) {
            $commentsHtml .= render_comment_with_replies($comment, $commentGroups);
        }

        return '<div class="comments-section">' . $commentsHtml . '</div>';
    }

    /**
     * Render a single comment with its replies recursively.
     *
     * @param object $comment
     * @param \Illuminate\Support\Collection $commentGroups
     * @return string
     */
    function render_comment_with_replies($comment, $commentGroups, $parentComment = null)
    {
        $authorName = $comment->author->name;
        $postId = $comment->post_id ?? null;
        $avatarUrl = $comment->author->image ?? "https://dummyimage.com/680x480/3e3e3e/fff&text={$authorName}";
        $timeAgo = $comment->created_at->diffForHumans();

        // Optimized "Reply to"
        $replyingTo = $parentComment
            ? "<span class=\"replyng-to text-primary\">@{$parentComment->author->name}</span>"
            : '';

        // Render komentar utama
        $html = <<<HTML
            <div class="comments-wrapper">
                <div class="comment" data-post-id="{$postId}" data-comment-id="{$comment->id}" data-comment-writter="{$comment->author->name}" title="Comment by {$authorName}">
                    <img class="comment-avatar img-thumbnail" 
                        src="{$avatarUrl}" 
                        title="User Avatar: {$authorName}" 
                        data-img-preview
                        data-img-preview-title="User Avatar: {$authorName}"
                        loading="lazy" 
                        onerror="this.onerror=null;this.src='/images/no-image-available.jpg';"
                        alt="User Avatar">
                    <div class="comment-content">
                        <div class="comment-header">
                            <span class="comment-author">{$authorName} {$replyingTo} . 
                                <a href="javascript:void(0);" 
                                class="comment-reply small no-permalink" 
                                title="Reply to this comment">Reply</a>
                            </span>
                            <span class="comment-date">{$timeAgo}</span>
                        </div>
                        <p class="comment-text">{$comment->description}</p>
                    </div>
                </div>
            HTML;

        // Cek jika ada nested comments
        $nestedComments = $commentGroups->get($comment->id, collect());
        if ($nestedComments->isNotEmpty()) {
            $html .= '<div class="nested-comments">';
            foreach ($nestedComments as $nestedComment) {
                $html .= render_comment_with_replies($nestedComment, $commentGroups, $comment);
            }
            $html .= '</div>';
        }

        $html .= '</div>'; // Tutup wrapper utama
        return $html;
    }
}

if (!function_exists('generate_month_name')) {

    /**
     * Generate month name.
     *
     * @param string|int $month
     * @return string
     */
    function generate_month_name(string|int $month)
    {
        if (empty($month)) {
            return '';
        }

        return date('F', mktime(0, 0, 0, $month, 1));
    }
}
