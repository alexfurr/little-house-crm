<?php
$mbm_ajax = new mbm_ajax();
class mbm_ajax
{

	//~~~~~
	public function __construct ()
	{
		$this->addWPActions();
	}


	function addWPActions()
	{

		// Show calendar when clicked next month etc
		add_action( 'wp_ajax_draw_calendar', array($this, 'draw_calendar' ));


	}
	public function draw_calendar()
	{


		// Check the AJAX nonce
		check_ajax_referer( 'mbm_ajax_nonce', 'security' );

		$month = $_POST['month'];
		$year = $_POST['year'];
		$cal = mbm_calendar::draw_calendar($month, $year);
		echo $cal;

		die();
	}




} // End Class
?>
