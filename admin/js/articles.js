/**
 * Disable/Enable posts select box based on post types and tags select boxes.
 */
function page_types_change_hook() {

    var posts_value = $("select[id^='shortcode-ui-post_types']").val();
    var tags = $("select[id^='shortcode-ui-tags']").val();
    if (null === posts_value && null === tags) {
        $("select[id^='shortcode-ui-posts']").prop('disabled', false);
    }
    else {
        $("select[id^='shortcode-ui-posts']").val(null).trigger('change.select2');
        $("select[id^='shortcode-ui-posts']").prop('disabled', 'disabled');
    }
}

/**
 * Disable/Enable p4 page types checkboxes based on posts select box value.
 */
function posts_select_change_hook() {

    var posts_value = $("select[id^='shortcode-ui-posts']").val();
    if (null === posts_value) {
        $("select[id^='shortcode-ui-tags']").prop('disabled', false);
        $("select[id^='shortcode-ui-post_types']").prop('disabled', false);
        $("input[name^='ignore_categories']").prop('disabled', false);
    }
    else {
        $("select[id^='shortcode-ui-post_types']").val(null).trigger('change.select2');
        $("select[id^='shortcode-ui-post_types']").prop('disabled', 'disabled');
        $("select[id^='shortcode-ui-tags']").val(null).trigger('change.select2');
        $("select[id^='shortcode-ui-tags']").prop('disabled', 'disabled');
        $("input[name^='ignore_categories']").prop('disabled', 'disabled');
    }
}


var hooks_defined = false;
if ('undefined' !== typeof(wp.shortcake)) {

    /**
     * Attach shortcake hooks for articles block fields.
     */
    function attach_hooks() {

        if (!hooks_defined) {
            hooks_defined = true;

            wp.shortcake.hooks.addAction('shortcake_articles.posts', posts_select_change_hook);
            wp.shortcake.hooks.addAction('shortcake_articles.post_types', page_types_change_hook);
            wp.shortcake.hooks.addAction('shortcake_articles.tags', page_types_change_hook);
        }
    }

    // Attach hooks when rendering a new or pre-existing articles block.
    wp.shortcake.hooks.addAction('shortcode-ui.render_new', attach_hooks);

    // Trigger hooks when shortcode renders an exisiting articles block.
    wp.shortcake.hooks.addAction('shortcode-ui.render_edit', function () {
        attach_hooks();
        page_types_change_hook();
        posts_select_change_hook();
    });
}
