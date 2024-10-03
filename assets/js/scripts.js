!function(e) {
    "use strict";
    e("[data-bg-img]").css("background-image", function() {
        return 'url("' + e(this).data("bg-img") + '")'
    }).removeAttr("data-bg-img").addClass("bg-img"),
    jQuery("img.svg").each(function() {
        var e = jQuery(this)
          , a = e.attr("id")
          , t = e.attr("class")
          , n = e.attr("src");
        jQuery.get(n, function(n) {
            var i = jQuery(n).find("svg");
            void 0 !== a && (i = i.attr("id", a)),
            void 0 !== t && (i = i.attr("class", t + " replaced-svg")),
            !(i = i.removeAttr("xmlns:a")).attr("viewBox") && i.attr("height") && i.attr("width") && i.attr("viewBox", "0 0 " + i.attr("height") + " " + i.attr("width")),
            e.replaceWith(i)
        }, "xml")
    });
    var a = e(".mobile-nav-menu .search-toggle-open")
      , t = e(".mobile-nav-menu .search-toggle-close")
      , n = e(".nav-search-box");
    function i() {
        e(window).scrollTop() > 0 ? e(".header-fixed").addClass("is-sticky fadeInDown animated") : e(".header-fixed").removeClass("is-sticky fadeInDown animated")
    }
    a.on("click", function() {
        n.addClass("show"),
        e(this).addClass("hide"),
        t.removeClass("hide")
    }),
    t.on("click", function() {
        n.removeClass("show"),
        e(this).addClass("hide"),
        a.removeClass("hide")
    }),
    e(window).on("scroll", function() {
        i()
    }),
    i(),
    e(".mobile-nav-menu .nav-menu-toggle").on("click", function() {
        e(".nav-menu").toggleClass("show")
    }),
    e(".nav-menu .menu-item-has-children a").on("click", function(a) {
        e(window).width() <= 991 && e(this).siblings(".sub-menu").addClass("show")
    });
    function o() {
        e(".nav-menu .menu-item-has-children .sub-menu").each(function() {
            e(window).width() > 991 && e(this).offset().left + e(this).width() > e(window).width() && e(this).css({
                left: "auto",
                right: "100%"
            })
        })
    }
    e(".nav-menu .menu-item-has-children a").each(function() {
        e(this).siblings(".sub-menu").prepend('<li class="sub-menu-close"> <i class="fa fa-long-arrow-left"></i> ' + e(this).siblings(".sub-menu").parent().children("a").text() + "</li>")
    }),
    e(".nav-menu .menu-item-has-children .sub-menu .sub-menu-close").on("click", function() {
        e(this).parent(".sub-menu").removeClass("show")
    }),
    o(),
    e(window).resize(o);
    var s = [];
    e(".banner-slide .banner-slide-text h1").each(function() {
        s.push(e(this).text()),
        e(".banner-slider-dots").prepend('<div class="dots-count"></div>')
    }),
    e(".banner-slider-dots .dots-count").each(function(a) {
        e(this).html(s[a])
    }),
    e(".banner-slider-dots .dots-count").append('<span class="process-bar"></span> <span class="process-bar-active"></span>');
    var r = e(".banner-slider")
      , l = e(".banner-slider-dots")
      , d = !0;
    r.owlCarousel({
        items: 1,
        slideSpeed: 2e3,
        autoplay: !0,
        loop: !0,
        responsiveRefreshRate: 200,
        animateIn: "fadeIn",
        animateOut: "fadeOut",
        margin: 50
    }).on("changed.owl.carousel", function(e) {
        var a = e.item.count - 1
          , t = Math.round(e.item.index - e.item.count / 2 - .5);
        t < 0 && (t = a);
        t > a && (t = 0);
        l.find(".owl-item").removeClass("current").eq(t).addClass("current");
        var n = l.find(".owl-item.active").length - 1
          , i = l.find(".owl-item.active").first().index()
          , o = l.find(".owl-item.active").last().index();
        t > o && l.data("owl.carousel").to(t, 100, !0);
        t < i && l.data("owl.carousel").to(t - n, 100, !0)
    }),
    l.on("initialized.owl.carousel", function() {
        setTimeout(function() {
            l.find(".owl-item").eq(0).addClass("current")
        }, 100)
    }).owlCarousel({
        items: 3,
        dots: !0,
        nav: !0,
        smartSpeed: 200,
        slideSpeed: 500,
        slideBy: 3,
        responsiveRefreshRate: 100,
        margin: 110
    }).on("changed.owl.carousel", function(e) {
        if (d) {
            var a = e.item.index;
            r.data("owl.carousel").to(a, 100, !0)
        }
    }),
    l.on("click", ".owl-item", function(a) {
        a.preventDefault();
        var t = e(this).index();
        r.data("owl.carousel").to(t, 300, !0)
    }),
    e(".vid-play-btn").magnificPopup({
        type: "iframe",
        iframe: {
            markup: '<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe></div>',
            patterns: {
                youtube: {
                    index: "youtube.com/",
                    id: "v=",
                    src: "https://www.youtube.com/embed/%id%?autoplay=1"
                },
                vimeo: {
                    index: "vimeo.com/",
                    id: "/",
                    src: "https://player.vimeo.com/video/%id%?autoplay=1"
                },
                gmaps: {
                    index: "https://maps.google.",
                    src: "%id%&output=embed"
                }
            },
            srcAction: "iframe_src"
        }
    });
    var c = function(e, a) {
        return void 0 === e ? a : e
    };
    e(".owl-carousel").each(function() {
        var a = e(this);
        a.owlCarousel({
            items: c(a.data("owl-items"), 1),
            margin: c(a.data("owl-margin"), 0),
            loop: c(a.data("owl-loop"), !0),
            smartSpeed: 1e3,
            autoplay: c(a.data("owl-autoplay"), !1),
            autoplayTimeout: c(a.data("owl-speed"), 8e3),
            center: c(a.data("owl-center"), !1),
            animateIn: c(a.data("owl-animate-in"), !1),
            animateOut: c(a.data("owl-animate-out"), !1),
            nav: c(a.data("owl-nav"), !1),
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            dots: c(a.data("owl-dots"), !1),
            responsive: c(a.data("owl-responsive"), {}),
            mouseDrag: c(a.data("owl-mouse-drag"), !1)
        })
    }),
    e(window).on("load", function() {
        e(".preloader").fadeOut(2e3)
    });
    var u = e(".back-to-top");
    if (u.length) {
        var m = function() {
            e(window).scrollTop() > 400 ? u.addClass("show") : u.removeClass("show")
        };
        m(),
        e(window).on("scroll", function() {
            m()
        }),
        u.on("click", function(a) {
            a.preventDefault(),
            e("html,body").animate({
                scrollTop: 0
            }, 700)
        })
    }
    e(".my-contact-form-cover").on("submit", "form", function(a) {
        a.preventDefault();
        var t = e(this);
        e.post(t.attr("action"), t.serialize(), function(a) {
            a = e.parseJSON(a),
            t.parent(".my-contact-form-cover").find(".form-response").html("<span>" + a[1] + "</span>")
        })
    })
}(jQuery);
