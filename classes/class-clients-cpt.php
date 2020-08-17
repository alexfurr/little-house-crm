<?php

$lh_clients = new lh_clients();

class lh_clients
{



	//~~~~~
	function __construct ()
	{
		$this->addWPActions();
	}


/*	---------------------------
	PRIMARY HOOKS INTO WP
	--------------------------- */
	function addWPActions ()
	{
		//Admin Menu
		add_action( 'init',  array( $this, 'create_CPTs' ) );

		//add_action( 'admin_menu', array( $this, 'create_admin_pages' ));

		add_action( 'add_meta_boxes_lh_clients', array( $this, 'add_metaboxes' ));


		add_filter( 'manage_lh_clients_posts_columns', array( $this, 'my_custom_post_columns' ), 10, 2 );
		add_action('manage_lh_clients_posts_custom_column', array($this, 'my_custom_post_content'), 10, 2);

		// Add 'Instructions' title to the text editor for projects
		//add_action( 'edit_form_after_title', array($this, 'myprefix_edit_form_after_title') );


		// Add Default order of DATE to the project list edit table
		//add_filter('pre_get_posts', array($this, 'peer_projects_default_order'));

		// Save additional project meta for the custom post
		add_action( 'save_post', array($this, 'save_meta' ));



	}


/*	---------------------------
	ADMIN-SIDE MENU / SCRIPTS
	--------------------------- */
	function create_CPTs ()
	{


        $singular = 'Client';
        $plural = 'Clients';

        //Topics
        $labels = array(
           'name'               =>  $plural,
           'singular_name'      =>  $singular,
           'menu_name'          =>  $plural,
           'name_admin_bar'     =>  $plural,
           'add_new'            =>  'Add New '.$singular,
           'add_new_item'       =>  'Add New '.$singular,
           'new_item'           =>  'New '.$singular,
           'edit_item'          =>  'Edit '.$singular,
           'view_item'          => 'View '.$plural,
           'all_items'          => 'All '.$plural,
           'search_items'       => 'Search '.$plural,
           'parent_item_colon'  => '',
           'not_found'          => 'No '.$plural.' found.',
           'not_found_in_trash' => 'No '.$plural.' found in Trash.'
        );

        $args = array(
           'menu_icon' => 'dashicons-groups',
           'labels'             => $labels,
           'public'             => true,
           'publicly_queryable' => true,
           'show_ui'            => true,
           'show_in_nav_menus'	 => true,
           'show_in_menu'       => true,
           'query_var'          => true,
           'rewrite' => array( 'slug' => 'clients' ),
           'capability_type'    => 'page',
           'has_archive'        => false,
           'hierarchical'       => false,
           'supports'           => array( 'title' )

        );



        register_post_type( 'lh_clients', $args );
	}

	function create_admin_pages()
	{
		/* Groups CSV Edit Page */
		$parent_slug = "no_parent";
		$page_title="Quotes";
		$menu_title="";
		$menu_slug="lh-quotes";
		$function=  array( $this, 'draw_quote_page' );
		$myCapability = "edit_others_pages";
		add_submenu_page($parent_slug, $page_title, $menu_title, $myCapability, $menu_slug, $function);

	}


	function draw_quote_page()
	{
		include_once( LH_PLUGIN_PATH . '/admin/quotes.php' );
	}



	// Register the metaboxes on projects CPT
	function add_metaboxes ()
	{

		//Project Settings Metabox
		$id 			= 'clients_meta';
		$title 			= 'Contact Information';
		$drawCallback 	= array( $this, 'draw_metabox_contact_info' );
		$screen 		= 'lh_clients';
		$context 		= 'normal';
		$priority 		= 'default';
		$callbackArgs 	= array();

		add_meta_box(
			$id,
			$title,
			$drawCallback,
			$screen,
			$context,
			$priority,
			$callbackArgs
		);

	}




	function draw_metabox_contact_info($post, $metabox)
	{

		//add wp nonce field
		wp_nonce_field( 'save_metabox_lh_clients', 'metabox_lh_clients' );

        $client_id = $post->ID;

		// GEt the post meta
        $address1 = get_post_meta($client_id,'address1',true);
        $address2 = get_post_meta($client_id,'address2',true);
        $town = get_post_meta($client_id,'town',true);
        $postcode = get_post_meta($client_id,'postcode',true);
        $email = get_post_meta($client_id,'email',true);
        $phone = get_post_meta($client_id,'phone',true);


		echo '<h3><label for="address1">Address line 1</label></h3>';
		echo '<input type="text" id="address1" name="address1" value = "'.$address1.'" />';

        echo '<h3><label for="address2">Address line 2</label></h3>';
        echo '<input type="text" id="address2" name="address2" value = "'.$address2.'" />';

        echo '<h3><label for="town">Town / City</label></h3>';
        echo '<input type="text" id="town" name="town" value = "'.$town.'" />';


        echo '<h3><label for="postcode">Postcode</label></h3>';
        echo '<input type="text" id="postcode" name="postcode" value = "'.$postcode.'" />';

        echo '<h3><label for="email">Email</label></h3>';
        echo '<input type="text" id="email" name="email" value = "'.$email.'" />';

        echo '<h3><label for="email">Phone</label></h3>';
        echo '<input type="text" id="phone" name="phone" value = "'.$phone.'" />';
	}

	// Save metabox data on edit slide
	function save_meta ( $postID )
	{

        // Check if nonce is set.
        if ( ! isset( $_POST['metabox_lh_clients'] ) ) {
            return;
        }

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['metabox_lh_clients'], 'save_metabox_lh_clients' ) ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $postID ) ) {
            return;
        }

        $address1 	= isset( $_POST['address1'] ) ? $_POST['address1'] : '';
        $address2 	= isset( $_POST['address2'] ) ? $_POST['address2'] : '';
        $postcode 	= isset( $_POST['postcode'] ) ? $_POST['postcode'] : '';
        $email 	= isset( $_POST['email'] ) ? $_POST['email'] : '';
        $phone 	= isset( $_POST['phone'] ) ? $_POST['phone'] : '';
        $town 	= isset( $_POST['town'] ) ? $_POST['town'] : '';

        update_post_meta( $postID, 'address1', $address1 );
        update_post_meta( $postID, 'address2', $address2 );
        update_post_meta( $postID, 'postcode', $postcode );
        update_post_meta( $postID, 'email', $email );
        update_post_meta( $postID, 'phone', $phone );
        update_post_meta( $postID, 'town', $town );

	}



	function my_custom_post_columns( $columns )
	{

        unset($columns['date']);

        $columns['quotes'] = 'Quotes';
        $columns['date'] = 'Date';

        return $columns;
	}



	// Content of the custom columns for Topics Page
	function my_custom_post_content($column_name, $post_ID)
	{
		switch ($column_name)
		{
			case "quotes":
            {

                $client_name = get_the_title($post_ID);
                echo '<a href="options.php?page=lh-quotes&client-id='.$post_ID.'" class="button-primary">View / create quotes</a><br/>';
                //echo '<a class="button-primary" href="post-new.php?post_type=lh_quotes&client_id='.$post_ID.'&post_title=Quote for '.$client_name.'">Create quote</a>';
            }
		}
	}

} //Close class
?>
