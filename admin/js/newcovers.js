/**
 * Cover type field change hook.
 */
function type_of_cover_change_hook() {

  var posts_value = $('input[name=cover_type]:checked').val();
  if ('undefined' === posts_value) {
    return;
  }

  if ('1' === posts_value) {
    $("select[id^='shortcode-ui-post_types']").val(null).trigger('change');
    $("select[id^='shortcode-ui-post_types']").prop('disabled', 'disabled');
    $("input[name='image_rotation']").prop('checked', false);
    $("input[name='image_rotation']").prop('disabled', true);
    $("select[id^='shortcode-ui-posts']").prop('disabled', false);
  }
  else if ('2' === posts_value) {
    $("select[id^='shortcode-ui-post_types']").val(null).trigger('change');
    $("select[id^='shortcode-ui-post_types']").prop('disabled', 'disabled');
    $("input[name='image_rotation']").prop('checked', false);
    $("input[name='image_rotation']").prop('disabled', true);
    $("select[id^='shortcode-ui-posts']").val(null).trigger('change');
    $("select[id^='shortcode-ui-posts']").prop('disabled', 'disabled');
  }
  else if ('3' === posts_value) {
    $("select[id^='shortcode-ui-post_types']").prop('disabled', false);
    $("input[name='image_rotation']").prop('disabled', false);
    $("select[id^='shortcode-ui-posts']").prop('disabled', false);
  }
}

/**
 * Post types select box change hook.
 */
function post_types_change_hook() {

  var cover_type = $('input[name=cover_type]:checked').val();
  var tags = $("select[id^='shortcode-ui-tags']").val();
  var post_types = $("select[id^='shortcode-ui-post_types']").val();
  var posts = $("select[id^='shortcode-ui-posts']").val();
  if ('undefined' === cover_type) {
    return;
  }

  if ('3' === cover_type) {
    if (null !== post_types) {
      $("select[id^='shortcode-ui-tags']").prop('disabled', false);
      $("select[id^='shortcode-ui-post_types']").prop('disabled', false);
      $("input[name='image_rotation']").prop('disabled', false);
      $("select[id^='shortcode-ui-posts']").val(null).trigger('change');
      $("select[id^='shortcode-ui-posts']").prop('disabled', 'disabled');

    }
    else if (null === tags && null === posts && null === post_types) {
      $("select[id^='shortcode-ui-tags']").prop('disabled', false);
      $("select[id^='shortcode-ui-post_types']").prop('disabled', false);
      $("select[id^='shortcode-ui-posts']").prop('disabled', false);
    }
  }

}

/**
 * Post select box change hook.
 */
function posts_select_change_hook() {

  var cover_type = $('input[name=cover_type]:checked').val();
  var posts = $("select[id^='shortcode-ui-posts']").val();
  if ('undefined' === cover_type) {
    return;
  }

  if (posts !== null) {
    if ('1' === cover_type) {
      $("select[id^='shortcode-ui-tags']").val(null).trigger('change');
      $("select[id^='shortcode-ui-tags']").prop('disabled', 'disabled');
    }
    else if ('3' === cover_type) {
      $("select[id^='shortcode-ui-tags']").val(null).trigger('change');
      $("select[id^='shortcode-ui-tags']").prop('disabled', 'disabled');
      $("select[id^='shortcode-ui-post_types']").val(null).trigger('change');
      $("select[id^='shortcode-ui-post_types']").prop('disabled', 'disabled');
    }
  } else {
    if ('1' === cover_type) {
      $("select[id^='shortcode-ui-tags']").prop('disabled', false);
    }
    else if ('3' === cover_type) {
      $("select[id^='shortcode-ui-tags']").prop('disabled', false);
      $("select[id^='shortcode-ui-post_types']").prop('disabled', false);
    }
  }
}

/**
 * Tags select box change hook.
 */
