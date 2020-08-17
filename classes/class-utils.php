<?php
class lh_crm_utils
{

    public static function get_quotes_upload_folder()
    {
        $upload_path_array = lh_crm_utils::get_lh_quotes_folder();
        $file_dir = $upload_path_array["file_dir"];
        $folder_url = $upload_path_array["folder_url"];


        if (!file_exists($file_dir)) {
            mkdir($file_dir, 0777, true);
        }

        return array(
            "file_dir" => $file_dir,
            "folder_url" => $folder_url,
        );

    }

    // Returns the file dit and the URL of the quotes folder
    public static function get_lh_quotes_folder()
    {
        // Search the tutor doc dir for any filesize
        $wp_upload_dir   = wp_upload_dir();
        $file_dir = $wp_upload_dir['basedir'].'/my-quotes';
        $folder_url = $wp_upload_dir['baseurl'].'/my-quotes';

        return array(
            "file_dir" => $file_dir,
            "folder_url"   => $folder_url
        );
    }

    public static function generate_secret()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 16; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string

    }

    public static function get_first_name($client_fullname)
    {
        $parts = explode(" ", $client_fullname);
        $first_name = $parts[0];
        return $first_name;

    }

}


?>
