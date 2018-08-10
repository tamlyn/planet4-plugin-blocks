// Override shortcake's editAttributeSelect2Field backbone view to manipulate select2 instance.
// Change render function of that view.
if ('undefined' !== sui.views) {

    /**
     * Abstract field for all ajax Select2-powered field views
     *
     * Adds useful helpers that are shared between all of the fields which use
     * Select2 as their UI.
     *
     */

    sui.views.editAttributeSelect2Field.prototype.render = function () {
        var self = this,
            defaults = {multiple: false};

        for (var arg in defaults) {
            if (!this.model.get(arg)) {
                this.model.set(arg, defaults[arg]);
            }
        }

        var data = this.model.toJSON();
        data.id = 'shortcode-ui-' + this.model.get('attr') + '-' + this.model.cid;

        this.$el.html(this.template(data));

        var $field = this.$el.find(this.selector);

        this.preselect($field);

        var select2_options = this.model.get('meta').select2_options;

        var default_options = {
            multiple: this.model.get('multiple'),
            dropdownParent: this.$el,
            allowClear: true,

            ajax: {
                url: ajaxurl,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return $.extend({
                        s: params.term, // search term
                        page: params.page,
                        shortcode: self.shortcode.get('shortcode_tag'),
                        attr: self.model.get('attr')
                    }, self.ajaxData);
                },
                processResults: function (response, params) {
                    if (!response.success || 'undefined' === typeof response.data) {
                        return {results: []};
                    }
                    var data = response.data;
                    params.page = params.page || 1;
                    console.log(data);
                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * data.items_per_page) < data.found_items
                        }
                    };
                },
                cache: true
            },

            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: 0,
            templateResult: this.templateResult,
            templateSelection: this.templateSelection,
        };

        var soptions = Object.assign({}, default_options, select2_options);
        console.log(soptions);


        var that = this;
        _.defer(function () {
            var $fieldSelect2 = $field[shortcodeUIData.select2_handle](soptions);
            if (that.model.get('multiple')) {
                that.sortable($field);
            }
        }, that, $field);

        return this;
    };
}


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
