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
        $email_content = $_POST['email_content'].' and folder = '.$quotes_folder['folder_url'];

        $headers = array('Content-Type: text/html; charset=UTF-8'); // Make it send as HTML
        $attachments = array($pdf);
        wp_mail($client_email, $email_subject, $email_content, $headers, $attachments);

        // Finally set the quote status to sent
        update_post_meta( $quote_id, 'quote_status', 'pending' );

    }

}
?>
