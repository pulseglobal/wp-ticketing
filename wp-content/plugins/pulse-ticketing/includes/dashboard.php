<?php
//delete_option('pulse_ticketing_tickets');
//delete_option('pulse_ticketing_updated');
//exit(0);
$apiKey = get_option('pulse_ticketing_api_key');
$apiEndpoint = get_option('pulse_ticketing_endpoint');
$eventId = (int)get_option('pulse_ticketing_event_id');

if (!empty($_POST['action']) && $_POST['action'] == 'retrieve') {
	try {
		$tickets = WP_Pulseradio_Ticketing::retrieveTickets();
	} catch (Exception $e) {
	    $message = array(
			'class' => 'error',
			'text' => "<p><strong style='color: red;'>Issues connecting to API:</strong> " . print_r($e->getMessage(), true) . "</p>"
		);
	}

	$message = array(
		'class' => 'notice updated',
		'text' => "<p><strong>Successfully retrieved tickets.</strong></p>"
	);
}

$tickets = get_option('pulse_ticketing_tickets');
$updated = get_option('pulse_ticketing_updated');
?><div class="wrap">
	<h1><?=__('Pulse Ticketing Synchronization'); ?></h1>

    <?php if (empty($apiEndpoint) || empty($apiKey) || empty($eventId)): ?>
        <div id="setting-error-settings_updated" class="settings-error error">
            <p><strong>Plugin not yet set up, please go to Settings and define the API info and Event ID.</strong></p>
        </div>
    <?php else: ?>
	    <?php if (!empty($message)): ?>
            <div id="setting-error-settings_updated" class="settings-error <?php echo $message['class']; ?>">
                <p><strong><?php echo $message['text']; ?></strong></p>
            </div>
	    <?php endif; ?>

	    <?php if (empty($tickets)): ?>
            <div id="setting-error-settings_updated" class="settings-error error">
                <p><strong>There are currently no available tickets stored.</strong></p>
            </div>
        <?php else: ?>
            <p>Locally stored tickets:</p>
            <table cellpadding="2" cellspacing="4">
                <thead>
                    <tr>
                        <th style="border-bottom: 1px solid silver;">ID</th>
                        <th style="border-bottom: 1px solid silver;">Ticket Name</th>
                        <th style="border-bottom: 1px solid silver;">Unit Price</th>
                        <th style="border-bottom: 1px solid silver;">Booking Fee</th>
                        <th style="border-bottom: 1px solid silver;">Ticket Total</th>
                        <th style="border-bottom: 1px solid silver;">Availability</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td><?=$ticket['id']; ?></td>
                            <td><?=$ticket['name']; ?></td>
                            <td style="text-align: right;"><?=$ticket['currency']; ?><?=number_format($ticket['base_price'],2); ?></td>
                            <td style="text-align: right;"><?=$ticket['currency']; ?><?=number_format($ticket['fee'],2); ?></td>
                            <td style="text-align: right;"><?=$ticket['currency']; ?><?=number_format($ticket['total'],2); ?></td>
                            <td>
                                <?php if(!empty($ticket['sold_out'])): ?>
                                    <strong><?=$ticket['sold_out_message']; ?></strong>
                                <?php else: ?>
                                    max. <?=max($ticket['quantity']); ?> tickets/order
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
	    <?php endif; ?>

        <p>Last retrieve date:
            <?php if (!empty($updated)): ?>
                <strong><?=date('j M, Y \a\t H:i:s', $updated); ?></strong>
                <br/>Tickets are automatically retrieved every minute. <br/>You can manually retrieve them using the button below.
            <?php else: ?>
                <em>never</em>

                <p>Feel free to check out the <a href="<?=admin_url('admin.php?page=pulse-ticketing-help'); ?>">PulseRadio Ticketing Intro</a> section to help you get started.</p>
            <?php endif; ?>
        </p>

        <form name="pulse-ticketing-form" method="post" action="">
            <p class="submit">
                <input type="hidden" name="action" value="retrieve" />
                <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Sync Tickets') ?>" />
            </p>
        </form>
    <?php endif; ?>

</div>
