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
include_once( LH_PLUGIN_PATH . '/classes/class-db.php' );
include_once( LH_PLUGIN_PATH . '/classes/class-calendar.php' );
include_once( LH_PLUGIN_PATH . '/classes/class-ajax.php' );

include_once( LH_PLUGIN_PATH . '/classes/class-php-utils.php' ); // Include this always


include_once( LH_PLUGIN_PATH . '/functions.php' );

// Include the forms library
if (!class_exists('ek_forms'))
{
    include_once( LH_PLUGIN_PATH . '/lib/forms/class-forms.php' );
}

// TCPDF Library
if (!defined("PDF_CREATOR") )
{
	include_once( LH_PLUGIN_PATH . '/lib/tcpdf/config/tcpdf_config.php' );
	include_once( LH_PLUGIN_PATH . '/lib/tcpdf/tcpdf.php' );
}

// Extension class for TCPDF Library
include_once( LH_PLUGIN_PATH . '/classes/class-extend-tcpdf.php' );


// Other defines
define( 'PRIMARY_COLOR', "#acd037" );
define( 'BANK_SC', "30-90-34" );
define( 'BANK_AC_NUMBER', "32156268" );
define( 'BANK_AC_NAME', "Barrington Innovation Ltd" );

define( 'DEPOSIT_DAY_LIMIT', 14 ); // Number of days after accepting the quote required to pay deposit



define( 'LH_EMAIL_ADDRESS', "littlehousebristol@gmail.com" );
define( 'LH_EMAIL_LOGO', "https://littlehousebristol.co.uk/wp-content/uploads/2020/09/logo_small.png" );

define( 'LH_SIGNATURE', '
<span style="color:green; font-weight:bold;">Little House</span> : <span style="color:#ccc">Bespoke Playhouses and Climbing Frames</span><br/>
Web :<a href="https://littlehousebristol.co.uk">littlehousebristol.co.uk</a><br/>
Facebook : <a href="https://facebook.com/littlehousebristol">facebook.com/littlehousebristol</a><br/>
Phone : 07779 606934
Email : <a href="mailto:info@littlehousebristol.co.uk">info@littlehousebristol.co.uk</a><br/>
<img src="https://littlehousebristol.co.uk/wp-content/uploads/2020/09/logo_small.png">' );


?>
