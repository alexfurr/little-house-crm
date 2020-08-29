<h1>Invoice Preview</h1>
<?php

echo lh_draw::feedback(); // Check for any feedback

$quote_id = $_GET['id'];

$client_meta = lh_queries::get_client_from_quote($quote_id);
$client_id = get_post_meta($quote_id, 'client_id', true);
$client_email = get_post_meta($client_id, 'email', true);

$default_invoice_text= '';
if (filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
    //echo "$client_email is a valid email address";
} else {
    echo '"'.$client_email.'" is not a valid email address';
    die();
}

$email_text = 'test';

$default_subject = 'Your Little House Invoice';

$default_email_text = lh_quotes::default_invoice_email_content($quote_id);

echo '<a href="post.php?post='.$quote_id.'&action=edit">Return to quote</a>';
echo '<hr/>';

echo '<div style="width:90%">';
echo '<form action="options.php?page=invoice-preview&action=send_invoice&id='.$quote_id.'" method="post">';
echo '<h3>Email Subject</h3>';
echo '<input type="text" placeholder="Email Subject" id="email_subject" name="email_subject" value="'.$default_subject.'" size="50"/>';
echo '<h3>Email Content</h3>';
wp_editor(
    $default_email_text,
    'email_content',
    array(
       'tinymce' => true,
      'media_buttons' => false,
      'textarea_rows' => 8,
  )
);

echo '<input type="submit" value="Send Invoice" class="button-primary" />';
echo '</form>';
echo '</div>';

$invoice_content = lh_draw::draw_invoice($quote_id);
echo '<h2>PDF Preview</h2>';
echo '<span class="smallText">This invoice PDF will be sent as an attachment to the email</span>';
echo '<div class="quote_preview_wrap">';
echo $invoice_content;
echo '</div>';



//echo lh_crm_pdf::create_pdf($quote_id);

?>
