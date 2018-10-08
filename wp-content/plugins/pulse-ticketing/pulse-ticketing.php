<?php
/**
 * @package Pulse_Ticketing
 * @version 1.0
 */
/*
Plugin Name: Pulseradio Ticketing
Plugin URI: http://pulseradio.net
Description: This is a ticketing integration for Wordpress websites, that pulls ticketing information from Pulseradio.net. It also handles ticketing acquisitions by leveraging the Pulseradio Payments API.
Author: Pulseradio Pty
Version: 1.0
Author URI: http://pulseradio.net/
*/

require_once dirname(__FILE__) . '/includes/PulseRadioApi.php';

function sxml_cdata($path, $string){
  $dom = dom_import_simplexml($path);
  $cdata = $dom->ownerDocument->createCDATASection($string);
  $dom->appendChild($cdata);
}

class WP_Pulseradio_Ticketing {

	// Constructor
	function __construct() {
		add_action( 'admin_menu', array($this, 'add_menu'));
		register_activation_hook( __FILE__, array( $this, 'wpa_install' ) );
		register_deactivation_hook( __FILE__, array( $this, 'wpa_uninstall' ) );

		// @see https://www.advancedcustomfields.com/resources/acfload_field/
		add_filter('acf/load_field/name=ticket_id', array( $this, 'load_tickets_dropdown' ));

		add_action('init', array( $this, 'initializePlugin'), 1);

		wp_enqueue_script( 'cart-ajax-request', plugin_dir_url( __FILE__ ) . 'js/cart.js', array( 'jquery' ) );
		wp_localize_script( 'cart-ajax-request', 'CartAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		add_action( 'wp_ajax_nopriv_ajax-cart-update', array( $this, 'ajax_update_cart') );
		add_action( 'wp_ajax_ajax-cart-update', array( $this, 'ajax_update_cart') );

		add_action( 'wp_ajax_nopriv_ajax-send-order', array( $this, 'ajax_send_order') );
		add_action( 'wp_ajax_ajax-send-order', array( $this, 'ajax_send_order') );

		add_action( 'pt_retrieve_tickets', array($this, 'retrieveTickets') );
	}

	/**
	 * Whatever we need to run to get things going
	 */
	function initializePlugin() {
		if(!session_id()) {
			session_start();
		}

		// sync tickets if last sync longer than 60 seconds ago
		if ($this->isConfigured()) {
			$lastFetched = get_option('pulse_ticketing_updated');
			if (!empty($lastFetched) && (time() - $lastFetched) > 60) {
				wp_schedule_single_event(time(), 'pt_retrieve_tickets');
			}
		}
	}

	/**
	 * Go ahead and fetch the tickets via the API.
	 *
	 * @return array
	 */
	static function retrieveTickets() {
		$api = new PulseRadioApi(get_option('pulse_ticketing_api_key'), get_option('pulse_ticketing_endpoint'));
		$tickets = $api->getTickets((int)get_option('pulse_ticketing_event_id'));
		if (is_array($tickets)) {
			update_option( 'pulse_ticketing_tickets', $tickets );
			update_option( 'pulse_ticketing_updated', time() );
		}
		return $tickets;
	}

	protected function isConfigured() {
		$endpoint = get_option('pulse_ticketing_endpoint');
		$key = get_option('pulse_ticketing_api_key');
		$eventId = get_option('pulse_ticketing_event_id');

		return !empty($endpoint) && !empty($key) && !empty($eventId);
	}

	/*
	  * Actions perform at loading of admin menu
	  */
	function add_menu() {

		add_menu_page( 'Pulseradio Ticketing', 'PulseTicketing', 'manage_options', 'pulse-ticketing-dashboard',
			array( __CLASS__, 'wpa_page_file_path'), plugins_url( 'images/pulse-logo.png', __FILE__ ), '2.2.9'
		);

		add_submenu_page( 'pulse-ticketing-dashboard', 'Pulse Ticketing' . ' Sync',
			'Sync Tickets', 'manage_options', 'pulse-ticketing-dashboard',
			array(__CLASS__, 'display_dashboard')
		);

		add_submenu_page( 'pulse-ticketing-dashboard', 'Pulse Ticketing' . ' Settings',
			'Settings', 'manage_options', 'pulse-ticketing-settings',
			array(__CLASS__, 'display_settings')
		);

		add_submenu_page( 'pulse-ticketing-dashboard', 'Pulse Ticketing' . ' Help',
			'Intro', 'manage_options', 'pulse-ticketing-help',
			array(__CLASS__, 'display_help')
		);

	}

	/**
	 * Render the plugin's intro page
	 */
	function display_help() {
		include(dirname(__FILE__) . '/includes/help.php');
	}

	/*
	 * Render the plugin admin's dashboard
	 */
	function display_dashboard() {
		include(dirname(__FILE__) . '/includes/dashboard.php');
	}

	/*
	 * Render the plugin settings page
	 */
	function display_settings() {
		include(dirname(__FILE__) . '/includes/settings.php');
	}

	/*
	 * Actions perform on activation of plugin
	 */
	function wpa_install() {



	}

	/*
	 * Actions perform on de-activation of plugin
	 */
	function wpa_uninstall() {



	}

	/**
	 * Load the locally stored tickets and replace the field_type
	 * ticket_id with the actual tickets info
	 *
	 * @param $field
	 *
	 * @return mixed
	 */
	function load_tickets_dropdown( $field )
	{
		$dropdown = array();

		$tickets = $this->getTickets();

		if (!empty($tickets)) {
			foreach ($tickets as $ticket) {
				$dropdown[$ticket['id']] = $ticket['name'] . ' (' . $ticket['currency'] . $ticket['total'] . ')';
			}
		}

		$field['choices'] = $dropdown;

		return $field;
	}

	/**
	 * Call that updates the current cart's contents
	 */
	function ajax_update_cart()
	{
		if (empty($_POST['ticketId']) && empty($_POST['quantity'])) {
			// nothing to update, just return the cart
			$this->json(array('cart' => $_SESSION['cart']));
		}

		if (empty($_POST['ticketId'])) {
			$this->json(array('ticketId' => 'This field is required'), 'fail');
		}

		$ticketId = (int)$_POST['ticketId'];
		$quantity = (int)$_POST['quantity'];

		if (!isset($_SESSION['cart'])) {
			$_SESSION['cart'] = array();
		}

		if ($quantity == 0) {
			if (!empty($_SESSION['cart'][$ticketId])) {
				unset($_SESSION['cart'][$ticketId]);
			}
			$this->json(array('cart' => $_SESSION['cart']));
		}

		$tickets = $this->getTickets();
		foreach ($tickets as $ticket) {
			if ($ticket['id'] == $ticketId) {
				$_SESSION['cart'][$ticketId] = $ticket;
				$_SESSION['cart'][$ticketId]['id'] = $ticketId;
				$_SESSION['cart'][$ticketId]['quantity'] = $quantity;
			}
		}

		$this->json(array('cart' => $_SESSION['cart']));
	}

	/**
	 * Send a pending order
	 */
	function ajax_send_order()
	{
		$this->storeOrderInformation($_POST);

		if (empty($_SESSION['cart'])) {
			$this->json(array('validation' => array('cart' => array('You don\'t have any tickets in your cart!'))), 'fail');
		}

		$order = $this->assembleOrder($_POST, $_SESSION['cart']);

		$api = new PulseRadioApi(get_option('pulse_ticketing_api_key'), get_option('pulse_ticketing_endpoint'));
		$result = $api->placeOrder($order);

		if (empty($result)) {
			$this->json('Cannot connect to payment API / Gateway. Please contact support!', 'error');
		}
		if (!empty($result['validation'])) {
			$this->json(array('validation' => $result['validation']), 'fail');
		}

		$_SESSION['placed_order'] = $result;

		$this->json(array('order' => $result));
	}

	/**
	 * Encode $response in JSON format, output it and exit
	 *
	 * @param $response
	 * @param $status
	 */
	protected function json($response, $status = 'success')
	{
		header( "Content-Type: application/json" );
		echo json_encode(array(
			'status' => $status,
			'data'   => $response
		));
		exit;
	}

	/**
	 * Locally store the payment information just in case the user reloads or
	 * navigates away from the checkout page.
	 *
	 * @param $order
	 */
	protected function storeOrderInformation($order)
	{
		if (!empty($order['cvv_number'])) {
			unset($order['cvv_number']);
		}
		if (empty($order['tickets_same_name'])) {
			$order['tickets_same_name'] = 0;
		} else {
			$order['tickets_same_name'] = 1;
		}

		$_SESSION['order'] = $order;
	}

	/**
	 * Get the locally stored tickets
	 *
	 * @return mixed
	 */
	protected function getTickets()
	{
		return get_option('pulse_ticketing_tickets');
	}

	/**
	 * Create a complete, sendable order
	 *
	 * @param array $order
	 * @param array $cart
	 *
	 * @return array
	 */
	protected function assembleOrder($order, $cart)
	{
		$order['event_ids'] = (int)get_option('pulse_ticketing_event_id');
		$order['amount'] = 0.0;
		if (!empty($cart)) {
			foreach ($cart as $ticketId => $ticket) {
				$orderTicket = array(
					'id' => $ticketId,
					'total' => $ticket['total'],
					'quantity' => $ticket['quantity']
				);
				if (empty($order['tickets_same_name'])) {
					$orderTicket['attendee'] = $order['attendee'][$ticketId];
				}
				$order['tickets'][] = $orderTicket;
				$order['amount'] += $ticket['total'] * $ticket['quantity'];
			}
		}
		unset($order['attendee']);
		unset($order['action']);
		return $order;
	}

} // END class

new WP_Pulseradio_Ticketing();
