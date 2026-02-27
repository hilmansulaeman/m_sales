<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//include_once APPPATH.'/third_party/mpdf/mpdf.php';

class M_pdf {

    public $param;
    public $pdf;

    /*public function __construct($param = '"en-GB-x","A4","","",10,10,10,10,6,3')
    {
        $this->param =$param;
        $this->pdf = new mPDF($this->param);
    }*/
	
	public function __construct()
    {
        $this->pdf = new \Mpdf\Mpdf(['mode'=>'',    // mode - default ''
		'format'=>'A4',    // format - A4, for example, default ''
		'default_font_size'=>0,     // font size - default 0
		'default_font'=>'',    // default font family
		'margin_left'=>10,    // margin_left
		'margin_right'=>10,    // margin right
		'margin_top'=>15,     // margin top
		'margin_bottom'=>0,    // margin bottom
		'margin_header'=>0,     // margin header
		'margin_footer'=>0,     // margin footer
		'orientation'=>'P']);  // L - landscape, P - portrait);
		
		$this->pdf2 = new \Mpdf\Mpdf(['mode'=>'',    // mode - default ''
		'format'=>'',    // format - A4, for example, default ''
		'default_font_size'=>0,     // font size - default 0
		'default_font'=>'',    // default font family
		'margin_left'=>20,    // margin_left
		'margin_right'=>10,    // margin right
		'margin_top'=>24,     // margin top
		'margin_bottom'=>20,    // margin bottom
		'margin_header'=>0,     // margin header
		'margin_footer'=>0,     // margin footer
		'orientation'=>'P']);  // L - landscape, P - portrait);
    }
}
