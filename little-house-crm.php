<?php
/*
Plugin Name: 	Little House CRM
Description: 	Client and invoice manager
Version: 		0.1.1
*/


if ( ! defined( 'ABSPATH' ) ) { // Prevent direct access
	die();
}


define( 'LH_PLUGIN_URL', plugins_url('little-house-crm' , dirname( __FILE__ )) );
define( 'LH_PLUGIN_PATH', plugin_dir_path(__FILE__) );

//define( 'IMPERIAL_NETWORK_PLUGIN_URL', plugins_url('form2-dashboard' , dirname( __FILE__ )) );



include_once( LH_PLUGIN_PATH . '/classes/class-wp.php' );
include_once( LH_PLUGIN_PATH . '/classes/class-clients-cpt.php' );
include_once( LH_PLUGIN_PATH . '/classes/class-quotes-cpt.php' );
include_once( LH_PLUGIN_PATH . '/classes/class-draw.php' );
include_once( LH_PLUGIN_PATH . '/classes/class-queries.php' );
include_once( LH_PLUGIN_PATH . '/classes/class-actions.php' );
include_once( LH_PLUGIN_PATH . '/classes/class-utils.php' );
include_once( LH_PLUGIN_PATH . '/classes/class-pdf.php' );
include_once( LH_PLUGIN_PATH . '/functions.php' );

// TCPDF Library


if (!defined("PDF_CREATOR") )
{



	include_once( LH_PLUGIN_PATH . '/lib/tcpdf/config/tcpdf_config.php' );
	include_once( LH_PLUGIN_PATH . '/lib/tcpdf/tcpdf.php' );

}

// Extension class for TCPDF Library
include_once( LH_PLUGIN_PATH . '/classes/class-extend-tcpdf.php' );


?>
