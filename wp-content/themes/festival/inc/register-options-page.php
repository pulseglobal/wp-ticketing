<?php
if( function_exists('acf_add_options_page') ) {
	$option_page = acf_add_options_page(array(
        'page_title' 	=> 'Header General Settings',
        'menu_title' 	=> 'Header Settings',
        'menu_slug' 	=> 'header-general-settings',
        'capability' 	=> 'edit_posts',
        'redirect' 	=> false
    ));

    $option_page = acf_add_options_page(array(
        'page_title' 	=> 'Tickets options',
        'menu_title' 	=> 'Tickets options',
        'menu_slug' 	=> 'tickets-settings',
        'capability' 	=> 'edit_posts',
        'redirect' 	=> false
    ));
}
