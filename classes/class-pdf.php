<?php


class lh_crm_pdf
{

    public static function create_pdf($args)
    {

    	//set up some default style options
    	$bg_rgb = array(
    		'red' 	=> 255,
    		'green' => 255,
    		'blue' 	=> 255
    	);

    	$text_font = 'helvetica';
    	$text_hex = '#363636';
    	$link_hex = '#3333ff';


        // Some default vars for the HTML emai
        $cellpadding = ' style="padding:5px;" ';

        $primary_color = "#acd037";


        $doc_type = $args['doc_type'];

        switch ($doc_type)
        {
            case "quote":

                $quote_id = $args['quote_id'];
                $client_info = lh_queries::get_client_from_quote($quote_id);
                $client_name = $client_info['name'];
                $client_id = $client_info['client_id'];

                $full_path = 'mbm/clients/'.$client_id.'/quotes';

                // Create the filename
                $filename_temp = $quote_id.' Little House Quote '.$client_name.' '.date('Y-m-d').'.pdf';
                $pdfFileName = lh_crm_utils::sanitize_filename($filename_temp);
                $htmlStr = lh_draw::draw_quote_for_pdf($quote_id);

            break;

        }

    	//--- init TCpdf ---------------------------------------
    	//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf = new LITTLEHOUSE_PDF( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );


    	// set document information
    	$pdf->SetCreator('SSA-PDF(TC)');
    	$pdf->SetAuthor('');
    	$pdf->SetTitle( $pdfFileName );
    	$pdf->SetSubject('');
    	$pdf->SetKeywords('');

    	// set header data
    	$pdf->SetHeaderMargin( PDF_MARGIN_HEADER );
    	// set footer data
    	$pdf->SetFooterMargin( PDF_MARGIN_FOOTER );
    	// set default monospaced font
    	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    	// set margins
    	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    	// set auto page breaks
    	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    	// set image scale factor
    	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$pdf->AddPage();
		$pdf->writeHTML	(
			//$cssStr . $html,
            $htmlStr,
			true,
			false,
			false,
			false,
			''
		);

		//--- output the PDF ---------------------------------------
		//$basePath = $_SERVER['DOCUMENT_ROOT'] . 'ssapdf_tmp/blog' . $blogID;
		$WPuploads = wp_upload_dir();


        $wp_basepath = $WPuploads['basedir'];
        $wp_baseurl = $WPuploads['baseurl'];

        $create_path = $wp_basepath . '/' .$full_path;

		if ( ! file_exists( $create_path ) ) {
			mkdir( $create_path, 0777, true );
		}

		//temp set server limit and timeout
		ini_set("memory_limit", "1024M");
		ini_set("max_execution_time", "600");
		ini_set("allow_url_fopen", "1");

		//output PDF document.

        $final_file_path = $wp_basepath . '/' .$full_path. '/' . $pdfFileName;
        $final_file_url = $wp_baseurl . '/' .$full_path. '/' . $pdfFileName;

		$pdf->Output( $final_file_path, 'F');

		return array(
            "path" => $final_file_path,
            "URL" => $final_file_url,
        );
    }

}
?>
