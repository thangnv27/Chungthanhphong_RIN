var PPO = PPO || {};
PPO.slider = function () {
    var settings = {
        $wWidth: $(window).width(),
        $wHeight: $(window).height(),
        $btHide: $("#menu").find("span.close"),
        $main: $("#main"),
        $mainLeft: $(".main-left"),
        $contentarea: $('.post'),
        $contentToggle: $('#page-content-toggle'),
        $carousel: $("#slide-carousel"),
        $carouselCloned: null,
        carouselOptions: {
            width: null,
            responsive: false,
            scroll: {
                fx: 'scroll',
                easing: "swing",
                duration: 1000,
                pauseOnHover: true
            },
            swipe: {
                onTouch: true,
                onMouse: true,
                easing: "swing"
            },
            prev: {
                button: '#prevslide'
            },
            next: {
                button: '#nextslide'
            }
        }
    };
            
    function a(){
        if(settings.$wWidth > 614){
            settings.$carousel.find("img.no-width").height(settings.$wHeight);
        } else {
            settings.carouselOptions.width = settings.$wWidth;
            settings.$carousel.find("img.no-width").removeClass('no-width').css({
                width: settings.$wWidth,
                'margin-left': '-1px'
            });
        }
        if(is_product){
            settings.carouselOptions.responsive = true;
        }
        settings.$carouselCloned = settings.$carousel.clone();
        settings.$carouselCloned.attr('id', 'slide-carousel-tmp');
        settings.$mainLeft.append(settings.$carouselCloned);
        settings.$carousel.carouFredSel(settings.carouselOptions);
        c();d();
        if(settings.$wWidth <= 614){
            setTimeout(function (){
                settings.$mainLeft.find(".caroufredsel_wrapper").height(settings.$carousel.find(".slide-item").height());
                settings.$carousel.find(".slide-item").each(function (){
                    $(this).css('top', $(this).height() - settings.$carousel.height());
                });
            }, 1000);
        }
    }
    function b(a){
        if(is_product){
            settings.carouselOptions.responsive = true;
        }
        settings.$mainLeft.find(".caroufredsel_wrapper").remove();
        var $clone = settings.$carouselCloned.clone().attr('id', 'slide-carousel');
        settings.$mainLeft.prepend($clone);
        $clone.carouFredSel(settings.carouselOptions);
        d();
        if(settings.$wWidth <= 614){
            setTimeout(function (){
                settings.$mainLeft.find(".caroufredsel_wrapper").height($clone.find(".slide-item").height());
                $clone.find(".slide-item").each(function (){
                    $(this).css('top', $(this).height() - $clone.height());
                });
            }, 1000);
        }
        if(a){
            $clone.find(".panzoom").panzoom();
            $clone.find(".panzoom").panzoom("enable");
        } else {
            $clone.find(".panzoom").panzoom("disable");
        }
    }
    /**
     * Product Big Image "View Large Image"
     */
    function c(){
        if(settings.$wWidth > 614){
            $('.big-image').on('mousemove', function(e){
                var l = e.pageX - $(this).offset().left;
                var t = e.pageY - $(this).offset().top - 50;
                if(settings.$wWidth > 1038){
                    l += 120;
                }

                $('span.view-image').css({
                    left: l,//-140,
                    top:  t //+ 90
                });
            });
            $('.big-image .slide-item').on('mousemove', function(e){
                $('span.view-image').show();
            });
            $('.big-image .slide-item').on('mouseleave', function(e){
                $('span.view-image').hide();
            });
        }
    }
    /**
     * Preview Large Image
     */
    function d(){
        if(settings.$wWidth > 614){
            $('.big-image').find('.slide-item').click(function (){
                if(settings.$wWidth > 1038 && !settings.$btHide.hasClass('on')){
                    settings.$btHide.click();
                }
                if (settings.$contentarea.hasClass('toggle-active')) {
                    settings.$contentToggle.click();
                }
                $('span.view-image').hide();
                setTimeout(function (){
                    $(".product-left").width(settings.$main.width());
                }, 800);
                setTimeout(function (){
                    b(true);
                    $('.big-image .close-btn').show();
                }, 1000);
            });
            $('.big-image .close-btn').click(function (){
                if(settings.$wWidth > 1038 && settings.$btHide.hasClass('on')){
                    settings.$btHide.click();
                }
                if (!settings.$contentarea.hasClass('toggle-active')) {
                    settings.$contentToggle.click();
                }
                $('span.view-image').show();
                $('.big-image .close-btn').hide();
                setTimeout(function (){
                    $(".product-left").width(settings.$main.width() - settings.$contentarea.outerWidth());
                }, 800);
                setTimeout(function (){
                    b(false);
                }, 1000);
            });
        }
    }
    
    return {
        settings: settings,
        carousel: a,
        reCarousel: b
    }
}();
PPO.menu = function() {
    function a(a) {
        $.extend(q, a), s.on("resize.menu", o), e(), g(), m(), n(), c(), q.menuOpen || q.$btHide.trigger(r.clickEvent), r.isTouch && b(), o()
    }

    function b() {
        r.$smenu.swipe({
            swipeLeft: function(a, b, c, d, e) {
                k()
            },
            threshold: 40
        })
    }

    function c() {
        q.$btHide.on(r.clickEvent, function(a, b) {
            if(r.$main.hasClass('fullscreen')){
                r.$menu.animate({
                    left: '0px'
                }, 200, function (){
                    r.$smenu.show();
                    q.$header.show();
                    q.$footer.show();
                    q.$btHide.removeClass('on').html('Hide menu');
                    r.$main.width($(window).width() - 300).removeClass('fullscreen');
                });
            } else {
                r.$menu.animate({
                    left: '-250px'
                }, 200, function (){
                    r.$smenu.hide();
                    q.$header.hide();
                    q.$footer.hide();
                    q.$btHide.addClass('on').html('');
                    r.$main.width($(window).width() - 90).addClass('fullscreen');
                });
            }
            if(is_home){
                setTimeout(function (){
//                    $('.grid .grid-item').imagefit({
                    $('.grid .grid-w1, .grid .grid-w2, .grid .grid-w, .grid .grid-w4').imagefit({
                        mode: 'outside',
                        halign : 'center',
                        valign : 'middle',
                        force : 'true'
                    });
                    $('.grid .grid-w3').imagefit({
                        mode: 'outside',
                        force : 'true',
                        halign : 'center',
                        valign : 'middle'
                    });
                }, 1000);
            } else {
                if(!is_product){
                    setTimeout(function (){
                        if(PPO.slider.settings.$carousel.length > 0){
                            PPO.slider.reCarousel();
                        }
                    }, 1500);
                }
            }
        });
    }
    function d() {
        r.isTouch || r.$main.off("mouseover").one("mouseover", function() {
            i(), f(q.currentId), q.timer = setTimeout(k, q.delayCloseSmenu)
        })
    }
    function e() {
        $("nav a", r.$menu).each(function(a) {
            var b = $(this);
            b.hasClass("on") && (q.currentId = b.attr("data-id")), b.on(r.overEvent, function(a) {
                var b = $(this).attr("data-id");
                i(), d(), $("#snav-" + b).width() ? (a.preventDefault(), q.smenuOpen === !1 ? j() : r.isTouch && "block" == $("#snav-" + b).css("display") && k(), f(b), l(b)) : (f(b), q.timer = setTimeout(k, q.delayCloseSmenu))
            }), r.isTouch && b.on("click", function(a) {
                var b = $(this).attr("data-id");
                $("#snav-" + b).width() && (a.preventDefault(), $(this).trigger(r.overEvent))
            })
        })
    }
    function f(a) {
        q.id = a, r.$menu.find("nav a").removeClass("on"), $("nav a[data-id=" + q.id + "]", r.$menu).addClass("on")
    }

    function g() {
        $("nav ul li a", r.$smenu).each(function(a) {
            $(this).parent().find("ul.ssnav").length > 0 && $(this).on(r.clickEvent, function(a) {
                a.preventDefault();
                var b = $(this);
                b.hasClass("on") ? (b.removeClass("on"), b.parent().find("ul.ssnav").slideUp(150)) : (b.addClass("on"), b.parent().find("ul.ssnav").slideDown(150))
            })
        })
    }

    function h() {
        $(r.$smenu).off("mouseleave").one("mouseleave", function() {
            i(), f(q.currentId), q.timer = setTimeout(k, q.delayCloseSmenu)
        })
    }
    function i() {
        clearTimeout(q.timer), q.timer = null
    }
    function j() {
        i(), $("#sl-submenu span.close", r.$main).length && $("#sl-submenu span.close", r.$main).trigger("click"), r.$smenu.stop().animate({
            left: r.$menu.width() + "px"
        }, 300, "easeOutSine").off("mouseover").on("mouseover", function() {
            i(), h()
        }), q.smenuOpen = !0
    }

    function k() {
        r.$smenu.off("mouseout").stop().animate({
            left: r.$menu.width() - r.$smenu.width() + "px"
        }, 300, "easeOutSine", function() {
            r.$smenu.find("nav").hide()
        }), q.smenuOpen = !1, q.id = null
    }

    function l(a) {
        r.$smenu.find("nav").hide(), $("#snav-" + a).fadeIn(100)
    }
    
    function m(){
        $("#main-nav").children("li").each(function (i){
            /*var html_menu = '<li><a data-id="menu_' + i + '" href="' + $(this).children('a').attr('href') + '">';
            html_menu += $(this).children("a").html();
            html_menu += '</a></li>';
            q.$header.find("nav ul").append(html_menu);*/
            q.$header.find("nav ul li a[data-id='menu_" + i + "']").html($(this).children("a").html());
            q.$header.find("nav ul li a[data-id='menu_" + i + "']").attr('href', $(this).children('a').attr('href'));
            
            // Sub-Menu
            var $nav = $(this).children("ul.sub-menu").clone();
            var html_smenu = "";
            $nav.children("li").each(function (i){
                if($(this).children("ul.sub-menu").length > 0){
                    if(i === 0){
                        html_smenu += '<ul class="first">';
                        html_smenu += '<li class="title">' + $(this).children("a").text() + '</li>';
                    } else {
                        html_smenu += '<ul>';
                        html_smenu += '<li>' + $(this).children("a").text() + '</li>';
                    }
                    html_smenu += $(this).children("ul.sub-menu").html();
                    html_smenu += "</ul>";
                } else {
                    html_smenu += '<ul class="first">';
                    html_smenu += '<li><a href="' + $(this).children('a').attr('href') + '">' + $(this).children("a").text() + '</a></li>';
                    html_smenu += "</ul>";
                }
            });
            $("#snav-menu_" + i).html(html_smenu);
        });
    }
    
    function n(){
        var top = (s.height() - q.$header.height() - q.$footer.height() - 10 - 40) / 2;
        if(top > 0){
            q.$header.css('top', top + 30);
        }
    }

    function o() {
        var a = s.height(),
            b = q.menuOpen ? 0 : -r.$menu.width() + q.widthClosed;
        $("nav", r.$smenu).each(function(b) {
            var c = $(this),
                d = a / 2 - c.height() / 2;
            c.css({
                top: d + "px"
            })
        }), r.$menu.css({
            left: b + "px"
        }), q.smenuOpen ? $(r.$smenu).css({
            left: r.$menu.width() + "px"
        }) : $(r.$smenu).css({
            left: r.$menu.width() - r.$smenu.width() + "px"
        })
    }

    function p() {
        return q.menuOpen ? r.$menu.width() : q.widthClosed
    }
    var q = {
        $btHide: $("#menu").find("span.close"),
        $header: $("#menu").find("header"),
        $footer: $("#menu").find("footer"),
        menuOpen: !0,
        smenuOpen: !1,
        timer: null,
        id: null,
        currentId: null,
        delayCloseSmenu: 500,
        widthClosed: 90,
        cookieMenu: "ch_menu"
    }, r = {
        overEvent: Modernizr.touch ? "touchstart" : "mouseover",
        clickEvent: Modernizr.touch ? "touchstart" : "click",
        downEvent: Modernizr.touch ? "touchstart" : "mousedown",
        upEvent: Modernizr.touch ? "touchend" : "mouseup",
        moveEvent: Modernizr.touch ? "touchmove" : "mousemove",
        historyHtml5: Modernizr.history,
        transitionEndOptions: {
            WebkitTransition: "webkitTransitionEnd",
            MozTransition: "transitionend",
            OTransition: "oTransitionEnd",
            transition: "transitionend"
        },
        transition: Modernizr.prefixed("transition"),
        transitionEnd: {},
        transform: Modernizr.prefixed("transform"),
        hasTransitions: Modernizr.csstransitions,
        hasCssanimations: Modernizr.cssanimations,
        hasTransforms: Modernizr.csstransforms,
        hasTransforms3d: Modernizr.csstransforms3d,
        isTouch: Modernizr.touch,
        isFirefox: $.browser.mozilla,
        isChrome: null !== navigator.userAgent.match(/Chrome/i) ? !0 : !1,
        isIE: $.browser.msie,
        isIos: null !== navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? !0 : !1,
        browserVersion: Math.floor(parseInt($.browser.version)),
        $body: $("body"),
        $menu: $("div#menu"),
        $smenu: $("div#smenu"),
        $main: $("div#main"),
        $error: $("div#main div.error"),
        baseHeight: 1050,
        aDatas: null,
        indexItem: 0,
        screenWidthMini: 600,
        screenHeightMini: 600
    }, s = $(window);
    return {
        init: a,
        getWidth: p
    }
}();