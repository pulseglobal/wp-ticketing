<?


function tickets_cpt() {

	$labels = array(
		'name'                  => _x( 'Tickets', 'Post Type General Name', 'festival' ),
		'singular_name'         => _x( 'Tickets', 'Post Type Singular Name', 'festival' ),
		'menu_name'             => __( 'Tickets', 'festival' ),
		'name_admin_bar'        => __( 'Tickets', 'festival' ),
		'archives'              => __( 'Tickets', 'festival' ),
		'attributes'            => __( 'Item Attributes', 'festival' ),
		'parent_item_colon'     => __( 'Parent Item:', 'festival' ),
		'all_items'             => __( 'All Items', 'festival' ),
		'add_new_item'          => __( 'Add New Item', 'festival' ),
		'add_new'               => __( 'Add New', 'festival' ),
		'new_item'              => __( 'New Item', 'festival' ),
		'edit_item'             => __( 'Edit Item', 'festival' ),
		'update_item'           => __( 'Update Item', 'festival' ),
		'view_item'             => __( 'View Item', 'festival' ),
		'view_items'            => __( 'View Items', 'festival' ),
		'search_items'          => __( 'Search Item', 'festival' ),
		'not_found'             => __( 'Not found', 'festival' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'festival' ),
		'featured_image'        => __( 'Featured Image', 'festival' ),
		'set_featured_image'    => __( 'Set featured image', 'festival' ),
		'remove_featured_image' => __( 'Remove featured image', 'festival' ),
		'use_featured_image'    => __( 'Use as featured image', 'festival' ),
		'insert_into_item'      => __( 'Insert into item', 'festival' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'festival' ),
		'items_list'            => __( 'Items list', 'festival' ),
		'items_list_navigation' => __( 'Items list navigation', 'festival' ),
		'filter_items_list'     => __( 'Filter items list', 'festival' ),
	);
	$rewrite = array(
		'slug'                  => 'tickets',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( 'Tickets', 'festival' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats', ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
        'menu_icon'             => 'dashicons-format-aside',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
	);
	register_post_type( 'tickets', $args );

}
add_action( 'init', 'tickets_cpt', 0 );
