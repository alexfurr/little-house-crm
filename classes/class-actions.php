<?php


class lh_actions
{
    public static function send_quote($quote_id)
    {


        $client_meta = lh_queries::get_client_from_quote($quote_id);
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

    }

}
?>
