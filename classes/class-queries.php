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

        $client_meta['name'] = $client_name;
        $client_meta['address1'] = $address1;
        $client_meta['address2'] = $address2;
        $client_meta['town'] = $town;
        $client_meta['postcode'] = $postcode;
        $client_meta['email'] = $email;
        $client_meta['phone'] = $phone;

        return $client_meta;


    }
}
?>
