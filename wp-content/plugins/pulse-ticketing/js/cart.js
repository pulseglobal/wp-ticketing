
var PTCART = PTCART || {};

PTCART = {
    /**
     *
     */
    init: function() {
        this.registerHooks();
        PTCART.loadCart();
        this.reValidate(jQuery('form[name="checkoutForm"]'));
    },

    /**
     * onWhatever handlers
     */
    registerHooks: function() {
        var self = this;

        jQuery('.lpCartAdd').on('click', function(e) {
            e.preventDefault();
            var $quantity = jQuery(this).parents('.lpTicket').find('select[name="quantity"]');
            var ticketId = parseInt($quantity.data('ticket-id'));
            var quantity = parseInt($quantity.val());
            PTCART.updateCart(ticketId, quantity);
        });

        jQuery('form[name="checkoutForm"]').on('submit', function(e) {
            e.preventDefault();
            if (PTCART.validate(jQuery(this))) {
                PTCART.showProcess('show');
                PTCART.sendOrder(jQuery(this).serialize());
            }
        });

        jQuery('.cart-sidebar').on('click', '.removeTicket', function(e) {
            e.preventDefault();
            var ticketId = jQuery(this).data('id');
            PTCART.updateCart(ticketId, 0);
            if (jQuery('.checkout-body').length) {
                PTCART.removeAttendees(ticketId);
            }
        });

        jQuery('#page').on('render_cart', '.cart-sidebar', function(e) {
            self.loadCart();
        });
    },

    /**
     * load the current cart and trigger the rendering of the sidebar
     * cart
     */
    loadCart: function() {
        jQuery.post(
            CartAjax.ajaxurl, { action : 'ajax-cart-update' },
            function( response ) {
                PTCART.renderCartFloater(response.data.cart);
            }
        );
    },

    /**
     * update the cart
     *
     * @param ticketId
     * @param quantity
     */
    updateCart: function (ticketId, quantity) {
        console.log('Updating cart: ticket#', ticketId, 'quantity' , quantity);

        jQuery.post(
            CartAjax.ajaxurl,
            {
                action : 'ajax-cart-update',
                ticketId : ticketId,
                quantity : quantity
            },
            function( response ) {
                console.log( response ,'the response');
                PTCART.renderCartFloater(response.data.cart);
                if (response.data.cart[ticketId]) {
                    jQuery('.cart-notification').remove();
                    jQuery('.cart-wrapper')
                        .append(
                            '<div class="cart-notification"><div class="arrow-up"></div><h4>Added - '+response.data.cart[ticketId].quantity+' x '+response.data.cart[ticketId].name+'('+response.data.cart[ticketId].currency+ '' +response.data.cart[ticketId].price +')</h4></div>'
                        );
                    setTimeout(function() {
                        jQuery('.cart-notification').fadeOut(180);
                    }, 20222200, function() {
                        jQuery('.cart-notification').remove();
                    });
                }
            }
        );
    },

    /**
     * Remove the individual attendee details from the checkout form, for the specified
     * ticket ID. This is mainly used when an item in the cart gets deleted and we're on the
     * checkout page.
     *
     * @param ticketId
     */
    removeAttendees: function (ticketId) {
        jQuery('.attendee-wrapper[data-ticket-id="' + ticketId + '"]').remove();
    },

    /**
     * Realtime render the current cart
     *
     * @param cart
     */
    renderCartFloater: function (cart) {
        console.log('Updating cart floater', cart);

        var $cartSidebar = jQuery('.cart-sidebar');
        var $cartCrumb = jQuery('.crumb-cart');
        var paymentType = jQuery('input[name="payment_method"]').val();
        var bookingFees = 0.0;
        var totalOrder = 0;
        var currency = '';

        $cartSidebar.find('.entry-tickets.cart-row').html('');
        if (cart && cart.constructor === Object && Object.keys(cart).length !== 0) {
            jQuery.each(cart, function(index, value) {
                var ticketHtml = '<h4>' + value.quantity + ' x ' + value.name + ' '
                    + '<span class="pull-right">' + value.currency + ' '
                    + '<em class="js-amount">' + value.base_price * value.quantity
                    + '</em> <a href="#" data-id="' + index + '" class="removeTicket">-</a>'
                    + '</span></h4>';
                $cartSidebar.find('.entry-tickets.cart-row').append(ticketHtml);
                bookingFees += value.fee * value.quantity;
                totalOrder += value.base_price * value.quantity;
                currency = value.currency;
            });

            $cartSidebar.find('.cta').show();
        } else {
            $cartSidebar.find('.entry-tickets.cart-row').append('<h4>No items in your cart</h4>');
        }

        totalOrder += bookingFees;

        var bookingFeesHtml = currency + ' <em class="js-amount">' + parseFloat(Math.round(bookingFees * 100) / 100).toFixed(2); + '</em>';
        $cartSidebar.find('.ptBookingTotal').html(bookingFeesHtml);

        var totalOrderHtml = currency + ' <em class="js-amount">' + Math.round(totalOrder * 100) / 100 + '</em>';
        $cartSidebar.find('.ptTotalOrder').html(totalOrderHtml);
        $cartCrumb.find('.cart_total').html(totalOrderHtml);

    },

    /**
     * Simple validation function
     *
     * @param $form
     * @returns {boolean}
     */
    validate: function ($form) {
        var isValid = true;
        $form.find('input.required').each(function(index, field) {
            if (jQuery(field).is(':visible') && ! jQuery(field).val().length || jQuery(field).hasClass('terms-condition') && !jQuery(field).is(':checked')) {
                jQuery(field).addClass('invalid');
                if (jQuery(field).hasClass('terms-condition')) {
                    jQuery(field).parents('.field-wrapper').addClass('checkbox-invalid');
                }
                isValid = false;
            } else {
                jQuery(field).removeClass('invalid');
                if (jQuery(field).hasClass('terms-condition')) {
                    jQuery(field).parents('.field-wrapper').removeClass('checkbox-invalid');
                }
            }
        });

        return isValid;
    },

    /**
     * Revalidate fields on input function
     *
     * @param $form
     * @returns {boolean}
     */
    reValidate: function ($form) {

        $form.find('input.required').on('input change', function() {
           var $this = jQuery(this);
           if ($this.val().length > 0) {
               $this.removeClass('invalid');
           }

           if ($this.hasClass('terms-condition') && $this[0].checked == true) {
               $this.removeClass('invalid');
               if ($this.hasClass('terms-condition')) {
                   $this.parents('.field-wrapper').removeClass('checkbox-invalid');
               }
           }
        });
    },

    /**
     * Send order to server
     *
     * @param order
     */
    sendOrder: function (order) {
        order += '&action=ajax-send-order';
        console.log('Sending order data', order);
        // START SPINNER
        var $lpMessage = jQuery('.lpMessage');
        $lpMessage.html('').hide(); // hide any previous message
        jQuery.post(
            CartAjax.ajaxurl,
            order,
            function( response ) {
                console.log( response );
                PTCART.showProcess('hide'); // stop spinner
                if (typeof response.data.order !== 'undefined') {
                    PTCART.processOrderResponse(response.data.order);
                } else if (typeof response.data.validation !== 'undefined') {
                    PTCART.processOrderValidation(response.data.validation)
                } else {
                    $lpMessage.html('Your order could not be processed, please contact support!').show();
                }
            }
        ).fail(function() {
            $lpMessage.html('Your order could not be sent, please contact support!').show();
        });
    },

    /**
     * @param order
     */
    processOrderResponse: function (order) {
        var $lpMessage = jQuery('.lpMessage');
        if (typeof order.order_number !== 'undefined') {
            $lpMessage.hide();
            if (order.payment_method === 'viagogo' || order.payment_method === 'oxxo') {
                window.location.href = '/confirmation/';
            } else if (order.payment_method === 'zipPay') {
                console.log('popping out zipPay with URL ' + order.redirect_url);
                zipMoney.checkout(order.redirect_url);
            } else {
                $lpMessage.html('Your order could not be processed, please contact support (Invalid Payment Method).');
            }
        } else if (typeof order.payment_information !== 'undefined') {
            $lpMessage.html('Your order could not be processed: ' + order.payment_information).show();
        }
    },

    /**
     * @param validation
     */
    processOrderValidation: function (validation) {
        var $lpMessage = jQuery('.lpMessage');
        $lpMessage.html('<h3>Your order could not be processed:</h3>').show();
        $lpMessage.append('<ul>');
        jQuery.each(validation, function(index, value) {
            jQuery.each(value, function(inner, message) {
                $lpMessage.find('ul').append('<li><strong>' + toTitleCase(index.replaceAll('_', ' ')) + '</strong>: ' + message + '</li>');
            });
        });

    },

    showProcess: function(status) {
        if ( status == 'show'  ) {
            $('.process-spinner').addClass('active');
        } else {
            $('.process-spinner').removeClass('active');
        }
    }

};

function toTitleCase(str) {
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

String.prototype.replaceAll = function(target, replacement) {
    return this.split(target).join(replacement);
};

jQuery(window).load(function() {
    PTCART.init();
});


