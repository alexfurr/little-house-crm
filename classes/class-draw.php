<?php

class lh_draw
{
    
    
    public static function draw_quote_for_pdf( $quote_id )
    {
        // Some default vars for the HTML emai
        $cellpadding = ' style="padding:5px;" ';
        $primary_color = "#acd037";

        $quote_info = get_page( $quote_id );
        $content = apply_filters('the_content', $quote_info->post_content);
        $quote_total = get_post_meta($quote_id,'quote_total',true);

        // Get the featured image
        $img_src = get_the_post_thumbnail_url( $quote_id, 'medium' );
        $img = '';
        if( $img_src ) {
            $img = '<img src = "'.$img_src.'" style="display:block; margin-left:50px; margin-right:auto; width:640px;"><br/>&nbsp;<br/>&nbsp;<br/>';
        }

        // get the quote breakdown and replace the lh_quote
        $item_lookup = lh_queries::get_quote_items();
        $quote_breakdown_str='<div style="padding:30px;">';
        $quote_breakdown_str.= '<table style="border-collapse: collapse; font-size:13px;  margin-left: auto;   margin-right: auto; width:100%;">';
        $quote_breakdown_str.= '<tr style="color:#acd037;font-weight:bold;"><td style="padding-left:8px; width:40%;">Item<br/>&nbsp;</td><td>Quantity</td><td>Cost</td><td></td></tr>';

        $quote_breakdown_array = get_post_meta($quote_id,'quote_breakdown',true);
        foreach ($quote_breakdown_array as $item_key => $item_quantity) {
            $item_name = $item_lookup[$item_key]['name'];
            $item_cost = $item_lookup[$item_key]['cost'];
            $item_type = $item_lookup[$item_key]['unit'];
            $this_subtotal = 0;

            if($item_quantity==0) {
                continue;
            }
            switch ($item_type) {
                case "each":
                case "sqm":
                {
                    $this_subtotal = ($item_cost * $item_quantity);
                    $unit_cost = '£'.$item_cost;

                    break;

                }

                case "fixed":
                    $this_subtotal= $item_quantity;
                    $item_quantity = 'Fixed';
                    $unit_cost = '';
                break;
            }
            $quote_breakdown_str.= '<tr style=" border-bottom:1px solid #ccc;"><td '.$cellpadding.'>'.$item_name.'</td><td>'.$item_quantity.'</td><td>'.$unit_cost.'</td><td style="font-weight:bold;">£'.$this_subtotal.'</td></tr>';
        }
        $quote_breakdown_str.='<tr><td>&nbsp;</td><td></td><td></td><td></td></tr>';
        $quote_breakdown_str.='<tr style="font-size:20px; font-weight:bold;"><td '.$cellpadding.'>Total</td><td></td><td></td><td>£'.$quote_total.'</td></tr>';
        $quote_breakdown_str.= '</table></div>';

        $quote_link = get_the_permalink($quote_id);
        $secret = get_post_meta($quote_id, 'secret', true);
        $accept_link = '<a href="'.$quote_link.'?secret='.$secret.'">Click here to accept the quote</a>';

        // replace the lh_iamge with the img
        $content = str_replace("[lh_image]", $img, $content);
        $content = str_replace("[lh_quote]", $quote_breakdown_str, $content);
        $content = str_replace("[lh_accept_link]", $accept_link, $content);


        $html = '';

        $client_id = get_post_meta($quote_id,'client_id',true);
        $address1 = get_post_meta($client_id,'address1',true);
        $address2 = get_post_meta($client_id,'address2',true);
        $town = get_post_meta($client_id,'town',true);
        $postcode = get_post_meta($client_id,'postcode',true);
        $email = get_post_meta($client_id,'postcode',true);
        $phone = get_post_meta($client_id,'phone',true);
        $client_name = get_the_title($client_id);

        //$html.= '<table cellspacing="0" cellpadding="1" border="0" width="100%" style="margin:0px; border-collapse: collapse;">';
        //$html.= '<tr style=" border-bottom:1px solid #ccc;"><td>';

        // Main content outside header
        $html.='<table cellspacing="0" cellpadding="1" border="0" style="width:100%; border-color:#fff; margin-left: auto;   margin-right:auto; border-collapse: collapse;">';
        $html.='<tr><td style="text-align: right; font-size:11px;">';
        $html.= "Quote # 123<br/>Date : ".date('d/m/y');
        $html.= '</td></tr>';
        $html.= '<tr><td style="font-size:12px;">';
        $html.= '<strong>'.$client_name.'</strong><br/>';
        if($address1){$html.= ''.$address1.'<br/>';}
        if($address2){$html.= ''.$address2.'<br/>';}
        if($postcode){$html.= ''.$postcode.'<br/>';}
        $html.= '</td></tr>';

        // The main letter content
        $html.='<tr><td style="font-size:12px;">';
        $html.=$content;
        $html.='</td></tr>';
        $html.='</table>';

        // Start of footer
        //$html.='</td></tr>';
        //$html.= '</table>';

        return $html;
    }
    
    
    
