<?php function get_listing_template($post_type = 'tickets'){ ?>
<div class="tickets-listing-container">
    <?
        $mobileBrowser = isMobileBrowser(); ?>
    <!-- /.featured-description -->
    <div class="tickets-list">
        <?php while ( have_posts() ) : the_post(); ?>
        <div class="ticket-list-item lpTicket">
	        <?php $ticketId = get_field('ticket_id'); $tickets = get_option('pulse_ticketing_tickets'); $ticket = array(); ?>
	        <?php foreach($tickets as $oneTicket) if ($oneTicket['id'] == $ticketId) $ticket = $oneTicket; ?>
            <div class="ticket-item-maincontent">
                <div class="left-ticket-content">
                    <div class="img-wrapper">
                        <a href="<?= the_permalink(); ?>"
                           class="single-page-link"><?php the_post_thumbnail('medium'); ?></a>
                    </div>
                    <!-- /.img-wrapper -->
                    <?php if (!$mobileBrowser): ?>
                        <div class="quantity pull-right">
                            <?php if(!empty($ticket) && !empty($ticket['sold_out'])): ?>
                            <span style="color: red; font-weight: bold;">SOLD OUT</span>
                            <?php elseif(!empty($ticket)): ?>
                            <span>Quantity</span>
                            <select name="quantity" data-ticket-id="<?=$ticketId; ?>">
                                <?php foreach ($ticket['quantity'] as $qty): ?>
                                <option value="<?=$qty; ?>"><?=$qty; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php endif; ?>
                        </div>
                        <!-- /.quantity -->

                        <?php if(!empty($ticket) && empty($ticket['sold_out'])): ?>
                        <div class="cta pull-right">
                            <a href="#" class="btn btn-small btn-cta-color btn-bordered lpCartAdd">Add to cart</a>
                        </div>
                        <!-- /.cta -->
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <!-- /.left-ticket-content -->
                <div class="item-content">
                    <div class="pull-left title">
                        <h3>
                            <a href="<?php the_permalink(); ?>" class="single-page-link"><? the_title(); ?></a>
                        </h3>
                    </div>
                    <!-- /.title -->
                    <div class="pull-left price">
                        <?php if (!empty($ticket)): ?>
                            <?php if(empty($ticket['sold_out'])): ?>
                                <p><strong><?=$ticket['currency'] . $ticket['base_price']; ?></strong> (additional booking fee will apply)</p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p><strong>unavailable</strong></p>
                        <?php endif; ?>
                    </div>
                    <!-- /.price -->
                    <?php if ($mobileBrowser): ?>
                        <div class="quantity">
                            <?php if(!empty($ticket) && !empty($ticket['sold_out'])): ?>
                            <span style="color: red; font-weight: bold;">SOLD OUT</span>
                            <?php elseif(!empty($ticket)): ?>
                            <span>Quantity</span>
                            <select name="quantity" data-ticket-id="<?=$ticketId; ?>">
                                <?php foreach ($ticket['quantity'] as $qty): ?>
                                <option value="<?=$qty; ?>"><?=$qty; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php endif; ?>
                        </div>
                        <!-- /.quantity -->

                        <?php if(!empty($ticket) && empty($ticket['sold_out'])): ?>
                        <div class="cta">
                            <a href="#" class="btn btn-small btn-cta-color btn-bordered lpCartAdd">Add to cart</a>
                        </div>
                        <!-- /.cta -->
                        <?php endif; ?>
                    <?php endif; ?>

                        <div class="pull-left description">
                            <p>
                                <?php if(the_field('ticket_short_description')):?><?php the_field('ticket_short_description'); ?><?php endif;?> <a href="<? the_permalink(); ?>" class="single-page-link">MORE INFO</a>
                            </p>
                        </div>
                        <!-- /.description -->

                </div>
                <!-- /.item-content -->
            </div>
            <!-- /.ticket-item-maincontent -->
        </div>
        <!-- /.ticket -->
        <?php endwhile; ?>
    </div>
    <!-- /.tickets-list -->
</div>
<!-- /.tickets-listing-container -->
<?php }