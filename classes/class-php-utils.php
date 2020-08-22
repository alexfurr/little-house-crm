<?php


class php_utils
{



	static function remove_non_alphanumeric($str)
	{
		$outstr = preg_replace("/[^A-Za-z0-9 ]/", '', $str);

		return $outstr;
	}


    public static function convert_text_from_db($string)
    {
        $string = stripslashes($string);
        $string = wp_kses_post($string);
        $string = wpautop($string);


        return $string;
    }




	public static function get_filetype_class($ext)
	{

		$ext = strtolower($ext);

		switch ($ext)
		{

			case "doc":
			case "docx":
				$class = 'doc_download';
			break;

			case "pdf":
				$class = 'pdf_download';
			break;

			case "ppt":
			case "pptx":
				$class = 'ppt_download';
			break;

			case "xlsx":
			case "xls":
			case "csv":
				$class = 'xls_download';
			break;

            case "png":
            case "jpg":
            case "jpeg":
            case "gif":
                $class = 'img_download';
            break;

			default:

				$class = 'txt_download';
			break;
		}

		// Add FiletypeLink to add the padding
		return ' fileTypeLink '.$class.' ';

	}

	static function get_uk_date($inputDate)
	{
		$tz = new DateTimeZone('Europe/London');
		$date = new DateTime($inputDate);
		$date->setTimezone($tz);
		$UKdate = $date->format('Y-m-d H:i:s');


		return $UKdate;
	}





    // Returns 1st, 2nd, 3rd etc
	static function get_ordinal($num)
	{
		$last=substr($num,-1);
		if( $last>3  or
			$last==0 or
			( $num >= 11 and $num <= 19 ) )
		{
			$ext='th';
		}
		else if( $last==3 )
		{
			$ext='rd';
		}
		else if( $last==2 )
		{
			$ext='nd';
		}
		else
		{
			$ext='st';
		}
		return $num.$ext;
	}

   public static function wp_kses_allowed_html()
   {

    	$allowed_tags = array(
    		'a' => array(
    			'class' => array(),
    			'href'  => array(),
    			'rel'   => array(),
    			'title' => array(),
    		),
    		'abbr' => array(
    			'title' => array(),
    		),
    		'b' => array(),
    		'blockquote' => array(
    			'cite'  => array(),
    		),
    		'cite' => array(
    			'title' => array(),
    		),
    		'code' => array(),
    		'del' => array(
    			'datetime' => array(),
    			'title' => array(),
    		),
    		'dd' => array(),
    		'div' => array(
    			'class' => array(),
    			'title' => array(),
    			'style' => array(),
    		),
    		'dl' => array(),
    		'dt' => array(),
    		'em' => array(),
    		'h1' => array(),
    		'h2' => array(),
    		'h3' => array(),
    		'h4' => array(),
    		'h5' => array(),
    		'h6' => array(),
    		'i' => array(),
    		'img' => array(
    			'alt'    => array(),
    			'class'  => array(),
    			'height' => array(),
    			'src'    => array(),
    			'width'  => array(),
    		),
    		'li' => array(
    			'class' => array(),
    		),
    		'ol' => array(
    			'class' => array(),
    		),
    		'p' => array(
    			'class' => array(),
    		),
    		'q' => array(
    			'cite' => array(),
    			'title' => array(),
    		),
    		'span' => array(
    			'class' => array(),
    			'title' => array(),
    			'style' => array(),
    		),
    		'strike' => array(),
    		'strong' => array(),
            'sub' => array(),
            'sup' => array(),
    		'ul' => array(
    			'class' => array(),
    		),
    	);

    	return $allowed_tags;
    }



    public static function get_current_username()
    {
        global $current_user;
        $current_user = wp_get_current_user();
        $username = $current_user->user_login;

        return $username;

    }

    /**
     *	Takes a cmplete filename with ext and then returns sanitized name
     *  Takes optional var to append at the end of the file for making unique e.g. post ID
     *	---
     */
    public static function sanitize_filename_from_upload($filename, $append='')
    {
        $file_parts = pathinfo($filename);
        $new_filename =  imperialNetworkUtils::create_filename($file_parts['filename']);
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
