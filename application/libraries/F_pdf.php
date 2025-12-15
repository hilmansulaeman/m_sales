<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use setasign\Fpdi\Fpdi;

require './vendor/setasign/fpdf/fpdf.php';
require './vendor/setasign/fpdi/src/autoload.php';

class F_pdf {

    public $pdf;
	
		public function __construct()
    {
        $this->pdf = new Fpdi();
				$this->pdf->AddFont('Calibri','','calibri.php');
				$this->pdf->AddFont('Calibri','B','calibrib.php');
				$this->pdf->AddFont('Times','','times.php');
				$this->pdf->AddFont('Times','B','timesb.php');
    }
}
