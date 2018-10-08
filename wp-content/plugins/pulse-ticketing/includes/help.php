<div class="wrap">
	<h1><?=__('About Pulse Ticketing'); ?></h1>

    <p>This Plugin allows a WordPress site to be integrated with pulseradio.net's ticket acquisition services.</p>
    <p>To get started with selling tickets on your WordPress site, follow these simple steps:</p>

    <h3>1. Sync tickets with pulseradio.net</h3>
    <p>Navigate to the <a href="<?=admin_url('admin.php?page=pulse-ticketing-settings'); ?>">Pulse-Ticketing Settings</a> page and set the information required:</p>
    <ul>
        <li>- <strong>API Endpoint</strong>: This is pulseradio.net API endpoint.
            Typically <code>http://test1.pulseradio.net/api</code> for testing purposes and
            <code>http://pulseradio.net/api</code> when in production.</li>
        <li>- <strong>API Key</strong>: Pulseradio.net will provide you with this key.</li>
        <li>- <strong>Event ID</strong>: The ID of the Event you want to sell tickets for.</li>
    </ul>

    <p>After entering the info above, click <strong>Save Changes</strong>. After the page reloads,
    you can see if the connection is working and what tickets are available over the API:</p>

    <p><img src="<?=plugins_url( '../images/help_settings.jpg', __FILE__); ?>" style="width: 90%; max-width: 640px;"></p>

    Once you make sure tickets are available, just head out to the <strong>Sync Tickets</strong> page:

    <p><img src="<?=plugins_url( '../images/help_sync_empty.jpg', __FILE__); ?>" style="width: 90%; max-width: 640px;"></p>

    And click the <strong>Sync Tickets</strong> button. Tickets for the event ID set up in Settings will be pulled in:

    <p><img src="<?=plugins_url( '../images/help_sync_success.jpg', __FILE__); ?>" style="width: 90%; max-width: 640px;"></p>

    <h3>2. Set ticket types on the actual Tickets</h3>

    <p>From the Admin sidebar, click on <strong>Tickets</strong> -> <strong>All Tickets</strong> (or Add New).</p>
    <p>On the desired ticket, scroll down to the <strong>Ticket Fields</strong> / <strong>Ticket Type</strong> dropdown
    and select the Pulseradio ticket equivalent for the ticket you're currently editing.</p>

    <p>Click <strong>Update</strong> to save the ticket. Repeat step 2. for all the ticket types that you wish to sell
    on your site.</p>

    <h3>3. That's it!</h3>

    <p>Now, the Tickets page on your site will have a Quantity drop-down, price (with Booking Fees),
        and <strong>Add To Cart</strong>
    button for each ticket that you've set up this way:</p>

    <p><img src="<?=plugins_url( '../images/help_tickets.jpg', __FILE__); ?>" style="width: 90%; max-width: 640px;"></p>

    <h3>NOTES</h3>

    <p>Tickets are being synced every minute as long as there are visitors on your website. So whenever a ticket's price
    or availability changes, it will get updated on your site in about one minute, unless you manually open up the
    Sync Tickets page and click the <strong>Sync Tickets</strong> button.</p>
</div>