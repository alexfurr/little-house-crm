<?php
$lh_crm = new lh_crm();
class lh_crm
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
        add_filter( 'gettext', array($this, 'change_publish_button'), 10, 2 );
        add_action('init', array($this, 'check_for_actions') );
        add_filter( 'single_template', array($this, 'load_my_custom_template'), 50, 1 );

        add_action( 'wp_enqueue_scripts', array( $this, 'load_frontend_scripts' ), 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );






	}

    // Loads the quote js for the quote calculator
    function load_frontend_scripts() {
        wp_enqueue_script( 'jquery' );


        wp_enqueue_style('lh_quotes_css', plugins_url('../css/lh-crm.css',__FILE__) );
        wp_enqueue_script('lh_quote_template_js', plugins_url('../js/quote-template.js',__FILE__) ); #

    }

    function load_admin_scripts( ) {

        wp_enqueue_style('lh_quotes_css', plugins_url('../css/lh-crm.css',__FILE__) );
        wp_enqueue_script('lh_quotes_js', plugins_url('../js/quote.js',__FILE__) ); #
        wp_enqueue_style( 'imperial-font-awesome', '//use.fontawesome.com/releases/v5.2.0/css/all.css' );



    }

    // Load a custom template for the quotes CPT
    function load_my_custom_template( $template )
    {


        if ( is_singular( 'lh_quotes' ) ) {
            $template = LH_PLUGIN_PATH.'/templates/quote_template.php';
        }

        return $template;
    }


    function change_publish_button( $translation, $text ) {

        if ( $text == 'Publish' )
        { // Your button text
            $text = 'Save';
        }

        return $text;
    }


    public static function check_for_actions()
    {
        if(isset($_GET['action']) )
        {

            $myAction = $_GET['action'];

            $home_url = get_site_url();

            switch ($myAction)
            {
                case "send_quote":
                    $quote_id = $_GET['id'];

                    lh_actions::send_quote($quote_id);
                    $redirectURL = $home_url.'/wp-admin/options.php?page=quote-preview&feedback=quote_sent&id='.$quote_id;
                    wp_redirect($redirectURL);
                    exit();

                break;

                case "edit_timeline_item":

                    $client_id = $_GET['client-id'];
                    $feedback = lh_actions::process_timeline_item();

                    $redirectURL = $home_url.'/wp-admin/options.php?page=client-activity&feedback='.$feedback.'&client-id='.$client_id;
                    wp_redirect($redirectURL);


                    exit();

                break;
            }

        }


     }


}
?>
