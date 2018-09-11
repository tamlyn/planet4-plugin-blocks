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
          // Overriding ajax data function for specific shortcode attribute ('shortcake_newcovers' - 'posts')
          if ('shortcake_newcovers' === self.shortcode.get('shortcode_tag') && 'posts' === self.model.get('attr')) {
            self.ajaxData.action = 'planet4_blocks_post_field';
            return $.extend({
              type: function () {
                return $('input[name=cover_type]:checked').val()
              },
              s: params.term, // search term
              page: params.page,
              shortcode: self.shortcode.get('shortcode_tag'),
              attr: self.model.get('attr'),
              action: 'wp_ajax_planet4_blocks_post_field'
            }, self.ajaxData);
          } else {
            return $.extend({
              s: params.term, // search term
              page: params.page,
              shortcode: self.shortcode.get('shortcode_tag'),
              attr: self.model.get('attr'),
            }, self.ajaxData);
          }
        },
        processResults: function (response, params) {
          var data = response.data;
          params.page = params.page || 1;
          if (!response.success || 'undefined' === typeof response.data) {
            return {results: []};
          }
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


jQuery(function ($) {
  'use strict';

  if ('undefined' !== typeof( wp.shortcake )) {

    shortcodeUIFieldData.p4_select = {
      encode: false,
      template: "shortcode-ui-field-p4-select",
      view: "editAttributeHeading"
    };
    shortcodeUIFieldData.p4_checkbox = {
      encode: false,
      template: "shortcode-ui-field-p4-checkbox",
      view: "editAttributeHeading"
    };
    shortcodeUIFieldData.p4_radio = {
      encode: false,
      template: "shortcode-ui-field-p4-radio",
      view: "editAttributeHeading"
    };

    wp.shortcake.hooks.addAction('shortcode-ui.render_edit', function (shortcodeModel) {
      $(".shortcode-ui-attribute-heading2").parent().before('<p></p>');
    });

    wp.shortcake.hooks.addAction('shortcode-ui.render_new', function (shortcodeModel) {
      $(".shortcode-ui-attribute-heading2").parent().before('<p></p>');
    });
  }
});