    // Draws the main quote in old school HTML with tables so t's compaitble with the PDF creator
    public static function draw_quote($quote_id)
    {
        // Some default vars for the HTML emai
        $cellpadding = ' style="padding:5px;" ';


        $quote_info = get_page( $quote_id );
        $content = apply_filters('the_content', $quote_info->post_content);
        $quote_total = get_post_meta($quote_id,'quote_total',true);

        // Get the featured image
        $img_src = get_the_post_thumbnail_url( $quote_id, 'medium' );
        $img = '';

        if($img_src)
        {
            $img = '<img src = "'.$img_src.'" style="  display: block;  margin-left: auto;   margin-right: auto;">';
        }

        // get the quote breakdown and replace the lh_quote
        $item_lookup = lh_queries::get_quote_items();
        $quote_breakdown_str='<div style="padding:30px;">';

        $quote_breakdown_str.= '<table style="border-collapse: collapse; font-size:15px;  margin-left: auto;   margin-right: auto; width:70%;">';
        $quote_breakdown_str.= '<tr style="background:#336699; color:#fff; font-size:18px; font-style:italic;"><td style="padding-left:8px;">Item</td><td>Quantity</td><td>Cost</td><td></td></tr>';

        $quote_breakdown_array = get_post_meta($quote_id,'quote_breakdown',true);
        foreach ($quote_breakdown_array as $item_key => $item_quantity)
        {
            $item_name = $item_lookup[$item_key]['name'];
            $item_cost = $item_lookup[$item_key]['cost'];
            $item_type = $item_lookup[$item_key]['unit'];
            $this_subtotal = 0;


            if($item_quantity==0)
            {
                continue;
            }
            switch ($item_type)
            {
                case "each":
                case "sqm":
                {
                    $this_subtotal = ($item_cost * $item_quantity);
                    $unit_cost = '£'.$item_cost;

                    break;

                }

                case "fixed":
                    $this_subtotal= $item_quantity;
                    $item_quantity = 'Fixed';
                    $unit_cost = '';
                break;

            }
            $quote_breakdown_str.= '<tr style=" border-bottom:1px solid #ccc;"><td '.$cellpadding.'>'.$item_name.'</td><td>'.$item_quantity.'</td><td>'.$unit_cost.'</td><td style="font-weight:bold;">£'.$this_subtotal.'</td></tr>';


        }
        $quote_breakdown_str.='<tr style="font-size:20px; font-weight:bold;"><td '.$cellpadding.'>Total</td><td></td><td></td><td>£'.$quote_total.'</td></tr>';

        $quote_breakdown_str.= '</table></div>';

        $quote_link = get_the_permalink($quote_id);
        $secret = get_post_meta($quote_id, 'secret', true);

        $accept_link = '<a href="'.$quote_link.'?secret='.$secret.'">Click here to accept the quote</a>';


        // replace the lh_iamge with the img
        $content = str_replace("[lh_image]", $img, $content);
        $content = str_replace("[lh_quote]", $quote_breakdown_str, $content);
        $content = str_replace("[lh_accept_link]", $accept_link, $content);



        $html = '';

        $client_id = get_post_meta($quote_id,'client_id',true);

        $address1 = get_post_meta($client_id,'address1',true);
        $address2 = get_post_meta($client_id,'address2',true);
        $town = get_post_meta($client_id,'town',true);
        $postcode = get_post_meta($client_id,'postcode',true);
        $email = get_post_meta($client_id,'postcode',true);
        $phone = get_post_meta($client_id,'phone',true);

        $client_name = get_the_title($client_id);

        // Get the logo




        $html.= '<table width="100%" style="margin:0px">';
        
        
        $html.='<tr><td style="padding:0px; border:0px;">';
        // Header
        $logo_src = LH_EMAIL_LOGO;
        $html.='<table style="margin:0px; background:'.PRIMARY_COLOR.';color:#fff;">';
        $html.='<tr>';
        $html.='<td style="width:70px; border:0px;">';
        $html.='<img src = "'.$logo_src.'" height="60px" width="60px">';
        $html.='</td>';
        $html.='<td style="border:0px; width:100%; font-size:24px;">Little House</td>';
        $html.='</tr></table>';
        // End of header
        $html.='</td></tr>';
        
        
        
        
        $html.= '<tr><td>';

        // Main content outside header
        $html.='<table style="width:80%; margin-left: auto;   margin-right:auto; ">';
        $html.='<td style="text-align: right; font-size:10px; border:0px;">';
        $html.= "Quote # 123<br/>Date : ".date('d/m/y');
        $html.= '</td></tr>';
        $html.= '<tr><td style="font-size:10px; border:0px; ">';
        $html.= '<strong>'.$client_name.'</strong><br/>';
        if($address1){$html.= ''.$address1.'<br/>';}
        if($address2){$html.= ''.$address2.'<br/>';}
        if($postcode){$html.= ''.$postcode.'<br/>';}
        $html.= '</td>';

        // The main letter content
        $html.='<tr><td style="border:0px; padding-left:50px; padding-right:50px; font-size:14px;">';
        $html.=$content;
        $html.='</td></tr>';
        $html.='</table>';

        // Start ofr footer
        $html.='</td></tr>';



        $html.= '</table>';


        return $html;

    }

