<?php

$lh_quotes = new lh_quotes();

class lh_quotes
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

		add_action( 'admin_menu', array( $this, 'create_admin_pages' ));

		add_action( 'add_meta_boxes_lh_quotes', array( $this, 'add_metaboxes' ));




		add_filter( 'manage_lh_quotes_posts_columns', array( $this, 'my_custom_post_columns' ), 10, 2 );
		add_action('manage_lh_quotes_posts_custom_column', array($this, 'my_custom_post_content'), 10, 2);

		// Add 'Instructions' title to the text editor for projects
		//add_action( 'edit_form_after_title', array($this, 'myprefix_edit_form_after_title') );


		// Add Default order of DATE to the project list edit table
		//add_filter('pre_get_posts', array($this, 'peer_projects_default_order'));

		// Save additional project meta for the custom post
		add_action( 'save_post', array($this, 'save_meta' ));

        add_filter( 'default_content', array($this, 'default_quote_content' ), 10, 2 );

	}


/*	---------------------------
	ADMIN-SIDE MENU / SCRIPTS
	--------------------------- */
	function create_CPTs ()
	{


        $singular = 'Quote';
        $plural = 'Quotes';

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
           'menu_icon' => 'dashicons-media-spreadsheet',
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
           'supports'           => array( 'title', 'revisions', 'editor', 'thumbnail' )

        );


        register_post_type( 'lh_quotes', $args );
	}

	function create_admin_pages()
	{
		$parent_slug = "no_parent";
		$page_title="Quotes";
		$menu_title="";
		$menu_slug="lh-quotes";
		$function=  array( $this, 'draw_quote_page' );
		$myCapability = "edit_others_pages";
		add_submenu_page($parent_slug, $page_title, $menu_title, $myCapability, $menu_slug, $function);

        /* Groups CSV Edit Page */
        $parent_slug = "no_parent";
        $page_title="Quote Preview";
        $menu_title="";
        $menu_slug="quote-preview";
        $function=  array( $this, 'draw_quote_preview_page' );
        $myCapability = "edit_others_pages";
        add_submenu_page($parent_slug, $page_title, $menu_title, $myCapability, $menu_slug, $function);

	}


	function draw_quote_page()
	{
		include_once( LH_PLUGIN_PATH . '/admin/quotes.php' );
	}

    function draw_quote_preview_page()
    {
        include_once( LH_PLUGIN_PATH . '/admin/quote-preview.php' );
    }



	// Register the metaboxes on projects CPT
	function add_metaboxes ()
	{

        // Draw the preview quote box ONLY if its been saved

        global $post;

        if($post->post_status=="publish")
        {

            $id 			= 'quote_preview';
            $title 			= 'Preview / Send Quote';
            $drawCallback 	= array( $this, 'draw_quote_preview' );
            $screen 		= 'lh_quotes';
            $context 		= 'side';
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

        $id 			= 'quote_status';
        $title 			= 'Quote Status';
        $drawCallback 	= array( $this, 'draw_metabox_quote_status' );
        $screen 		= 'lh_quotes';
        $context 		= 'side';
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

		$id 			= 'clients_meta';
		$title 			= 'Client';
		$drawCallback 	= array( $this, 'draw_metabox_client_info' );
		$screen 		= 'lh_quotes';
		$context 		= 'side';
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


		$id 			= 'quote_meta';
		$title 			= 'Quote breakdown';
		$drawCallback 	= array( $this, 'draw_metabox_quote_meta' );
		$screen 		= 'lh_quotes';
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

    function draw_metabox_quote_meta($post, $metabox)
    {
        //add wp nonce field
        wp_nonce_field( 'save_metabox_lh_quote', 'metabox_lh_quote' );

        $post_id = $post->ID;

        // get the saved quote breakdown
        $quote_breakdown = get_post_meta($post_id,'quote_breakdown',true);
        $quote_total = get_post_meta($post_id,'quote_total',true);

        $default_wood = lh_queries::get_quote_items();

        echo '<div id="lh_quote_calculator">';
        echo '<table>';
        echo '<tr><th>Item</th><th>Quantity</th><th></th><th>Subtotal</th></tr>';
        foreach ($default_wood as $id => $quote_meta)
        {

            $unit = $quote_meta['unit'];
            $cost = $quote_meta['cost'];
            $this_value = 0;

            if(isset($quote_breakdown[$id] ) )
            {
                $this_value = $quote_breakdown[$id];
            }

            echo '<tr>';
            echo '<td>'.$quote_meta['name'].'</td>';
            echo '<td><input value="'.$this_value.'" name="'.$id.'" value="" id="'.$id.'" size="5" data-unit="'.$unit.'" data-cost="'.$cost.'"/></td>';
            echo '<td>';
            if($unit=="fixed")
            {

            }
            else
            {
                if($unit=="each")
                {
                    echo '£'.$cost.' each';
                }
                elseif($unit=="sqm")
                {
                    echo '£'.$cost.' / sqm';
                }
            }
            echo '</td>';
            echo '<td><span id="subtotal_'.$id.'" class="quote_subtotal">-</td>';

            echo '</tr>';
        }

        echo '<tr><td>Total Price</td><td></td><td></td><td><span class="quote_total">£<input type="text" readonly id="quote_total" name="quote_total" value="'.$quote_total.'"></span></td></tr>';
        echo '</table>';
        echo '</div>';


    }

    function draw_metabox_quote_status($post, $metabox)
    {
        $post_id = $post->ID;

        $quote_status = get_post_meta($post_id,'quote_status',true);
        if($quote_status==""){$quote_status=="not_sent";}

        $status_array = array(
            "not_sent" => "Not yet sent",
            "pending" => "Sent, pending response",
            "accepted" => "Accepted",
        );

        echo '<select name="post_status" id="post_status">';
        foreach ($status_array as $key => $value)
        {
            echo '<option value="'.$key.'"';
            if($quote_status==$key){echo ' selected ';}
            echo '>';
            echo $value.'</option>';
        }
        echo '</select>';
    }



	function draw_metabox_client_info($post, $metabox)
	{
        $post_id = $post->ID;

        if(isset($_GET['client_id']) )
        {
            $client_id = $_GET['client_id'];
        }
        else
        {
            $client_id = get_post_meta($post_id,'client_id',true);
        }

        $address1 = get_post_meta($client_id,'address1',true);
        $address2 = get_post_meta($client_id,'address2',true);
        $town = get_post_meta($client_id,'town',true);
        $postcode = get_post_meta($client_id,'postcode',true);
        $email = get_post_meta($client_id,'postcode',true);
        $phone = get_post_meta($client_id,'phone',true);

        $client_name = get_the_title($client_id);

        echo '<strong>Name : '.$client_name.'</strong><br/>';
        echo 'Address 1 : '.$address1.'<br/>';
        echo 'Address 2 : '.$address2.'<br/>';
        echo 'Postcode : '.$postcode.'<br/>';
        echo 'Email : '.$email.'<br/>';
        echo 'Phone : '.$phone.'<br/>';

        echo '<input type="hidden" value = "'.$client_id.'" name="client_id" />';
	}

    function draw_quote_preview($post, $metabox)
    {
        $post_id = $post->ID;

        echo '<a href="options.php?page=quote-preview&id='.$post_id.'" class="button-primary">Preview / send quote</a>';




    }





	// Save metabox data on edit slide
	function save_meta ( $post_id )
	{

        // Check if nonce is set.
        if ( ! isset( $_POST['metabox_lh_quote'] ) ) {
            return;
        }

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['metabox_lh_quote'], 'save_metabox_lh_quote' ) ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // If the post key contains the quote_element add it to the array to save
        $quote_breakdown = array();
        foreach ($_POST as $KEY => $VALUE)
        {
            if(strpos($KEY, "quote_element") !== false){
                $quote_breakdown[$KEY] = $VALUE;
            }
        }

        update_post_meta( $post_id, 'quote_breakdown', $quote_breakdown );

        $quote_total = $_POST['quote_total'];
        update_post_meta( $post_id, 'quote_total', $quote_total );

        // Save the client ID
        $client_id = $_POST['client_id'];
        update_post_meta( $post_id, 'client_id', $client_id );

        // Update quote status
        $quote_status = $_POST['quote_status'];
        update_post_meta( $post_id, 'quote_status', $quote_status );

        // Finally see if there is a descret key - if not create one
        $secret =  get_post_meta($post_id,'secret',true);


        if($secret=="")
        {
            $new_secret = lh_crm_utils::generate_secret();
            update_post_meta( $post_id, 'secret', $new_secret );
        }




	}



	function my_custom_post_columns( $columns )
	{

        unset($columns['date']);

        $columns['client'] = 'Clients';
        $columns['date'] = 'Date';

        return $columns;
	}



	// Content of the custom columns for Topics Page
	function my_custom_post_content($column_name, $post_ID)
	{
		switch ($column_name)
		{
			case "client":
            {
                echo 'Get client';
            }
		}
	}



    // Prepopulate content of the quote PDF
    function default_quote_content( $content, $post )
    {


        if ($post->post_type !== 'lh_quotes')
        {
            return $content;
        }


        if(!isset($_GET['client_id']) )
        {
            return $content;
        }

        $client_id = $_GET['client_id'];


        $client_name = get_the_title($client_id);
        $arr = explode(' ',trim($client_name));
        $first_name =  $arr[0]; // get the first name

        $content = 'Dear '.$first_name.',<br/>';
        $content.= 'Thanks for your interest in Little House<br/>';
        $content.= 'Please find attached your quote for the XXXX as discussed<br/><br/>';
        $content.= '[lh_image]<br/>';
        $content.= '[lh_quote]<br/><br/>';

        $content.= '<br/>Any questions please let me know.<br/>';
        $content.= 'Best wishes,<br/>';
        $content.= 'Alex Furr';

        return $content;
    }

    // Prepopulate content of the email
    function default_email_content($quote_id)
    {

        $client_info = lh_queries::get_client_from_quote($quote_id);

        $client_fullname = $client_info['name'];
        // Get the first name
        $first_name = lh_crm_utils::get_first_name($client_fullname);

        $content = 'Dear '.$first_name.',<br/>';
        $content.= 'Please find attached your quote for the XXXXX as discussed<br/>';

        $content.= '<br/>Please do not hesitate to get in touch if you have any questions.<br/>';
        $content.= 'Kind regards,<br/>';
        $content.= 'Alex Furr';

        return $content;
    }


} //Close class
?>
