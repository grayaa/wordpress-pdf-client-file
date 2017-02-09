<?php
// Include the main TCPDF library and TCPDI.
require_once plugin_dir_path( __FILE__ ).'/lib/tcpdf/tcpdf.php';
require_once plugin_dir_path( __FILE__ ).'/lib/tcpdf/tcpdi.php';

global $current_user;

if (get_attached_file( get_field('pdf_file_id') )) {
    
    $pdf_file = get_attached_file( get_field('pdf_file_id') );

    if ( mime_content_type( $pdf_file ) !="application/pdf" ) {
        die('Filetype Not supported, Must be pdf file !');
    }

    if (!is_user_logged_in ()) {
        die('User is not logged in !');
    }

    $fullPathToFile = get_attached_file( get_field('pdf_file_id'));
    //var_dump($fullPathToFile);die();

    class PDF extends tcpdi {

        var $_tplIdx;

        function Header() {

            global $fullPathToFile;

            if (is_null($this->_tplIdx)) {

                // THIS IS WHERE YOU GET THE NUMBER OF PAGES
                $this->SetDisplayMode('fullwidth');
                $this->numPages = $this->setSourceFile($fullPathToFile);
                $this->_tplIdx = $this->importPage(1);

            }
            $this->useTemplate($this->_tplIdx, 0, 0);

        }

        function Footer() {

        }

    }


    // initiate PDF
    $pdf = new PDF();

    // add a page
    $pdf->AddPage();


    // The new content
    $pdf->SetFont("helvetica", "", 10);
    $pdf->SetTextColor(255, 0, 0);

    $text_to_display = 'This document is being provided for the exclusive use of '.$current_user->data->display_name.' ( '.$current_user->data->user_email.' )'; 
    // THIS PUTS THE REMAINDER OF THE PAGES IN
    if($pdf->numPages>1) {
        for($i=2;$i<=$pdf->numPages;$i++) {
            //$pdf->endPage();
            //$pdf->Text($text_to_display);
            $pdf->Cell(0, 32, $text_to_display, 0, $ln=2, 'C', 0, '', 0, false, 'T', 'C');
            // Centered text in a framed 20*10 mm cell and line break
            //$pdf->Cell(100,100,'Title',1,1,'C');
            $pdf->_tplIdx = $pdf->importPage($i);
            $pdf->AddPage();
        }
    }

    //show the PDF in page
    //$pdf->Output();

    // or Output the file as forced download
    //$pdf->Output("sampleUpdated.pdf", 'D');

    $pdf_file_title = get_the_title();
    ob_end_clean(); 
    $pdf->Output($pdf_file_title.".pdf", 'I');

}else{
    
    die('No pdf file found !');
}