    public static function draw_invoice($quote_id)
    {
        $html = '';

        $quote_meta = get_post_meta($quote_id);

        $quote_meta_array = array(
            'client_id',
            'quote_total',
            'deposit_status',

        );

        foreach ($quote_meta_array as $meta_key)
        {
            // Set to blank by default
            $$meta_key = '';

            // If it exists set the var to be the value
            if(array_key_exists($meta_key, $quote_meta) )
            {
                $$meta_key = $quote_meta[$meta_key][0];
            }
        }

        $address1 = get_post_meta($client_id,'address1',true);
        $address2 = get_post_meta($client_id,'address2',true);
        $town = get_post_meta($client_id,'town',true);
        $postcode = get_post_meta($client_id,'postcode',true);
        $email = get_post_meta($client_id,'postcode',true);
        $phone = get_post_meta($client_id,'phone',true);

        // Calculate the invoice left to pay

        $deposit_paid = 0;
        if($deposit_status=="paid")
        {
            $deposit_paid = "500";
        }


        $client_name = get_the_title($client_id);
        $first_name = lh_crm_utils::get_first_name($client_name);

        $html.= '<table width="100%" style="margin:0px">';
        $html.='<tr><td style="padding:0px; border:0px;">';

        // Header
        $logo_src = LH_EMAIL_LOGO;
        $html.='<table style="margin:0px; background:'.PRIMARY_COLOR.';color:#fff;">';
        $html.='<tr>';
        $html.='<td style="width:70px; border:0px;">';
        $html.='<img src = "'.$logo_src.'" height="60px" width="60px">';
        $html.='</td>';
        $html.='<td style="border:0px; width:100%; font-size:24px;">Little House</td>';

        $html.='</tr></table>';
        // End of header

        $html.='</td></tr>';
        $html.= '<tr><td>';

        // Main content outside header
        $html.='<table style="width:80%; margin-left: auto;   margin-right:auto; ">';
        $html.='<td style="text-align: right; font-size:10px; border:0px;">';
        $html.= "Invoice #LH-INV-".$quote_id."<br/>Date : ".date('d/m/y');
        $html.= '</td></tr>';
        $html.= '<tr><td style="font-size:10px; border:0px; ">';
        $html.= '<strong>'.$client_name.'</strong><br/>';
        if($address1){$html.= ''.$address1.'<br/>';}
        if($address2){$html.= ''.$address2.'<br/>';}
        if($postcode){$html.= ''.$postcode.'<br/>';}
        $html.= '</td>';

        // The main letter content
        $html.='<tr><td style="border:0px; padding-left:50px; padding-right:50px; font-size:14px;">';
       // $html.=$content;

       $html.='Dear '.$first_name.'<br/><br/>';
       $html.='Thank you for choosing Little House!<br/>';
       $html.='Please find the outstanding balance to be paid for your Little House below.  We would appreciate it if you could please settle the balance within 30 days of receiving this invoice.<br/><br/>';


       $left_to_pay = $quote_total - $deposit_paid;
       $html.='Cost : £'.$quote_total.'<br/>';
       $html.='Deposit Paid : £'.$deposit_paid.'<br/>';
       $html.='Outstanding Balance : £'.$left_to_pay.'<br/>';
       $html.='Account Holder: '.BANK_AC_NAME.'<br/>';
       $html.='Sort Code: '.BANK_SC.'<br/>';
       $html.='Account number: '.BANK_AC_NUMBER.'<br/><br/>';

       $html.='If you have any queries about your Little House, please do not hesitate to get in touch.<br/><br/>';
       $html.='Kind Regards,<br/>';

       $html.=LH_SIGNATURE;



       // end of content
        $html.='</td></tr>';
        $html.='</table>';

        // Start ofr footer
        $html.='</td></tr>';

        $html.= '</table>';


        return $html;




    }

    public static function feedback()
    {
        if(isset($_GET['feedback']) )
        {

            $feedback = $_GET['feedback'];

            switch ($feedback)
            {
                case "quote_sent":
                $feedback_str = 'Quote sent!';
                break;

                case "invoice_sent":
                $feedback_str = 'Invoice sent!';
                break;

            }

            $html = '<div class="notice notice-success is-dismissible">
            	<p><strong>'.$feedback_str.'</strong></p>
            	<button type="button" class="notice-dismiss">
            		<span class="screen-reader-text">Dismiss this notice.</span>
            	</button>
            </div>';

            return $html;


        }
    }


}


?>
