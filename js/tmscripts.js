function tm_include(scriptUrl) {
    document.write('<script src="' + scriptUrl + '"></sc' + 'ript>');
}

tm_include('%BASE_PATH%sysjs/jquery.cookie');
tm_include('%BASE_PATH%sysjs/jquery.easing');
tm_include('%BASE_PATH%sysjs/superfish');
tm_include('%BASE_PATH%sysjs/jquery.rd-navbar');

function tm_isIE() {
    var myNav = navigator.userAgent.toLowerCase();
    return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
};

(function ($) {
    var o = $('html');
    if (o.hasClass('desktop')) {
        tm_include('%BASE_PATH%sysjs/tmstickup');
        $(document).ready(function () {
            $('#stuck_container').TMStickUp({})
        });
    }
})(jQuery);

/* ToTop
 ========================================================*/
(function ($) {
    var o = $('html');
    if (o.hasClass('desktop')) {
        tm_include('%BASE_PATH%sysjs/jquery.ui.totop');
        $(document).ready(function () {
            $().UItoTop({
                easingType: 'easeOutQuart',
                containerClass: 'toTop fa fa-angle-up'
            });
        });
    }
})(jQuery);


/* Responsive Tabs
 ========================================================*/
;
(function ($) {
    var o = $('.resp-tabs');
    if (o.length > 0) {
        tm_include('%BASE_PATH%sysjs/jquery.responsive.tabs');

        $(document).ready(function () {
            o.easyResponsiveTabs();
        });
    }
})(jQuery);

/* Google Map
 ========================================================*/
;
(function ($) {
    var o = document.getElementById("google-map");
    if (o) {
        tm_include('//maps.google.com/maps/api/js?sensor=false');
        tm_include('%BASE_PATH%sysjs/jquery.rd-google-map');

        $(document).ready(function () {
            var o = $('#google-map');
            if (o.length > 0) {
                o.googleMap({styles: []});
            }
        });
    }
})
(jQuery);

/* Owl Carousel
 ========================================================*/
;
(function ($) {
    var o = $('.owl-carousel');
    if (o.length > 0) {
        tm_include('%BASE_PATH%sysjs/owl.carousel');
        $(document).ready(function () {
            o.owlCarousel({
                margin: 30,
                smartSpeed: 450,
                loop: true,
                dots: false,
                dotsEach: 1,
                nav: true,
                navClass: ['owl-prev fa fa-angle-left', 'owl-next fa fa-angle-right'],
                responsive: {
                    0: {items: 1},
                    768: {items: 1},
                    980: {items: 1}
                }
            });
        });
    }
})(jQuery);


/* WOW
 ========================================================*/
;
(function ($) {
    var o = $('html');
    if ((navigator.userAgent.toLowerCase().indexOf('msie') == -1 ) || (tm_isIE() && tm_isIE() > 9)) {
        if (o.hasClass('desktop')) {
            tm_include('%BASE_PATH%sysjs/wow');
            $(document).ready(function () {
                new WOW().init();
            });
        }
    }
})(jQuery);


/* Orientation tablet fix
 ========================================================*/
$(function () {
    // IPad/IPhone
    var viewportmeta = document.querySelector && document.querySelector('meta[name="viewport"]'),
        ua = navigator.userAgent,

        gestureStart = function () {
            viewportmeta.content = "width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0";
        },

        scaleFix = function () {
            if (viewportmeta && /iPhone|iPad/.test(ua) && !/Opera Mini/.test(ua)) {
                viewportmeta.content = "width=device-width, minimum-scale=1.0, maximum-scale=1.0";
                document.addEventListener("gesturestart", gestureStart, false);
            }
        };

    scaleFix();
    // Menu Android
    if (window.orientation != undefined) {
        var regM = /ipod|ipad|iphone/gi,
            result = ua.match(regM);
        if (!result) {
            $('.sf-menus li').each(function () {
                if ($(">ul", this)[0]) {
                    $(">a", this).toggle(
                        function () {
                            return false;
                        },
                        function () {
                            window.location.href = $(this).attr("href");
                        }
                    );
                }
            })
        }
    }
});
var ua = navigator.userAgent.toLocaleLowerCase(),
    regV = /ipod|ipad|iphone/gi,
    result = ua.match(regV),
    userScale = "";
if (!result) {
    userScale = ",user-scalable=0"
}
document.write('<meta name="viewport" content="width=device-width,initial-scale=1.0' + userScale + '">');

/* Camera
 ========================================================*/
;(function ($) {
    var o = $('#camera');
    if (o.length > 0) {
        if (!(tm_isIE() && (tm_isIE() > 9))) {
            tm_include('%BASE_PATH%sysjs/jquery.mobile.customized');
        }

        tm_include('%BASE_PATH%sysjs/camera');

        $(document).ready(function () {
            o.camera({
                autoAdvance: true,
                height: '35.375%',
                minHeight: '300px',
                pagination: true,
                thumbnails: false,
                playPause: false,
                hover: false,
                loader: 'none',
                navigation: false,
                navigationHover: false,
                mobileNavHover: false,
                fx: 'simpleFade'
            })
        });
    }
})(jQuery);

/* Search.js
 ========================================================*/
;
(function ($) {
    var o = $('.search-form');
    if (o.length > 0) {
        tm_include('%BASE_PATH%sysjs/TMSearch');
    }
})(jQuery);


/* Mailform
 =============================================*/
;
(function ($) {
    tm_include('%BASE_PATH%sysjs/jquery.form');
    tm_include('%BASE_PATH%sysjs/jquery.rd-mailform');
})(jQuery);


/* FancyBox
 ========================================================*/
;
(function ($) {
    var o = $('.thumb');
    if (o.length > 0) {
        tm_include('%BASE_PATH%sysjs/jquery.fancybox');
        tm_include('%BASE_PATH%sysjs/jquery.fancybox-media');
        tm_include('%BASE_PATH%sysjs/jquery.fancybox-buttons');
        $(document).ready(function () {
            o.fancybox();
        });
    }
})(jQuery);



/* JQuery UI Accordion
 ========================================================*/
;
(function ($) {
    var o = $('.accordion');
    if (o.length > 0) {

        $(document).ready(function () {
            o.accordion({
                active: false,
                header: '.accordion_header',
                heightStyle: 'content',
                collapsible: true
            });
        });
    }
})(jQuery);


/* Parallax
 =============================================*/
;
(function ($) {
     tm_include('%BASE_PATH%sysjs/jquery.rd-parallax');
})(jQuery);
