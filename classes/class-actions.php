<?php


class lh_actions
{
    public static function send_quote($quote_id)
    {
        $client_meta = lh_queries::get_client_from_quote($quote_id);
        $client_id = $client_meta['client_id'];
        $client_email = $client_meta['email'];


        $quotes_folder = lh_crm_utils::get_quotes_upload_folder();
        $pdf = $quotes_folder['file_dir'].'/test.pdf';


        $email_subject = $_POST['email_subject'];
        $email_content = nl2br($_POST['email_content']);

        // Add proper link breaks i.e. convert BR to /n
       // $breaks = array("<br />","<br>","<br/>");
        //$email_content = str_ireplace($breaks, "\r\n", $email_content);


        $headers = array('Content-Type: text/html; charset=UTF-8'); // Make it send as HTML
        $attachments = array($pdf);
        wp_mail($client_email, $email_subject, $email_content, $headers, $attachments);

        // Finally set the quote status to sent
        update_post_meta( $quote_id, 'quote_status', 'pending' );


        // Update post date it was sent
        update_post_meta( $quote_id, 'quote_date_sent', date('Y-m-d') );

        // Update the client timeline
        $args = array(
            "client_id" => $client_id,
            "activity_title" => 'Quote Sent',
            "activity_content" => 'Quote was sent to '.$client_email,
            "activity_date" => date('Y-m-d'),
        );
        lh_actions::activity_item_add($args);

    }

    public static function accept_quote($quote_id)
    {
        // Finally set the quote status to sent
        update_post_meta( $quote_id, 'quote_status', 'accepted' );
        update_post_meta( $quote_id, 'date_quote_accepted', date('Y-m-d H:i:s'));
        $client_info = lh_queries::get_client_from_quote($quote_id);
        $client_id = $client_info['client_id'];
        $client_name = $client_info['name'];
        $client_email = $client_info['email'];

        $headers = array('Content-Type: text/html; charset=UTF-8'); // Make it send as HTML
        $email_subject = 'Quote accepted by '.$client_name;
        $email_content = 'Quote has just accepted by '.$client_name;

        //$headers = array('Content-Type: text/html; charset=UTF-8'); // Make it send as HTML
        wp_mail(LH_EMAIL_ADDRESS, $email_subject, $email_content, $headers);


        $client_first_name = lh_crm_utils::get_first_name($client_name);
        $email_subject = 'Little House Quote - Acceptance Confirmation';
        $email_content = 'Dear '.$client_first_name.',<br/><br/>';
        $email_content.= 'Thank you for accepting your Little House quote. We are looking forward to your build!<br/><br/>';
        $email_content.= 'Your Little House Client Liaison Ailsa Peron will be in touch to confirm the details of your build.<br/><br/>';
        $email_content.= 'Please don\'t hesitate to get in touch at any time if you have any questions or queries.<br/><br/>';


        $email_content.= LH_SIGNATURE;

        wp_mail($client_email, $email_subject, $email_content, $headers);

        // Update the client timeline
        $args = array(
            "client_id" => $client_id,
            "activity_title" => 'Quote Accepted',
            "activity_content" => 'Quote accepted by '.$client_name,
            "activity_date" => date('Y-m-d'),
        );
        lh_actions::activity_item_add($args);

    }

    public static function process_timeline_item()
    {
        global $wpdb;
        global $lh_activity_db;


        $item_id = $_POST['item_id'];

        $client_id = $_POST['client_id'];
        $activity_title = $_POST['activity_title'];
        $activity_content = $_POST['activity_content'];
        $activity_date = $_POST['activity_date'];



        if($item_id)
        {
            $wpdb->query( $wpdb->prepare(
                "UPDATE   ".$lh_activity_db." SET activity_title=%s, activity_content=%s, activity_date=%s WHERE id = %d;",
                $activity_title,
                $activity_content,
                $activity_date,
                $item_id
            ));


           return "timeline_item_updated";



        }
        else
        {

            $args = array(
                "client_id" => $client_id,
                "activity_title" => $activity_title,
                "activity_content" => $activity_content,
                "activity_date" => $activity_date,
            );
            lh_actions::activity_item_add($args);

            return "timeline_item_added";


        }


    }

    public static function activity_item_add($args)
    {
        global $wpdb;
        global $lh_activity_db;

        $client_id = $args['client_id'];
        $activity_title = $args['activity_title'];
        $activity_content = $args['activity_content'];
        $activity_date = $args['activity_date'];

        $wpdb->query( $wpdb->prepare(
        "INSERT INTO ".$lh_activity_db." (client_id, activity_title, activity_content, activity_date)
        VALUES ( %d, %s, %s, %s )",
        array(
            $client_id,
            $activity_title,
            $activity_content,
            $activity_date
            )
        ));


    }

}
?>
