// Underline headlines on thumbnail hover.

$(document).ready(function() {
  'use strict';

  $('.article-listing').off('hover').on('hover', '.article-list-item-image',
    function() {
      $('.article-list-item-headline', $(this).parent()).toggleClass('article-hover');
    }
  );

  $('.four-column-content-symbol').hover(
    function() {
      $('h4', $(this).parent()).addClass('four-column-hover');
    }, function() {
      $('h4', $(this).parent()).removeClass('four-column-hover');
    }
  );
});
