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
                        // Finally set the quote status to sent
                        update_post_meta( $quote_id, 'quote_status', 'accepted' );

                        $quote_status = "accepted";

                    }
                }

                echo '<div style="text-align:center; margin-bottom:20px; padding:20px;">';

                if($quote_status=="pending")
                {
                    echo '<a href="?secret='.$secret.'&action=accept_quote" class="lh-button-primary">Click here to accept this quote</a>';
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