function tags_change_hook() {

  var cover_type = $('input[name=cover_type]:checked').val();
  var posts = $("select[id^='shortcode-ui-posts']").val();
  var tags = $("select[id^='shortcode-ui-tags']").val();
  var post_types = $("select[id^='shortcode-ui-post_types']").val();
  if ('undefined' === cover_type) {
    return;
  }

  if ('1' === cover_type) {
    if (null !== tags || null !== post_types) {
      $("select[id^='shortcode-ui-posts']").prop('disabled', 'disabled');
      $("select[id^='shortcode-ui-posts']").val(null).trigger('change');
      $("select[id^='shortcode-ui-post_types']").prop('disabled', 'disabled');
    }
    else if (null === tags && null === posts) {
      $("select[id^='shortcode-ui-tags']").prop('disabled', false);
      $("select[id^='shortcode-ui-posts']").prop('disabled', false);
    }
  }
  else if ('2' === cover_type) {
    $("select[id^='shortcode-ui-posts']").prop('disabled', 'disabled');
    $("select[id^='shortcode-ui-posts']").val(null).trigger('change');
    $("select[id^='shortcode-ui-tags']").prop('disabled', false);
    $("select[id^='shortcode-ui-post_types']").val(null).trigger('change');
    $("select[id^='shortcode-ui-post_types']").prop('disabled', 'disabled');
  }
  else if ('3' === cover_type) {
    if (null !== tags) {
      $("select[id^='shortcode-ui-tags']").prop('disabled', false);
      $("select[id^='shortcode-ui-post_types']").prop('disabled', false);
      $("input[name='image_rotation']").prop('disabled', false);
      $("select[id^='shortcode-ui-posts']").val(null).trigger('change');
      $("select[id^='shortcode-ui-posts']").prop('disabled', 'disabled');

    }
    else if (null === tags && null === posts && null === post_types) {
      $("select[id^='shortcode-ui-posts']").prop('disabled', false);
    }
  }
}

function initialize_view_fields() {

  var cover_type = $('input[name=cover_type]:checked').val();
  var posts = $("select[id^='shortcode-ui-posts']").val();
  var tags = $("select[id^='shortcode-ui-tags']").val();
  var post_types = $("select[id^='shortcode-ui-post_types']").val();
  if ('undefined' === cover_type) {
    return;
  }

  if ('1' === cover_type) {
    $("select[id^='shortcode-ui-post_types']").prop('disabled', 'disabled');
    $("input[name='image_rotation']").prop('disabled', true);

    if (null !== tags || null !== post_types) {
      $("select[id^='shortcode-ui-posts']").prop('disabled', 'disabled');
    }
    if (null !== posts) {
      $("select[id^='shortcode-ui-tags']").prop('disabled', 'disabled');
    }
  }
  else if ('2' === cover_type) {
    $("select[id^='shortcode-ui-post_types']").prop('disabled', 'disabled');
    $("input[name='image_rotation']").prop('disabled', true);
    $("select[id^='shortcode-ui-posts']").prop('disabled', 'disabled');
  }
  else if ('3' === cover_type) {
    if (null !== tags || null !== post_types) {
      $("select[id^='shortcode-ui-posts']").prop('disabled', 'disabled');
    }
    if (null !== posts) {
      $("select[id^='shortcode-ui-tags']").prop('disabled', 'disabled');
      $("select[id^='shortcode-ui-post_types']").prop('disabled', 'disabled');
    }
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
      wp.shortcake.hooks.addAction('shortcake_newcovers.cover_type', type_of_cover_change_hook);
      wp.shortcake.hooks.addAction('shortcake_newcovers.tags', tags_change_hook);
      wp.shortcake.hooks.addAction('shortcake_newcovers.post_types', post_types_change_hook);
      wp.shortcake.hooks.addAction('shortcake_newcovers.posts', posts_select_change_hook);
    }
  }

  // Attach hooks when rendering a new newcovers block.
  wp.shortcake.hooks.addAction('shortcode-ui.render_new', attach_hooks);

  // Trigger hooks when shortcode renders an existing newcovers block.
  wp.shortcake.hooks.addAction('shortcode-ui.render_edit', function (shortcode) {
    attach_hooks();

    var requests = shortcode.get('ajax_requests');
    var $new_covers_div = $('.shortcode-ui-edit-shortcake_newcovers');
    $new_covers_div.addClass('not-clickable');
    $new_covers_div.prev().prepend('<span class="spinner is-active" id="bl_loader"></span>');
    $new_covers_div.animate({opacity: 0.5});

    if (null !== requests) {
      Promise.all(requests).then(function (values) {
        $('.shortcode-ui-edit-shortcake_newcovers').animate({opacity: 1});
        $('.shortcode-ui-edit-shortcake_newcovers').removeClass('not-clickable');
        $('#bl_loader').removeClass('is-active');
        shortcode.unset('ajax_requests');
        initialize_view_fields();
      });
    }
  });
}
