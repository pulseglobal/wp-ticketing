var LOSTAPP = LOSTAPP || {};
LOSTAPP = {
    init: function() {
        var self = this;
        this.tabNavigation();
        if ( $(window).width() > 992 ) {
            self.scrollCart();
        }
        if ($('form[name="checkoutForm"]').length) {
            self.loadCheckout();
        }
    },

    tabNavigation: function() {
        $('.tab-pill').on('click', 'a', function(e) {
            e.preventDefault();
            var $this = $(this),
                $cc_fields = $('.cc-card-fields'),
                $parent = $this.parent(),
                target = $this.attr('data-target');

            $parent.parent().find('.active').removeClass('active'); // remove previous tab pill class
            $parent.addClass('active'); // add class to current selection

            if ( target == 'oxxo-tab' ) {
                $cc_fields.hide();
                $('input[name="payment_method"]').val('oxxo');
            } else {
                $cc_fields.show();
                $('input[name="payment_method"]').val('viagogo');
            }

            $('.cart-sidebar').trigger('render_cart');
        });
    },

    scrollCart: function() {
        var $cart = $('.cart'),
            $header = $('.site-header'),
            headerHeight = $header.outerHeight(true),
            cartHeight = $cart.height(),
            windowHeight = $(window).height(),
            currentCartPosition = $cart.offset().top;
            currentWindowPosition = $(window).scrollTop() + 190; //190 is the equivalent in pixels of the header height
        // and the padding of the cart from the header while it's fixed
        if (currentWindowPosition > currentCartPosition && (headerHeight + cartHeight) < windowHeight) {
            // handle the cart position on window load
            $cart.addClass('fixed');
        }

        $(window).scroll(function() {
            var $window = $(this);
                scrollPosition = $window.scrollTop() + 190; //190 is the equivalent in pixels of the header height
            // and the padding of the cart from the header while it's fixed;
            if ( scrollPosition > currentCartPosition && (headerHeight + cartHeight) < windowHeight) {
                // handle the care fixed position when we scroll the page.
                $cart.addClass('fixed');
            } else {
                $cart.removeClass('fixed');
            }
        });

    },

    loadCheckout: function() {
        var self = this;

        $('input[name="tickets_same_name"]').on('click', function() {
            self.handleAttendees($(this));
        });

        self.handleAttendees($('input[name="tickets_same_name"]'));

        self.handleZipCallback();
    },

    handleZipCallback: function() {
        (function($) {
            $.QueryString = (function(a) {
                if (a == "") return {};
                var b = {};
                for (var i = 0; i < a.length; ++i)
                {
                    var p=a[i].split('=', 2);
                    if (p.length != 2) continue;
                    b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
                }
                return b;
            })(window.location.search.substr(1).split('&'))
        })(jQuery);

        if ($.QueryString["zip"] === "cancel") {
            var $lpMessage = jQuery('.lpMessage');
            $lpMessage.html('Your oxxo order was cancelled.').show();
        }

        if ($.QueryString["zip"] === "error") {
            var $lpMessage = jQuery('.lpMessage');
            $lpMessage.html('Sorry, your oxxo order was denied. Please try again or use a different payment method.').show();
        }
    },

    handleAttendees: function($allCheckbox) {
        var $ticketAttendees = $('.ticket_attendees');
        if ($allCheckbox.is(':checked')) {
            $ticketAttendees.find('input').removeClass('required');
            $ticketAttendees.slideUp();
        } else {
            $ticketAttendees.find('input').addClass('required');
            $ticketAttendees.slideDown();
        }
    },


    setCookie: function(key, value) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (1000 * 60 * 60 * 24 * 21)); // where 1000 * 60 = 1 minute * 60 = 1 hour
        // * 24 = 24 hours * 21 = 3 weeks aka 21 days.
        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
    },

    loadCookie: function(key) {
        var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
        return keyValue ? keyValue[2] : null;
    },
};

$(window).load(function() {
   LOSTAPP.init();
   // init mtachheight plugin
    if ( $('.tickets-list').length > 0 ) {
        $('.ticket-list-item').matchHeight({
            property: 'min-height',
        });
    }
    if ( $('.music-post').length > 0 ) {
        $('.journal-post.music-post').matchHeight({
            property: 'min-height',
        });
    }
    // init flexslider
    if ($('.flexslider').length > 0) {
        $('.flexslider').flexslider({
            animation: "fade",
            animationSpeed: "300",
            controlNav: false,
            slideshowSpeed: 4000
        });
    }
    if ($('.container-wide').length) {
        LOSTAPP.triggerSetPopups();
    }
});