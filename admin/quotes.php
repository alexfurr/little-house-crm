
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

echo '<h1>'.$client_name.' : Projects</h1>';

echo '<a href="post-new.php?post_type=lh_quotes&client_id='.$client_id.'&post_title='.$new_quote_title.'" class="button-primary">Create a new project quote</a><hr/>';



if($quote_count==0)
{
    echo 'No projects found';
}
else
{

    foreach ($my_quotes as $quote_id => $quote_info)
    {
        $quote_title = $quote_info['quote_title'];
        $quote_status = $quote_info['quote_status'];
        $quote_total = $quote_info['quote_total'];
        $quote_date_sent = $quote_info['quote_date_sent'];
        $deposit_status = $quote_info['deposit_status'];
        $materials_status = $quote_info['materials_status'];
        $accessories_status = $quote_info['accessories_status'];
        $invoice_sent = $quote_info['invoice_sent'];
        $invoice_paid = $quote_info['invoice_paid'];
        $build_date = $quote_info['build_date'];

        if($quote_status==""){$quote_status="not_sent";}
        $status_array = lh_queries::get_quote_status_options();

        $quote_status_str = $status_array[$quote_status];

        switch ($quote_status)
        {
            case "pending";
                $quote_status_str =  '<span class="alert_text">Pending</span>';
                $quote_status_box_css = "alert_background";
            break;

            case "accepted":
                $quote_status_str ='<span class="success_text"><i class="fas fa-check-circle"></i> Accepted</span>';
                $quote_status_box_css =  "success_background";

            break;

            default:
                $quote_status_str ='<span class="fail_text">Not yet sent</span>';
                $quote_status_box_css =  "fail_background";
            break;
        }

        switch ($deposit_status)
        {
            case "paid";
                $deposit_status_str =  '<span class="success_text"><i class="fas fa-check-circle"></i> Paid</span>';
                $deposit_status_box_css = "success_background";
            break;

            default:
                $deposit_status_str =  '<span class="fail_text">Not Paid</span>';
                $deposit_status_box_css = "fail_background";
            break;
        }


        switch ($materials_status)
        {
            case "ordered";
                $materials_status_str =  '<span class="success_text"><i class="fas fa-check-circle"></i> Ordered</span>';
                $materials_status_box_css = "success_background";
            break;

            default:
                $materials_status_str =  '<span class="fail_text">Not Ordered</span>';
                $materials_status_box_css = "fail_background";

            break;
        }

        switch ($accessories_status)
        {
            case "arrived";
                $accessories_status_str =  '<span class="success_text"><i class="fas fa-check-circle"></i> Arrived</span>';
                $accessories_status_box_css = "success_background";

            break;

            case "na";
                $accessories_status_str =  'N/A';
                $accessories_status_box_css = "";

            break;

            default:
                $accessories_status_str =  '<span class="fail_text">Not Arrived</span>';
                $accessories_status_box_css = "fail_background";
            break;
        }

        switch ($invoice_sent)
        {
            case "sent";
                $invoice_status_str =  '<span class="success_text"><i class="fas fa-check-circle"></i> Sent</span>';
                $invoice_status_box_css = "success_background";
            break;

            default:
                $invoice_status_str =  '<span class="fail_text">Not sent</span>';
                $invoice_status_box_css = "fail_background";
            break;
        }

        switch ($invoice_paid)
        {
            case "paid";
                $invoice_paid_status_str =  '<span class="success_text"><i class="fas fa-check-circle"></i> Paid</span>';
                $invoice_paid_status_box_css = "success_background";
            break;

            default:
                $invoice_paid_status_str =  '<span class="fail_text">Not paid</span>';
                $invoice_paid_status_box_css = "fail_background";
            break;
        }

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

        $status_boxes_array1 = array(
            array(
                "title" => "Quote Price",
                "value" => "£".$quote_total,
                "class" => "quote_price_class",
                "box_class" => "test",
            ),
            array(
                "title" => "Quote Status",
                "value" => $quote_status_str,
                "class" => "quote_status_class",
                "box_class" => $quote_status_box_css,
            ),
        );

        $status_boxes_array2 = array(


            array(
                "title" => "Materials",
                "value" => $materials_status_str,
                "class" => "quote_status_class",
                "box_class" => $materials_status_box_css,
            ),
            array(
                "title" => "Accessories",
                "value" => $accessories_status_str,
                "class" => "quote_status_class",
                "box_class" => $accessories_status_box_css,
            ),

        );

        $status_boxes_array3 = array(
            array(
                "title" => "Deposit",
                "value" => $deposit_status_str,
                "class" => "quote_status_class",
                "box_class" => $deposit_status_box_css,
            ),

            array(
                "title" => "Invoice Status",
                "value" => $invoice_status_str,
                "class" => "quote_status_class",
                "box_class" => $invoice_status_box_css,
            ),

            array(
                "title" => "Final Payment",
                "value" => $invoice_paid_status_str,
                "class" => "quote_status_class",
                "box_class" => $invoice_paid_status_box_css,
            ),
        );


        // Initial Cost
        echo '<div class="project_status_boxes">';
        foreach ($status_boxes_array1 as $quote_meta)
        {
            $this_title = $quote_meta['title'];
            $this_value = $quote_meta['value'];
            $this_class = $quote_meta['class'];
            $this_box_class = $quote_meta['box_class'];

            echo '<div class="status_box_item">';
            echo '<div class="'.$this_class.' '.$this_box_class.' quote_meta_value">'.$this_value.'</div>';
            echo '<div class="status_box_title">'.$this_title.'</div>';
            echo '</div>';
        }
        echo '</div>';


        if($quote_status=="accepted")
        {

            // pre build stuff
            echo '<div class="quote_title">Pre Build</div>';

            echo '<div class="project_status_boxes">';
            foreach ($status_boxes_array2 as $quote_meta)
            {
                $this_title = $quote_meta['title'];
                $this_value = $quote_meta['value'];
                $this_class = $quote_meta['class'];
                $this_box_class = $quote_meta['box_class'];

                echo '<div class="status_box_item">';
                echo '<div class="'.$this_class.' '.$this_box_class.' quote_meta_value">'.$this_value.'</div>';
                echo '<div class="status_box_title">'.$this_title.'</div>';
                echo '</div>';
            }
            echo '</div>';

            // Payment stuff Cost
            echo '<div class="quote_title">Payment</div>';

            echo '<div class="project_status_boxes">';
            foreach ($status_boxes_array3 as $quote_meta)
            {
                $this_title = $quote_meta['title'];
                $this_value = $quote_meta['value'];
                $this_class = $quote_meta['class'];
                $this_box_class = $quote_meta['box_class'];

                echo '<div class="status_box_item">';
                echo '<div class="'.$this_class.' '.$this_box_class.' quote_meta_value">'.$this_value.'</div>';
                echo '<div class="status_box_title">'.$this_title.'</div>';
                echo '</div>';
            }
            echo '</div>';
        }


        echo '<a href="post.php?post='.$quote_id.'&action=edit" class="button-secondary">View / edit quote</a>';

        echo '</div>';
        echo '<div class="quote_image">'.$img.'</div>';
        echo '</div>';

        echo '</div>';

    }
}



?>
