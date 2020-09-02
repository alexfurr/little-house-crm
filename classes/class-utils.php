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

    /**
     *	Takes a cmplete filename with ext and then returns sanitized name
     *  Takes optional var to append at the end of the file for making unique e.g. post ID
     *	---
     */
    public static function sanitize_filename($filename, $append='')
    {
        $file_parts = pathinfo($filename);
        $new_filename =  lh_crm_utils::create_filename($file_parts['filename']);
        $extension =  $file_parts['extension'];
        if($append){$new_filename=$new_filename.'_'.$append;}
        $new_filename = $new_filename.'.'.$extension;

        return $new_filename;

    }

    /**
 *	Removes non alhpanumeric characters and replaces whitespace with underscore
 *	---
 */
public static function create_filename($input)
{
    $output = trim($input);
    $output = strtolower($output);
    $output = preg_replace("/[^A-Za-z0-9 ]/", '', $output);
    $output = preg_replace('/\s+/', '_', $output);

    return $output;

}

}


?>
