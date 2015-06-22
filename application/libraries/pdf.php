<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class pdf {
    
    function pdf()
    {
        $CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }
 
    function load($param=NULL)
    {
        include_once 'mpdf/mpdf/mpdf.php';
         
        if ($params == NULL)
        {
            $param = '"en-GB-x","A4","","",10,10,10,10,6,3';         
        }
         
        return new mPDF($param);
    }

    function generate( $html = '', $fname = 'report.pdf')
    {

        //get style sheet
        $stylesheet = file_get_contents(base_url('public/css/style.css'));
        die($stylesheet);
        $obj = $this->load();
        $obj->WriteHTML($stylesheet,1); 
        $obj->WriteHTML($html); // write the HTML into the PDF
        $obj->Output($fname, 'D'); // save to file
    }
}