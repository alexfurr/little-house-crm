<h1>Quote Preview</h1>
<?php

echo lh_draw::feedback(); // Check for any feedback


$quote_id = $_GET['id'];

$client_meta = lh_queries::get_client_from_quote($quote_id);

$client_id = get_post_meta($quote_id, 'client_id', true);
$client_email = get_post_meta($client_id, 'email', true);

$default_email_text= '';
if (filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
    //echo "$client_email is a valid email address";
} else {
    echo '"'.$client_email.'" is not a valid email address';
    die();
}


// Create the quote PDF
$args = array(
    "doc_type" => "quote",
    "quote_id" => $quote_id,
);
$quote_info = lh_crm_pdf::create_pdf($args);
$quote_url = $quote_info['URL'];

$default_subject = 'Your Little House Quote';

$default_email_text = lh_quotes::default_quote_email_content($quote_id);

echo '<a href="post.php?post='.$quote_id.'&action=edit">Return to quote</a>';
echo '<hr/>';

echo '<div style="width:90%">';
echo '<form action="options.php?page=quote-preview&action=send_quote&id='.$quote_id.'" method="post">';
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

echo '<h3>Attached PDF quote</h3>';
echo '<span class="smallText">This PDF will be sent as an attachment to the email</span><br/>';
echo '<a href="'.$quote_url.'" target="blank"><i class="fas fa-file-pdf fa-3x"></i> View PDF</a><hr/>';

echo '<input type="submit" value="Send quote" class="button-primary" />';
echo '</form>';
echo '</div>';

//$quote_content = lh_draw::draw_quote_for_pdf($quote_id);

//echo '<div class="quote_preview_wrap">';
//echo $quote_content;
//echo '</div>';



?>
