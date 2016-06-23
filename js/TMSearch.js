/*jslint browser, this, multivar */
/*global jQuery, $, window */
/*property
    TMSearch, _resize, appendTo, attr, container, dataType, extend, form, get,
    handler, hasClass, height, html, input, length, liveSearch, liveout, on,
    out, ready, replace, s, scope, search, src, val
*/
(function ($) {
    'use strict';
    $.TMSearch = function (o) {
        var defaults, ifr, options, s;
        var $form, $input, $liveout, $out;
        defaults = {
            container: '.stuck_container',
            form: '.search-form',
            input: '.search-form_input',
            liveout: '.search-form_liveout',
            out: '#search-results',
            scope: 1,
            handler: 'search/'
        };
        options = $.extend({}, defaults, o);
        $form = $(options.form);
        $input = $(options.input, $form);
        $liveout = $(options.liveout, $form);
        $out = $(options.out);
        // Live Search
        if ($('html').hasClass('desktop')) {
            $input.on("keyup", function () {
                $.get(
                    options.handler,
                    {
                        s: $(this).val(),
                        liveSearch: "true",
                        dataType: "html"
                    },
                    function (data) {
                        $liveout.html(data);
                    }
                );
            });
            $input.on('focusout', function () {
                setTimeout(
                    function () {
                        $liveout.html('');
                    },
                    300
                );
            });
        }
        // Frame Search
        if ($out.length > 0) {
            $out.height(0);
            s = location.search.replace(
                /^\?.*s=([^&]+)/,
                '$1'
            );
            ifr = $(
                '<iframe width="100%" height="100%" frameborder="0" marginheight="0" marginwidth="0"' +
                ' allowTransparency="true"></iframe>'
            );
            if ($out.length) {
                ifr.attr({src: options.handler + '?s=' + s}).appendTo($out);
                $input.val(decodeURI(s));
            }
            window._resize = function (h) {
                $out.height(h);
            };
        }
    };
}(jQuery));

$(document).ready(function () {
    'use strict';
    $.TMSearch();
});
