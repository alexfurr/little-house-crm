<?php
get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

            <?php

            $quote_id = get_the_ID();

            // Get the hidden key for the quote
            $secret_key = get_post_meta($quote_id, "secret", true);
            $quote_status = get_post_meta($quote_id,'quote_status',true);

            $secret =  get_post_meta($quote_id,'secret',true);

            $check_secret = '';

            if(isset($_GET['secret']) )
            {
                $check_secret = $_GET['secret'];
            }

            $check_secret = $_GET['secret'];

            // Do they have permission to view the quote?
            if($check_secret<>$secret)
            {
                echo 'You do not have permission to view this quote';
            }
            else
            {

                if(isset($_GET['action']) )
                {

                    $action = $_GET['action'];

                    if($action == "accept_quote")
                    {

                        lh_actions::accept_quote($quote_id);
                        $quote_status = "accepted";



                    }
                }

                echo '<div style="text-align:center; margin-bottom:20px; padding:20px;">';

                if($quote_status=="pending")
                {

                    $checklist_array = array(
                        "I have checked with the neighbours and they are happy with the design and location of build.",
                        "I will pay the £500 deposit within 7 days of accepting this quote. I understand this will be deducted from my final invoice.",
                        "I confirm there is access to the garden for 3m lengths of timber, either via a side gate or through the house.",
                        "I confirm water and electricity will be available on the day(s) of the build.",

                    );

                    echo 'We are excited that you would like to proceed with your Little House! We just need you to confirm the following (you can return to this page at any time):';

                    $i=1;
                    echo '<div class="quote_checklist_div">';
                    echo '<ul id="quote_checklist">';
                    foreach ($checklist_array as $checklist_item)
                    {
                        echo '<li><label for="check_item_'.$i.'"><input type="checkbox" id="check_item_'.$i.'" class="quote_checklist_item">';
                        echo $i.'. '.$checklist_item.'</label></li>';
                        $i++;
                    };
                    echo '</ul>';
                    echo '</div>';


                    echo '<a  href="?secret='.$secret.'&action=accept_quote" class="lh-button-primary disabled_link" id="quote_accept_link">Click here to accept your quote</a>';
                }
                elseif($quote_status=="accepted")
                {
                    echo 'Thank you! We will be in touch shortly to finalise a build date if not already done';
                }
                echo '</div>';

                $quote_content = lh_draw::draw_quote($quote_id);
                echo '<div class="quote_preview_wrap">';
                echo $quote_content;
                echo '</div>';
            }

            ?>
        </main>
    </div>

</div>

<?php
get_footer();


?>
