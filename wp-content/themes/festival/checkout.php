<?php
/**
 * Template Name: Checkout Page
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package festival
 */

get_header(); ?>

<div id="tickets-listing" class="ticket-listing">

    <div class="container">
        <div class="content-heading hidden-xs">
            <h2>Tickets</h2>
        </div>
        <!-- /.content-heading -->

        <div class="tickets-menu">
            <?php wp_nav_menu( array( 'theme_location' => 'menu-3', 'menu_id' => 'tickets-menu' ) ); ?>
        </div>
        <!-- /.tickets-menu -->
    </div>
    <!-- /.container -->

    <div class="tickets-wrapper">
        <form name="checkoutForm" method="post" novalidate>
            <input type="hidden" name="payment_method" value="<?=!empty($_SESSION['order']['payment_method']) ? $_SESSION['order']['payment_method'] : 'viagogo'; ?>" />
        <div class="container clearfix">
            <?php get_template_part( 'inc/cart', 'sidebar' ); ?>
            <div class="checkout-container">
                <div class="content-subtitle">
                    <h2>
                        CHECKOUT
                    </h2>
                    <h4>Please select your payment method<br/>VISA, MASTERCARD OR OXXO ONLY</h4>
                </div>
                <!-- /.content-subtitle -->
                <div class="checkout-tabs">
                    <div class="checkout-tab-pills">
                        <div class="tab-pill <?=empty($_SESSION['order']['payment_method']) || $_SESSION['order']['payment_method'] == 'viagogo' ? 'active' : ''; ?>">
                            <a href="#" data-target="cc-tab">Credit card</a>
                        </div>
                        <!-- /.tab-pill -->
                        <div class="tab-pill <?=!empty($_SESSION['order']['payment_method']) && $_SESSION['order']['payment_method'] == 'oxxo' ? 'active' : ''; ?>">
                            <a href="#" data-target="oxxo-tab">Oxxo</a>
                        </div>
                        <!-- /.tab-pill -->
                    </div>
                    <!-- /.checkout-tab-pills -->
                    <div class="checkout-body">
                        <div id="cc-tab" class="active tab-body">
                            <div class="field-row double-row">
                                <div class="field-wrapper">
                                    <input type="text" placeholder="First Name" name="billing_first_name" class="required"
                                           value="<?=!empty($_SESSION['order']['billing_first_name']) ? $_SESSION['order']['billing_first_name'] : ''; ?>">
                                </div>
                                <!-- /.field-wrapper -->
                                <div class="field-wrapper">
                                    <input type="text" placeholder="Last Name" name="billing_last_name" class="required" required="required"
                                           value="<?=!empty($_SESSION['order']['billing_last_name']) ? $_SESSION['order']['billing_last_name'] : ''; ?>">
                                </div>
                                <!-- /.field-wrapper -->
                            </div>
                            <!-- /.field-row -->
                            <div class="field-row">
                                <div class="field-wrapper">
                                    <input type="email" placeholder="Email" name="customer_email" class="required" required="required"
                                           value="<?=!empty($_SESSION['order']['customer_email']) ? $_SESSION['order']['customer_email'] : ''; ?>">
                                </div>
                                <!-- /.field-wrapper -->
                            </div>
                            <!-- /.field-row -->
                            <div class="field-row double-row">
                                <div class="field-wrapper">
                                    <input type="text" placeholder="Address" name="custom_fields[billing_address]" class="required" required="required"
                                           value="<?=!empty($_SESSION['order']['billing_address']) ? $_SESSION['order']['billing_address'] : ''; ?>">
                                </div>
                                <!-- /.field-wrapper -->
                            </div>
                            <!-- /.field-row -->
                            
                            <div class="field-row double-row">
                                <div class="field-wrapper">
                                    <input type="text" placeholder="Zipcode" name="billing_zip" class="required" required="required"
                                           value="<?=!empty($_SESSION['order']['billing_zip']) ? $_SESSION['order']['billing_zip'] : ''; ?>">
                                </div>
                                <!-- /.field-wrapper -->
                            </div>
                            <!-- /.field-row -->

                            <div class="field-row">
                                <div class="quantity pull-right">
                                    <select name="custom_fields[country]" class="required" required="required"
                                           value="<?=!empty($_SESSION['order']['country']) ? $_SESSION['order']['country'] : ''; ?>">
                                       <option value='ES'>Spain</option>    
                                       <option value='RO'>Romania</option>
                                   </select>
                                </div>
                                <!-- /.field-wrapper -->
                            </div>
                            <!-- /.field-row -->

                            <div class="field-row bordered-row">
                                <div class="field-wrapper">
                                    <label>
                                        <input type="checkbox" <?=!isset($_SESSION['order']['tickets_same_name']) || !empty($_SESSION['order']['tickets_same_name']) ? 'checked="checked"': ''; ?> name="tickets_same_name">
                                        <span>ALL TICKETS ARE UNDER MY NAME</span>
                                    </label>
                                </div>
                                <!-- /.field-wrapper -->
                            </div>
                            <!-- /.field-row -->

                            <div class="ticket_attendees" style="margin-left: 40px; display: none;">
                                <?php $cart = $_SESSION['cart']; $index = 1; ?>
                                <?php foreach ($cart as $ticketId => $ticket): ?>
                                    <?php for ($counter = 0; $counter < $ticket['quantity']; $counter++): ?>
                                        <div class="attendee-wrapper" data-ticket-id="<?=$ticketId; ?>">
                                            <h3>Ticket '<?=$ticket['name']; ?>' - Attendee #<?=$index++; ?></h3>
                                            <div class="field-row double-row">
                                                <div class="field-wrapper">
                                                    <input type="text" placeholder="First Name" name="attendee[<?=$ticketId; ?>][<?=$counter; ?>][first_name]"
                                                           value="<?=!empty($_SESSION['order']['attendee'][$ticketId][$counter]['first_name']) ? $_SESSION['order']['attendee'][$ticketId][$counter]['first_name'] : ''; ?>">
                                                </div>
                                                <!-- /.field-wrapper -->
                                                <div class="field-wrapper">
                                                    <input type="text" placeholder="Last Name" name="attendee[<?=$ticketId; ?>][<?=$counter; ?>][last_name]"
                                                           value="<?=!empty($_SESSION['order']['attendee'][$ticketId][$counter]['last_name']) ? $_SESSION['order']['attendee'][$ticketId][$counter]['last_name'] : ''; ?>">
                                                </div>
                                                <!-- /.field-wrapper -->
                                            </div>
                                            <!-- /.field-row -->
                                            <div class="field-row double-row">
                                                <div class="field-wrapper">
                                                    <input type="email" placeholder="Email" name="attendee[<?=$ticketId; ?>][<?=$counter; ?>][email]"
                                                           value="<?=!empty($_SESSION['order']['attendee'][$ticketId][$counter]['email']) ? $_SESSION['order']['attendee'][$ticketId][$counter]['email'] : ''; ?>">
                                                </div>
                                                <!-- /.field-wrapper -->
                                                <div class="field-wrapper">
                                                    <input type="text" placeholder="Zipcode" name="attendee[<?=$ticketId; ?>][<?=$counter; ?>][zip_code]"
                                                           value="<?=!empty($_SESSION['order']['attendee'][$ticketId][$counter]['zip_code']) ? $_SESSION['order']['attendee'][$ticketId][$counter]['zip_code'] : ''; ?>">
                                                </div>
                                                <!-- /.field-wrapper -->
                                            </div>
                                            <!-- /.field-row -->
                                        </div>
                                    <?php endfor; ?>
                                <?php endforeach; ?>
                            </div>
                            <div class="cc-card-fields" style="<?=!empty($_SESSION['order']['payment_method']) && $_SESSION['order']['payment_method'] == 'oxxo' ? 'display: none;' : ''; ?>">
                                <div class="field-row">
                                    <div class="field-wrapper">
                                        <input type="text" placeholder="Name on Card" name="card_name" class="required"
                                               value="<?=!empty($_SESSION['order']['card_name']) ? $_SESSION['order']['card_name'] : ''; ?>">
                                    </div>
                                    <!-- /.field-wrapper -->
                                </div>
                                <!-- /.field-row -->
                                <div class="field-row">
                                    <div class="field-wrapper">
                                        <input type="text" placeholder="Card Number" name="card_number" class="required"
                                               value="<?=!empty($_SESSION['order']['card_number']) ? $_SESSION['order']['card_number'] : ''; ?>">
                                    </div>
                                    <!-- /.field-wrapper -->
                                </div>
                                <!-- /.field-row -->
                                <div class="field-row">
                                    <div class="quantity pull-right">
                                        <span>Expiry Date</span>
                                        <select name="expire_month">
                                            <?php for($i = 1; $i <= 12; $i++): ?>
                                                <option value="<?=$i; ?>" <?=!empty($_SESSION['order']['expire_month']) && $_SESSION['order']['expire_month'] == $i ? 'selected="selected"' : ''; ?>><?=sprintf('%02d', $i); ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <!-- /# expiry month-->
                                        <select class="exp_year" name="expire_year">
                                            <?php for($i = 2017; $i <= 2024; $i++): ?>
                                                <option value="<?=$i; ?>" <?=!empty($_SESSION['order']['expire_year']) && $_SESSION['order']['expire_year'] == $i ? 'selected="selected"' : ''; ?>><?=sprintf('%4d', $i); ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <!-- /#expiry year -->
                                    </div>
                                </div>
                                <!-- /.field-row -->
                                <div class="field-row small-fields">
                                    <div class="field-wrapper">
                                        <input type="text" placeholder="CVV" name="cvv_number" class="required" required="required">
                                        <span>
                                            This is the last 3 digits normally found on the signature strip of your card
                                        </span>
                                    </div>
                                    <!-- /.field-wrapper -->
                                </div>
                                <!-- /.field-row -->
                            </div>
                            <!-- /.cc-card-fields -->
                            <div class="lpMessage checkout-notice" style="display: none;">

                            </div>
                                <div class="field-row">
                                    <div class="field-wrapper">
                                        <button type="submit" class="btn btn-cta-color btn-reversed lpBookNow">Book Now</button>
                                    </div>
                                    <!-- /.field-wrapper -->
                                </div>
                                <!-- /.field-row -->
                        </div>
                        <!-- /#cc-tab.tab-body -->
                    </div>
                    <!-- /.checkout-body -->
                </div>
                <!-- /.checkout-tabs -->
            </div>
            <!-- /.checkout-container -->
        </div>
        <!-- /.container -->
        </form>
    </div>
    <!-- /.tickets-wrapper -->
</div>
<!-- /#tickets-listing.ticket-listing -->
<?php
get_footer();
