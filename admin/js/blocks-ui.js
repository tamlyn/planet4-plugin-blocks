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

		$.get(blocks.url + "/admin/templates/submenu.tpl.php", function (data) {
			$("#wpwrap").append(data);
		});
	}
});