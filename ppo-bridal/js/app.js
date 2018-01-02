jQuery(document).ready(function ($) {
    if ($.browser.msie)
        $("html").removeClass("csstransforms3d");
    var dtGlobals = {}; // Global storage


    /* !Custom touch events */
    /* !(we need to add swipe events here) */

    dtGlobals.touches = {};
    dtGlobals.touches.touching = false;
    dtGlobals.touches.touch = false;
    dtGlobals.touches.currX = 0;
    dtGlobals.touches.currY = 0;
    dtGlobals.touches.cachedX = 0;
    dtGlobals.touches.cachedY = 0;
    dtGlobals.touches.count = 0;
    dtGlobals.resizeCounter = 0;

    dtGlobals.isMobile = (/(Android|BlackBerry|iPhone|iPod|iPad|Palm|Symbian)/.test(navigator.userAgent));
    dtGlobals.isAndroid = (/(Android)/.test(navigator.userAgent));
    dtGlobals.isiOS = (/(iPhone|iPod|iPad)/.test(navigator.userAgent));
    dtGlobals.isiPhone = (/(iPhone|iPod)/.test(navigator.userAgent));
    dtGlobals.isiPad = (/(iPad)/.test(navigator.userAgent));


    $(document).on('touchstart', function (e) {
        if (e.originalEvent.touches.length == 1) {
            dtGlobals.touches.touch = e.originalEvent.touches[0];

            // caching the current x
            dtGlobals.touches.cachedX = dtGlobals.touches.touch.pageX;
            // caching the current y
            dtGlobals.touches.cachedY = dtGlobals.touches.touch.pageY;
            // a touch event is detected      
            dtGlobals.touches.touching = true;

            // detecting if after 200ms the finger is still in the same position
            setTimeout(function () {

                dtGlobals.touches.currX = dtGlobals.touches.touch.pageX;
                dtGlobals.touches.currY = dtGlobals.touches.touch.pageY;

                if ((dtGlobals.touches.cachedX === dtGlobals.touches.currX) && !dtGlobals.touches.touching && (dtGlobals.touches.cachedY === dtGlobals.touches.currY)) {
                    // Here you get the Tap event
                    dtGlobals.touches.count++;
                    $(e.target).trigger("tap");
                }
            }, 200);
        }
    });

    $(document).on('touchend touchcancel', function (e) {
        // here we can consider finished the touch event
        dtGlobals.touches.touching = false;
    });

    $(document).on('touchmove', function (e) {
        dtGlobals.touches.touch = e.originalEvent.touches[0];

        if (dtGlobals.touches.touching) {
            // here you are swiping
        }
    });


    $(document).on("tap", function (e) {
        $(".dt-hovered").trigger("mouseout");
    });

    /* Custom touch events:end */
    /* !Debounced resize event */
    var dtResizeTimeout;
    $(window).on("resize", function () {
        clearTimeout(dtResizeTimeout);
        dtResizeTimeout = setTimeout(function () {
            $(window).trigger("debouncedresize");
        }, 200);
    });
    /* Debounced resize event: end */

    /* !jQuery extensions */

    /* !- Check if element exists */
    $.fn.exists = function () {
        if ($(this).length > 0) {
            return true;
        } else {
            return false;
        }
    }

    /* !- Check if element is loaded */
    $.fn.loaded = function (callback, jointCallback, ensureCallback) {
        var len = this.length;
        if (len > 0) {
            return this.each(function () {
                var el = this,
                        $el = $(el),
                        blank = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";

                $el.on("load.dt", function (event) {
                    $(this).off("load.dt");
                    if (typeof callback == "function") {
                        callback.call(this);
                    }
                    if (--len <= 0 && (typeof jointCallback == "function")) {
                        jointCallback.call(this);
                    }
                });

                if (!el.complete || el.complete === undefined) {
                    el.src = el.src;
                } else {
                    $el.trigger("load.dt")
                }
            });
        } else if (ensureCallback) {
            if (typeof jointCallback == "function") {
                jointCallback.call(this);
            }
            return this;
        }
    };

    /* jQuery extensions: end */

    /* !Main navigation */
    /* We need to fine-tune timings and do something about the usage of jQuery "animate" function */

    $("#mobile-menu").wrap('<div id="dl-menu" class="dl-menuwrapper" />');
    var $mobileNav = $("#main-nav").clone();
    $mobileNav
            .attr("id", "")
            .attr("class", "dl-menu")
            .find(".sub-menu")
            .addClass("dl-submenu")
            .removeClass("sub-menu");
    $mobileNav.appendTo("#dl-menu");
    $("#dl-menu").prepend('<button class="dl-trigger">Open Menu</button>');
    
    if (!$("html").hasClass("old-ie"))
        $('#dl-menu').dlmenu();

    PPO.menu.init();

    /* Main navigation: end */


    /* init prettyPhoto */
    $("a[rel^='prettyPhoto']").prettyPhoto();

    /* !Beautiful loading */

    if(is_home){
        dtGlobals.imgLoaded = setTimeout(function () {
            $("body").append('<style>img {opacity: 1}</style>');
        }, 20000);

        $("img").loaded(function () {
            $(this).css("opacity", 1);
        }, function () {
            clearTimeout(dtGlobals.imgLoaded);
        }, true);
    }

    /* !Beautiful loading: end */

    /* Old ie raw emulation */
    //$("html").addClass("old-ie");
    
    $(window).bind('load', function (){
        setTimeout(function (){
            if(PPO.slider.settings.$carousel.length > 0){
                PPO.slider.carousel();
            }
        }, 1000);
    });
    $(window).bind('resize', function (){
        setTimeout(function (){
            if(PPO.slider.settings.$carousel.length > 0){
                PPO.slider.reCarousel();
            }
        }, 1000);
    });
    $(window).bind('load resize', function (){
        var wWidth = $(this).width(), wHeight = $(this).height();
        $("#main").width(wWidth - 300);
        
        if(is_home){
            $('.grid').height(wHeight);
//            $('.grid .grid-item').imagefit({
            $('.grid .grid-w1, .grid .grid-w2, .grid .grid-w, .grid .grid-w4').imagefit({
                mode: 'outside',
                force : 'true',
                halign : 'center',
                valign : 'middle'
            });
            $('.grid .grid-w3').imagefit({
                mode: 'outside',
                force : 'true',
                halign : 'center',
                valign : 'middle'
            });
            
            $("#main .grid .grid-item").hoverIntent({
                over: function(){
                    $(this).find("section").stop().show();
                    var _left = ($(this).find("section").width() - $(this).find("article").width()) / 2;
                    var _top = ($(this).find("section").height() - $(this).find("article").height()) / 2;
                    $(this).find("article a").css({
                        padding: (_top - 0.2) + 'px ' + (_left - 0.2) + 'px'
                    });
                },
                timeout: 0,
                out: function(){
                    $(this).find("section").stop().hide();
                }
            });
        } else {
            $(".page-slideshow, .page-layerslider").height(wHeight);
            if(wWidth <= 614){
                $('.main-left').height('auto');
            } else {
                $('.main-left').height(wHeight);
            }
            
            /* Scrollbar */
            $.mCustomScrollbar.defaults.scrollButtons.enable=true; //enable scrolling buttons by default
            $(".post").mCustomScrollbar({
                axis: "yx", //enable 2 axis scrollbars by default
                theme:"minimal-dark"
            });
            setTimeout(function (){
                $(".post").find("#page-content-toggle").prependTo(".post");
            }, 1000);
            /* Scrollbar: end */
        }
    });

    $("#menu header nav .search .search-field").focusin(function (){
        $("#menu header nav .search .ico-search").animate({
            right: '10px'
        }, 600);
    });
    $("#menu header nav .search .search-field").focusout(function (){
        $("#menu header nav .search .ico-search").animate({
            right: '50%'
        }, 600);
    });
});

