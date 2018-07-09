/**
 * Disable/Enable posts select box based on p4 page types checkboxes.
 *
 * @param changed
 * @param collection
 * @param shortcode
 */
function page_types_change_hook(changed, collection, shortcode) {

    var posts_value = $("input[name^='p4_page_type']:checked, input[name^='ignore_categories']:checked").length;
    if (0 === posts_value) {
        $("select[id^='shortcode-ui-posts']").prop('disabled', false);
    }
    else {
        $("select[id^='shortcode-ui-posts']").prop('disabled', 'disabled');
        $("select[id^='shortcode-ui-posts']").val(null).trigger('change.select2');
    }
}

/**
 * Disable/Enable p4 page types checkboxes based on posts select box value.
 *
 * @param changed
 * @param collection
 * @param shortcode
 */
function posts_select_change_hook(changed, collection, shortcode) {

    var posts_value = $("select[id^='shortcode-ui-posts']").val();
    if (null === posts_value) {
        $("input[name^='p4_page_type']").prop('disabled', false);
        $("input[name^='ignore_categories']").prop('disabled', false);
        $("input[name^='article_count']").prop('disabled', false);
    }
    else {
        $("input[name^='p4_page_type']").prop('checked', false);
        $("input[name^='ignore_categories']").prop('checked', false);
        $("input[name^='p4_page_type']").prop('disabled', 'disabled');
        $("input[name^='ignore_categories']").prop('disabled', 'disabled');
        $("input[name^='article_count']").prop('disabled', 'disabled');
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

            $("input[name^='p4_page_type']").each(function (index) {
                var page_type_name = $(this).attr('name');
                wp.shortcake.hooks.addAction('shortcake_articles.' + page_type_name, page_types_change_hook);
            });
            wp.shortcake.hooks.addAction('shortcake_articles.ignore_categories', page_types_change_hook);
        }
    }

    // Attach hooks when rendering a new or pre-existing articles block.
    wp.shortcake.hooks.addAction('shortcode-ui.render_new', attach_hooks);
    wp.shortcake.hooks.addAction('shortcode-ui.render_edit', attach_hooks);

    // Trigger hooks when shortcode renders an exisiting articles block.
    wp.shortcake.hooks.addAction('shortcode-ui.render_edit', function () {
        page_types_change_hook();
        posts_select_change_hook();
    });
}
