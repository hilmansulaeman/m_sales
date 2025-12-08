<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Preview extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function pemol()
    {
        $this->load->view('preview_pemol');
    }
}