(function ($) {
    var $contentarea = $('.post'),
        new_content_width = 200,
        window_width = $(window).width(),
        $nextslide = $('#nextslide');

    /**
     * On Document Ready
     */
    $(document).ready(function () {

        $(window).bind('load resize', function (){
            if(!is_product && window_width <= 1038){
                new_content_width = 150;
            }
            if(is_product && window_width > 614){
                $(".product-left").width($('#main').width() - new_content_width - 50);
            }
            if($("#slide-carousel").length > 0 && $("#slide-carousel").find(".slide-item").length === 0){
                $contentarea.animate({
                    width: window_width - 500 - 50,
                    right: 0
                }, 800, function (){
                    $contentarea.find(".mCSB_container").width('auto');
                });
            } else {
                setTimeout(function (){
                    toggle_content();
                }, 1000);
            }
        });

        //On click event for contentarea arrow
        //When arrow is clicked toggle the contentarea visibility (animation / slide in / slideout)
        $('#page-content-toggle').click(function () {
            toggle_content();
            return false;
        });

    });

    //Toggle contentarea
    function toggle_content() {
        if(window_width > 614){
            var content_width = new_content_width;

            //Set content area width
            $contentarea.css('width', content_width);

            //Position content area to right (so that is not visible)
            $contentarea.css('right', '-' + (content_width) + 'px');

            if (!$contentarea.hasClass('toggle-active')) {
                slide_content_in(content_width);
            } else {
                slide_content_out(content_width);
            }
        }
    }
    
    //Slide content in
    function slide_content_in(content_width) {
        $contentarea.css('right', '-' + (content_width) + 'px');

        $contentarea.animate({
            marginRight: content_width
        }, 800).addClass('toggle-active');
        
        if(!is_product){
            $nextslide.animate({
                right: content_width + 50 + 35
            }, 800);
        }
    }

    //Slide content out
    function slide_content_out(content_width) {
        $contentarea.css('right', '-' + (content_width + 50) + 'px');
        
        $contentarea.animate({
            marginRight: 0
        }, 800).removeClass('toggle-active');
        
        $nextslide.animate({
            right: 35
        }, 800);
    }

}(jQuery));