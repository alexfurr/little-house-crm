
<?php

$client_id = $_GET['client-id'];
$client_meta = lh_queries::get_client($client_id);
$client_name = $client_meta['name'];
$my_quotes = lh_queries::get_client_quotes($client_id);
$quote_count = count($my_quotes);

$next_quote = $quote_count+1;
$new_quote_title = 'Quote for '.$client_name;
if($quote_count>=1)
{
    $new_quote_title.= ' (v'.$next_quote.')';
}

echo '<h1>'.$client_name.' : Quotes</h1>';

echo '<a href="post-new.php?post_type=lh_quotes&client_id='.$client_id.'&post_title='.$new_quote_title.'" class="button-primary">Create a new quote</a><hr/>';



if($quote_count==0)
{
    echo 'No quotes found';
}
else
{

    foreach ($my_quotes as $quote_id => $quote_info)
    {
        $quote_title = $quote_info['quote_title'];
        $quote_status = $quote_info['quote_status'];
        $quote_total = $quote_info['quote_total'];
        $quote_date_sent = $quote_info['quote_date_sent'];

        if($quote_status==""){$quote_status="not_sent";}
        $status_array = lh_queries::get_quote_status_options();

        $this_status = $status_array[$quote_status];


        // Get the featured image
        $img_src = get_the_post_thumbnail_url( $quote_id, 'medium' );

        $img = '';
        if($img_src)
        {
            $img = '<img src = "'.$img_src.'" style="  display: block;  margin-left: auto;   margin-right: auto;">';
        }


        echo '<div class="quote_overview">';
        echo '<div class="quote_title">'.$quote_title.'</div>';
        echo '<div class="quote_meta">';
        echo '<div class="quote_info">';
        echo 'Total Price : £'.$quote_total.'<br/>';
        echo 'Status : '.$this_status.'<br/>';

        if($quote_status=="pending")
        {
            echo 'Sent on '.$quote_date_sent.'<br/>';
        }


        echo '<br/>';
        echo '<a href="post.php?post='.$quote_id.'&action=edit" class="button-secondary">View / edit quote</a>';

        echo '</div>';
        echo '<div class="quote_image">'.$img.'</div>';
        echo '</div>';

        echo '</div>';

    }
}



?>
