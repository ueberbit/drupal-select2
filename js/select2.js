/**
 * @file
 * Select2 integration
 *
 */
(function ($) {

  Drupal.behaviors.select2 = {
    attach: function (context, drupalSettings) {
      // Taxonomy tagging widget.
      $('[data-select2-field-name]').once().each(function () {
        var fieldName = $(this).data('select2-field-name'),
          settings = drupalSettings.select2[fieldName],
          url = $(this).data('autocomplete-path');

        var opts = {
          tokenSeparators: settings.token_separator,
          multiple: settings.multiple
        };
        // Do not add tags to settings, as soon as it is added, select2 enables it
        // ignoring the value
        if (settings.tags) {
          opts.tags = settings.tags;
        }

        $(this).select2($.extend(opts, {
          width: 'element',
          createSearchChoice: function (term, data) {
            if ($(data).filter(function () {
                return this.text.localeCompare(term) === 0;
              }).length === 0) {
              return {id: term, text: term};
            }
          },
          ajax: {
            url: url,
            data: function (term, page) {
              return {
                q: term
              };
            },
            results: function (data, page) {
              // map keys from taxonomy to select2
              var res = [];
              $.each(data, function (key, val) {
                res.push({'id': val.value, 'text': settings.display_id ? val.value : val.label});
              });
              var more = (page * 10) < data.length; // whether or not there are more results available
              // notice we return the value of more so Select2 knows if more results can be loaded
              return {results: res, more: more};
            }
          },
          initSelection: function (element, callback) {
            var data = [];
            // @todo Needs testing, this does not seem to work anymore.
            $(element.val().split(",")).each(function () {
              var value = this.trim(),
                match = value.match(/^(.*) \((\d+)\)$/);
              if (!settings.display_id && match) {
                data.push({id: value, text: match[1]});
              }
              else {
                data.push({id: value, text: value});
              }
            });
            if (!settings.multiple) {
              data = data.shift();
            }
            callback(data);
          }
        }));
      });

      // @todo merge both widget configurations; the only difference should be
      // that taxonomy supports free-tagging, while the views-autocomplete one
      // does not. This could be passed in as a config option on the element...
      $('[data-select2-autocomplete-widget]').once().each(function () {
        var $items_per_page = 10;
        var url = $(this).data('autocomplete-path');
        var tokenSeparator = $(this).data('select2-token-separator');
        var tokenSeparators = (tokenSeparator) ? [tokenSeparator] : [','];
        $(this).select2({
          tokenSeparators: tokenSeparators,
          multiple: true,
          width: 'resolve',
          // we do not want to escape markup since we are displaying html
          escapeMarkup: function (m) {
            return m;
          },
          initSelection: function (element, callback) {
            var data = $(element).data('default-value');
            callback(data);
          },
          formatResultCssClass: function (data) {
            return data.css;
          },
          formatSelectionCssClass: function (data, container) {
            return data.css;
          },
          ajax: {
            url: url,
            data: function (term, page) {
              return {
                q: term,
                page: page
              };
            },
            results: function (data, page) {
              // map keys from taxonomy to select2
              var res = [];
              $.each(data, function (i, val) {
                if (i >= $items_per_page) {
                  // We use the eleventh entry to determine whether there's more
                  // data available, therefore ignore anything beyond to avoid
                  // duplicates.
                  return false;
                }
                res.push({'id': val.value, 'text': val.label, 'css': val.css});
              });
              var more = $items_per_page < data.length;
              return {results: res, more: more};
            }
          }
        });
      });
    }
  };

})(jQuery);
