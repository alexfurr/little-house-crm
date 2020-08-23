<?php
class lh_queries
{
    public static function get_quote_items()
    {
        $my_items = array();

        $my_items["quote_element_4by4"] = array(
            "name" => "4 by 4 trested newel posts",
            "cost" => "30",
            "unit"  => "each",
        );

        $my_items["quote_element_4by3"] = array(
            "name" => "4 by 3 treated timber (4.8m)",
            "cost" => "27",
            "unit"  => "each",
        );

        $my_items["quote_element_4by2"] = array(
            "name" => "4 by 2 treated timber (4.8m)",
            "cost" => "25",
            "unit"  => "each",
        );

        $my_items["quote_element_decking"] = array(
            "name" => "Deckboard (sqm)",
            "cost" => "26",
            "unit"  => "sqm",
        );

        $my_items["quote_element_tig"] = array(
            "name" => "Tongue in groove (sqm)",
            "cost" => "28",
            "unit"  => "sqm",
        );

        $my_items["quote_element_fixings"] = array(
            "name" => "Fixings",
            "cost" => "60",
            "unit"  => "fixed",
        );

        $my_items["quote_element_labour"] = array(
            "name" => "Labour",
            "cost" => "650",
            "unit"  => "fixed",
        );

        return $my_items;

    }

    public static function get_client_from_quote($quote_id)
    {

        $client_meta = array();
        $client_id = get_post_meta($quote_id,'client_id',true);
        $client_name = get_the_title($client_id);

        // GEt the post meta
        $address1 = get_post_meta($client_id,'address1',true);
        $address2 = get_post_meta($client_id,'address2',true);
        $town = get_post_meta($client_id,'town',true);
        $postcode = get_post_meta($client_id,'postcode',true);
        $email = get_post_meta($client_id,'email',true);
        $phone = get_post_meta($client_id,'phone',true);

        $client_meta['client_id'] = $client_id;
        $client_meta['name'] = $client_name;
        $client_meta['address1'] = $address1;
        $client_meta['address2'] = $address2;
        $client_meta['town'] = $town;
        $client_meta['postcode'] = $postcode;
        $client_meta['email'] = $email;
        $client_meta['phone'] = $phone;

        return $client_meta;


    }

    public static function get_client($client_id)
    {

        $client_meta = array();

        $client_name = get_the_title($client_id);

        // GEt the post meta
        $address1 = get_post_meta($client_id,'address1',true);
        $address2 = get_post_meta($client_id,'address2',true);
        $town = get_post_meta($client_id,'town',true);
        $postcode = get_post_meta($client_id,'postcode',true);
        $email = get_post_meta($client_id,'email',true);
        $phone = get_post_meta($client_id,'phone',true);

        $client_meta['name'] = $client_name;
        $client_meta['address1'] = $address1;
        $client_meta['address2'] = $address2;
        $client_meta['town'] = $town;
        $client_meta['postcode'] = $postcode;
        $client_meta['email'] = $email;
        $client_meta['phone'] = $phone;

        return $client_meta;

    }

    public static function get_client_quotes($client_id)
    {

        $args = array(
            'posts_per_page'   => -1,
            'orderby'           => 'title',
            'order'            => 'ASC',
            'post_type'        => 'lh_quotes',
            'post_status'      => 'publish',

            // more args here
            'meta_query' => array(
            // meta query takes an array of arrays, watch out for this!
                array(
                'key'     => 'client_id',
                'value'   => $client_id,
                'compare' => '='
                ),
            )

        );
        $posts_array = get_posts( $args );
        $quotes_array = array();

        foreach ($posts_array as $quote_info)
        {
            $quote_id = $quote_info->ID;
            $quote_title = $quote_info->post_title;
            $quote_status = get_post_meta($quote_id,'quote_status',true);
            $quote_total = get_post_meta($quote_id,'quote_total',true);
            $quote_date_sent = get_post_meta($quote_id,'quote_date_sent',true);
            $quote_date_sent = get_post_meta($quote_id,'quote_date_sent',true);
            $deposit_status = get_post_meta($quote_id,'deposit_status',true);
            $materials_status = get_post_meta($quote_id,'materials_status',true);
            $accessories_status = get_post_meta($quote_id,'accessories_status',true);
            $build_date = get_post_meta($quote_id,'build_date',true);

            $quotes_array[$quote_id] = array(
                'quote_title' => $quote_title,
                'quote_status' => $quote_status,
                'quote_total' => $quote_total,
                'quote_date_sent' => $quote_date_sent,
                'deposit_status' => $deposit_status,
                'materials_status' => $materials_status,
                'accessories_status' => $accessories_status,
                'build_date' => $build_date,
            );

        }


        return $quotes_array;

    }

    public static function get_quote_status_options()
    {
        $status_array = array(
            "not_sent" => "Not yet sent",
            "pending" => "Sent, pending response",
            "accepted" => "Accepted",
        );

        return $status_array;

    }

    public static function get_client_activity($client_id)
    {
        global $wpdb;
        global $lh_activity_db;

        $sql = "SELECT * FROM $lh_activity_db WHERE client_id= $client_id ORDER by activity_date DESC";

        $activity_items =  $wpdb->get_results( $sql );
        return $activity_items;

    }

    public static function get_activity_info($item_id)
    {
        global $wpdb;
        global $lh_activity_db;

        $sql = "SELECT * FROM $lh_activity_db WHERE id= $item_id";

        $activity_info =  $wpdb->get_row( $sql );
        return $activity_info;
    }
}
?>
