<?php
/**
*   Class SSA_PDF
*   ---
*   Extends TCPDF class. Sets document headers footers, and some other bits.
*
*/

class LITTLEHOUSE_PDF extends TCPDF
{
    
    var $header_type = 'default';
    var $footer_type = 'default';
    
    
	var $bg_rgb = array(
		'red' 	=> 172,
		'green' => 208,
		'blue' 	=> 55 
	);

	var $text_font = 'helvetica';
	var $text_hex = '#363636';
	var $link_hex = '#3333ff';

	var $text_rgb = array(
		'red' 	=> 40,
		'green' => 40,
		'blue' 	=> 40 
	);
	
	var $link_rgb = array(
		'red' 	=> 40,
		'green' => 40,
		'blue' 	=> 255 
	);
		
	var $displayDate = '';
	var $siteURL = '';
	var $siteTitle = '';
	
	
	//--- custom header
	public function Header()
	{
		$bMargin = $this->getBreakMargin(); 		//get the current page break margin
		$auto_page_break = $this->AutoPageBreak; 	//get current auto-page-break mode
		$this->SetAutoPageBreak(false, 0); 			//disable auto-page-break						
		        
        if ( 'full' === $this->header_type ) {
            // EG. full page colour bg.
            $this->Rect	( 
                0,
                0,
                210,
                297,
                'F',
                array(),
                array( $this->bg_rgb['red'], $this->bg_rgb['green'], $this->bg_rgb['blue'] ) 
            );
            $this->Image( LH_PLUGIN_PATH . '/images/lh_logo.png', 54, 50, 100, '', '', 'http://littlehousebristol.co.uk', '', false, 300);
        
        } else {
            // Default header.
            $this->Rect	( 
                0,
                0,
                210,
                18,
                'F',
                array(),
                array( $this->bg_rgb['red'], $this->bg_rgb['green'], $this->bg_rgb['blue'] ) 
            );
            
            $this->Image( LH_PLUGIN_PATH . '/images/lh_logo.png', 1, 1, 16, '', '', 'http://littlehousebristol.co.uk', '', false, 300);
            
            $text_rgb = array(
                'red' 	=> 255,
                'green' => 255,
                'blue' 	=> 255 
            );
            $this->writeHTML( '<h1 style="color: rgb(255, 255, 255);font-weight:normal;">&nbsp;&nbsp;&nbsp;Little House</h1>', true, false, true, false, '' );
        }
                
		$this->SetAutoPageBreak( $auto_page_break, $bMargin ); 	//restore auto-page-break status
		$this->setPageMark(); 									//set the starting point for the page content
	}
	
	
	//--- custom footer
	public function Footer ()
	{
		$this->SetY( -15 ); //position 15 mm from bottom		
		$this->SetFont( $this->text_font , 'N', 8 );

		$this->SetTextColorArray	(
			array( $this->text_rgb['red'], $this->text_rgb['green'], $this->text_rgb['blue'] ),
			false
		);
		
        $this->Cell( 10, 10, 'Little House Bristol', 0, false, 'L', 0, '', 0, false, 'T', 'M' );
		$this->Cell( 176, 10, 'Page '.$this->getAliasNumPage(), 0, false, 'R', 0, '', 0, false, 'T', 'M' );
		
		$style = array( 'width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 1, 'color' => array($this->bg_rgb['red'], $this->bg_rgb['green'], $this->bg_rgb['blue']) );
		$this->Line( PDF_MARGIN_LEFT, 282, (210-PDF_MARGIN_RIGHT), 282, $style);
	}
	
} 


?>
