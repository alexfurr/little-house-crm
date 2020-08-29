<?php
$mbm_calendar = new mbm_calendar();

class mbm_calendar
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
        add_action( 'admin_menu', array( $this, 'create_admin_pages' ));

    }



    function create_admin_pages()
    {

        $parent_slug = "edit.php?post_type=lh_clients";
        $page_title="Calendar";
        $menu_title="Calendar";
        $menu_slug="mbm-calendar";
        $function=  array( $this, 'draw_calendar_page' );
        $myCapability = "edit_others_pages";
        add_submenu_page($parent_slug, $page_title, $menu_title, $myCapability, $menu_slug, $function);
    }

    function draw_calendar_page()
    {
        include_once( LH_PLUGIN_PATH . '/admin/calendar.php' );
    }

    public static function get_month ($month_num)
    {
        $date_obj   = DateTime::createFromFormat('!m', $month_num);
        $month_name = $date_obj->format('F'); // March

        return $month_name;
    }


    public static function generate_month_day_array($month, $year)
    {
        // Get the number of days in this month
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if($month<10){$month='0'.$month;} // Format the month if missing a zero

        // Get the first day
        $first_day_date = $year.'-'.$month.'-01';

        $first_day_number = date('N', strtotime($first_day_date));

        // Calculate how many additional blank divs to add based on the first day
        // e.g. A thursday would have three additional divs
        $start_blank_count = $first_day_number-1;
        if($start_blank_count<0){$start_blank_count=0;} // Can't be less than zero!

        //Create the master array
        $month_day_array = array();

        $this_weekday_count=0;
        $current_week=1;
        $temp_week_array = array();

        $i=0;
        while($i<$start_blank_count)
        {
            $temp_week_array[]="";
            $i++;
        }

        // Add any blank divs to the start

        $this_day = 1;
        while($this_day <= $days_in_month)
        {

            // Count the items in the array
            if(count($temp_week_array)>=7)
            {
                $month_day_array[] = $temp_week_array; // Add this week to the array
                $temp_week_array = array(); // Clear the temp array
            }

            $temp_week_array[] = $this_day;
            $this_day++;
        }

        // Check if there are any items left in the temp array and add if not
        if(count($temp_week_array)>=1)
        {
            $daysLeft = 7 - count($temp_week_array);
            $i=0;
            while($i<$daysLeft)
            {
                $temp_week_array[] = "";
                $i++;
            }

            $month_day_array[] = $temp_week_array; // Add this week to the array
        }

        return $month_day_array;
    }

    public static function draw_calendar($month="", $year="")
    {

        $str='';

        if($month==""){$month = date('n');}
        if($year==""){$year = date('Y');}


        $slot_array = array();

        // Get the next month and year
        $next_year = $year;
        $next_month = $month+1;
        $prev_year = $year;
        $prev_month = $month-1;

        if($month==12)
        {
            $next_month=1;
            $next_year = $year+1;
        }
        if($month==1)
        {
            $prev_month=12;
            $prev_year = $year-1;
        }

        $this_month= $month;
        if($month<10){$this_month = '0'.$month;}

        $month_array = mbm_calendar::generate_month_day_array($month, $year);
        $month_name = mbm_calendar::get_month($month);

        $master_items_array = mbm_calendar::get_key_dates();

        /*
        $master_items_array['2020-08-27'][] = array(
            "title" => 'Ian Norton Build starts',
            "class" => 'project_start',
        );
        $master_items_array['2020-08-27'][] = array(
            "title" => 'Check a deposit',
            "class" => 'reminder',
        );

        $master_items_array['2020-08-19'][] = array(
            "title" => 'Dave Stuary start build',
            "class" => 'project_start',
        );
        */




        $str.='<div class="month_wrapper">';
        $str.='<div class="prev_month_link load_cal_month has-click-event" data-method="load_cal_month" data-month="'.$prev_month.'" data-year="'.$prev_year.'"><i class="fas fa-chevron-circle-left"></i></div>';
        $str.='<div class="month_name">';
        $str.='<div class="current_year">'.$year.'</div>';
        $str.='<div class="current_month">'.$month_name.'</div>';
        $str.='</div>';
        $str.='<div class="next_month_link load_cal_month has-click-event" data-method="load_cal_month" data-month="'.$next_month.'" data-year="'.$next_year.'"><i class="fas fa-chevron-circle-right"></i></div>';
        $str.='</div>';

        $day_array = array("Mon", "Tues", "Weds", "Thurs", "Fri", "Sat", "Sun");
        $str.= '<div class="month">';
        $str.='<div class="week day-name">';
        foreach($day_array as $day_name)
        {
            $str.='<div class="day day_name">'.$day_name.'</div>';
        }
        $str.='</div>'; // End of month div


        foreach ($month_array as $week)
        {
            $str.= '<div class="week">';
            foreach ($week as $this_day)
            {

                $this_day_text = $this_day;
                if($this_day<10){$this_day = '0'.$this_day;}

                $this_full_date = $year.'-'.$this_month.'-'.$this_day;


                $str.='<div class="day">';
                $str.='<div class="day_number">'.$this_day_text.'</div>';
                $str.='<div class="day_content">';

                if(array_key_exists($this_full_date, $master_items_array) )
                {
                    $items_array = $master_items_array[$this_full_date];

                    foreach ($items_array as $item_meta)
                    {
                        $title = $item_meta['title'];
                        $class = $item_meta['class'];

                        $str.='<div class="'.$class.'">'.$title.'</div>';

                    }

                }
                $str.='</div>';


                $str.='</div>';
            }
            $str.='</div>'; // End of week
        }
        $str.= '</div>'; // End of Month

        return $str;

    }


    public static function get_key_dates()
    {
        $master_key_dates_array= array();
        //Get all build dates
        $build_dates_array = mbm_calendar::get_all_build_dates();

        foreach ($build_dates_array as $date_meta)
        {
            $build_date = $date_meta['date'];
            $date_type = $date_meta['type'];
            $client_meta = $date_meta['client'];
            $client_name = $client_meta['name'];
            $client_address = $client_meta['address1'].'<br/>'.$client_meta['town'].'<br/>'.$client_meta['postcode'];

            switch ($date_type)
            {
                case "project_start_date":
                    $this_title = '<strong>'.$client_name.' build start</strong><br/>'.$client_address;
                    $this_class="project_start";
                break;

                case "deposit_check":
                    $this_title = '<strong>'.$client_name.'</strong><br/>Deposit check';
                    $this_class="reminder";
                break;

                case "pre_build_confirmation_email":
                    $this_title = '<strong>'.$client_name.'</strong><br/>Pre build confirmation email';
                    $this_class="reminder";
                break;

            }

            $master_key_dates_array[$build_date][] = array(
                "title" => $this_title,
                "class" => $this_class,
            );
        }

        return $master_key_dates_array;
    }

    public static function get_all_build_dates()
    {
        $accepted_quotes = lh_queries::get_accepted_quotes();
        $date_array = array();
        foreach ($accepted_quotes as $quote_meta)
        {
            $quote_id = $quote_meta->ID;
            $build_date = get_post_meta($quote_id,'project_start_date',true);
            $date_quote_accepted = get_post_meta($quote_id,'date_quote_accepted',true);
            $client_info = lh_queries::get_client_from_quote($quote_id);

            if($build_date)
            {
                $date_array[] = array(
                    'date' => $build_date,
                    'type'  => 'project_start_date',
                    'client' => $client_info,
                );

                // Take 7 days offfor the confirmation email
                $date_obj = new DateTime($build_date);
                $date_obj->modify('-7 day');
                $date_check = $date_obj->format('Y-m-d');

                $date_array[] = array(
                    'date' => $date_check,
                    'type'  => 'pre_build_confirmation_email',
                    'client' => $client_info,
                );


                // Take 14 days of the build date check for deposit
                $date_obj = new DateTime($build_date);
                $date_obj->modify('-14 day');
                $date_check = $date_obj->format('Y-m-d');

                $date_array[] = array(
                    'date' => $date_check,
                    'type'  => 'deposit_check',
                    'client' => $client_info,
                );

            }


        }

        return $date_array;
    }


}


?>
