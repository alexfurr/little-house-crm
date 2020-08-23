<?php

$lh_database = new lh_database();

class lh_database
{
	var $DBversion 		= '1.2';

	//~~~~~
	function __construct ()
	{
        add_action( 'init',  array( $this, 'checkCompat' ) );

        global $wpdb;
        global $lh_activity_db;


        $lh_activity_db = $wpdb->prefix . 'lh_activity';

	}

	//~~~~~
	function checkCompat ()
	{

		// Get the Current DB and check against this verion
		$currentDBversion = get_option('lh_db_version');
		$thisDBversion = $this->DBversion;


		if($thisDBversion>$currentDBversion)
		{

			$this->createTables();
			update_option('lh_db_version', $thisDBversion);
		}
		//$this->createTables();
	}



	function createTables ()
	{


        global $wpdb;
        global $lh_activity_db;

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$WPversion = substr( get_bloginfo('version'), 0, 3);
		$charset_collate = ( $WPversion >= 3.5 ) ? $wpdb->get_charset_collate() : $this->getCharsetCollate();

		//users table
		$sql = "CREATE TABLE $lh_activity_db (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
            client_id int,
            activity_title varchar(255),
			activity_content longtext,
            activity_date datetime,
            project_id int,
			INDEX client_id (client_id),
			PRIMARY KEY (id)

		) $charset_collate;";

		$feedback = dbDelta( $sql );

	}


	function getCharsetCollate ()
	{
		global $wpdb;
		$charset_collate = '';
		if ( ! empty( $wpdb->charset ) )
		{
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) )
		{
			$charset_collate .= " COLLATE $wpdb->collate";
		}
		return $charset_collate;
	}

}



?>
