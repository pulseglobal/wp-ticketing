<?php

if (! function_exists( 'curl_init' ) ) {
	esc_html_e('This plugin requires the CURL PHP extension');
	return false;
}

if (! function_exists( 'json_decode' ) ) {
	esc_html_e('This plugin requires the JSON PHP extension');
	return false;
}

if (! function_exists( 'http_build_query' )) {
	esc_html_e('This plugin requires http_build_query()');
	return false;
}

if (!empty($_POST)) {
    $prefix = 'pulse_ticketing_';
    foreach($_POST as $key => $value) {
        if (substr($key,0, strlen($prefix)) == $prefix) {
	        update_option( $key, $value );
        }
	    $message = array(
	        'text' => 'Settings saved successfully.'
        );
    }
}

?>
<div class="wrap">
	<h1><?=__('Pulse Ticketing Settings'); ?></h1>

    <?php if (!empty($message)): ?>
        <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
            <p><strong><?php echo $message['text']; ?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
        </div>
    <?php endif; ?>

	<form name="pulse-ticketing-form" method="post" action="">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="api_endpoint"><?php _e('API Endpoint', 'pulse-ticketing' ); ?></label></th>
                    <td>
                        <input type="text" class="regular-text code" name="pulse_ticketing_endpoint" id="api_endpoint" value="<?=get_option('pulse_ticketing_endpoint'); ?>" size="30" placeholder="http://test1.pulseradio.net/api" />
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="api_key"><?php _e('API Key', 'pulse-ticketing' ); ?></label></th>
                    <td>
                        <input type="text" class="regular-text code" name="pulse_ticketing_api_key" id="api_key" value="<?=get_option('pulse_ticketing_api_key'); ?>" size="20" placeholder="YOUR_API_KEY_KERE" />
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="api_endpoint"><?php _e('Pulse Event ID', 'pulse-ticketing' ); ?></label></th>
                    <td>
                        <input type="text" class="regular-text code" name="pulse_ticketing_event_id" id="api_endpoint" value="<?=get_option('pulse_ticketing_event_id'); ?>" size="8" placeholder="Event ID on Pulseradio.net" />
                    </td>
                </tr>
            </tbody>
        </table>

        <hr />

		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
		</p>
	</form>

    <h2>Connection Status</h2>
    <?php
    try {
	    $api = new PulseRadioApi(get_option('pulse_ticketing_api_key'), get_option('pulse_ticketing_endpoint'));
	    $tickets = $api->getTickets((int)get_option('pulse_ticketing_event_id'));

	    if (!empty($tickets['authentication'])) {
		    echo "<p><strong style='color: red;'>Authentication failed:</strong> {$tickets['authentication']}. <br/>Make sure the API Key above is correct and active within Pulseradio systems</p>";
	    } else if (!is_array($tickets)) {
		    echo "<p><strong>Connection successful:</strong> $tickets</p>";
	    } else {
		    echo "<p><strong>Connection successful. </strong>Tickets available from Pulseradio:<br><ol>";
		    foreach ($tickets as $ticket) {
			    echo sprintf("<li>ID <code>%d</code>: <strong>%s</strong> (%s%.2f) - %s</li>", $ticket['id'], $ticket['name'], $ticket['currency'],
				    $ticket['total'], !empty($ticket['quantity']) ? 'Quantities: ' . implode(', ', $ticket['quantity']) : $ticket['sold_out_message']);
		    }
		    echo "</ol></p>";
	    }
    } catch (Exception $e) {
        echo "<p><strong style='color: red;'>Issues connecting to API:</strong> "; print_r($e->getMessage()); echo "</p>";
    }

     ?>
</div>