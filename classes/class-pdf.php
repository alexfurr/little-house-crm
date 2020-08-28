<?php


class lh_crm_pdf
{

    public static function create_pdf($quote_id)
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



        $filename = 'test';


        // Some default vars for the HTML emai
        $cellpadding = ' style="padding:5px;" ';

        $primary_color = "#acd037";
 	//clean up site title to use as filename
    	$pdfFileName = preg_replace( "/&#?[a-z0-9]{2,8};/i", "", $filename );
    	$pdfFileName = str_replace( " ", "-",  $pdfFileName );
    	$pdfFileName = str_replace( "/", "_",  $pdfFileName );

    	$pdfFileName = preg_replace('!\.pdf$!i', '', $pdfFileName );
    	$pdfFileName = $pdfFileName . ".pdf";
    	//$pdfFileName = "FrontendTesting.pdf";




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

    	// set default font subsetting mode
    	//$pdf->setFontSubsetting( true );




    	//--- build the contents ---------------------------------------


        //$htmlStr = lh_draw::draw_quote($quote_id);
        $htmlStr = lh_draw::draw_quote_for_pdf($quote_id);
        
        /*
        // Header
        $html = '';
        $logo_src = 'http://localhost/littlehouse/wp-content/uploads/2020/08/logo_white_back.png';
        $html.='<table style="margin:0px; background:'.$primary_color.';color:#fff;">';
        $html.='<tr>';
        $html.='<td style="width:70px; border:0px;">';
        $html.='<img src = "'.$logo_src.'" height="60px" width="60px">';
        $html.='</td>';
        $html.='<td style="border:0px; width:100%; font-size:24px;">Little House</td>';

		//set some extra document css
		$cssStr = '<style type="text/css"> ';
		$cssStr .= '.pageBreak { page-break-after: always; } ';
		$cssStr .= '* { font-family:' . $text_font . ';	} ';
		$cssStr .= 'a { color:' . $link_hex . '; } ';
		$cssStr .= 'a:visited { color:' . $link_hex . '; } ';
		$cssStr .= ' </style>';

        
        $htmlStr = 'PDF content';
        */
        
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
		$basePath = $WPuploads['basedir'];
		if ( ! file_exists( $basePath ) ) {
			mkdir( $basePath, 0777, true );
		}

		//temp set server limit and timeout
		ini_set("memory_limit", "1024M");
		ini_set("max_execution_time", "600");
		ini_set("allow_url_fopen", "1");

		//output PDF document.
		$pdf->Output( $basePath . '/' . $pdfFileName, 'F');

		return $basePath.'/'.$pdfFileName;
    }

}
?>
