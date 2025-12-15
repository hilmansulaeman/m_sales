<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Addendum extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		$this->load->helper(array('url', 'html', 'file'));
		$this->load->library(array('f_pdf','template'));
        $this->load->model('Addendum_model');
        $this->Addendum_model->config('data_adendum_sales','adendum_id');
    }

    
    function index()
    {

		$username = $this->session->userdata('username'); 
		
		$posisi = $this->session->userdata('position');
        $var_code="";
		if ($posisi == "BSH" OR $posisi == "ASH") {
            $var_code = "BSH_Code";
        }
		elseif ($posisi == "RSM" ) {
            $var_code = "RSM_Code";
        }
		elseif ($posisi == "ASM" ) {
            $var_code = "ASM_Code";
        }
		elseif ($posisi == "SPV" ) {
            $var_code = "SPV_Code";
        }
		else{
            $var_code = "DSR_Code";
        }
        $this->template->set('title','addendum');
		$this->template->load('template','addendum/index_addendum');
    }


    function get_all_data()
    {
        $query = $this->Addendum_model->get_datatables();
        // var_dump($query); die();
        $data = array();
		$no = 1;
        foreach ($query->result() as $row){
		    
			$verification_status = $row->is_checked;

			if($verification_status == null){
			    $status = '<span class="label label-danger">No Status</span>';
			} else if($verification_status == '1'){
			    $status = '<span class="label label-success">Verified</span>';
			} else{
			    $status = '<span class="label label-warning">Unverified</span>';
			}
			
			$date = $row->updated_date;
            // var_dump($date); die();
            // $view = $this->sign_form();

			if($date == null){
			    $date = '-';
			} else {
				$date = date('d-m-Y', strtotime($date));
            }
            
            if ($row->is_checked == 0) {
                $buttons = '<a href="'.site_url('Addendum/view/'.$row->adendum_id).'" class="btn btn-success btn-icon btn-circle"><i class="fa fa-eye"></i></a>';
                $status = "<span class='label label-warning'>Not signed yet</span>";
            }else{
                $buttons = '<a href="'.site_url('Addendum/preview/'.$row->adendum_id).'" class="btn btn-success btn-icon btn-circle" target="_blank"><i class="fa fa-eye"></i></a>';
                $status = "<span class='label label-success'>Already signed</span>";
            }
            // var_dump($data);die();
            $data[] = array(
				$no++,				
                $row->template_name,
                $status,
				$buttons
			);
        }

		$output = array(

            "recordsTotal" => $this->Addendum_model->count_filtered(),
            "recordsFiltered" => $this->Addendum_model->count_filtered(),
            "data" => $data,
        );
		
       
        echo json_encode($output);
    }

    function view2($id)
    {

        $this->load->library('form_validation');

        $this->form_validation->set_rules('check_list', 'Setuju', 'required');

        if ($this->form_validation->run() == FALSE) {

            $query      = $this->Addendum_model->get_addendum_by_id($id);
            $userdata   = $query->row();

            if (!empty($userdata->agreement)) {
                
                $path     = './upload/agreement/';
                $filename = $userdata->agreement;
                // $path_signature = './upload/signature/';
                // $image =$userdata->signature;
                $src = base_url($path. $filename );
                // $src = base_url($path. $filename, $path_signature. $image );

            } else {
                $src = site_url("addendum/preview_save/$id");
            }


            $data['preview'] = $src;
            $data['query']   = $query;
            $data['id'] = $id;

            //load view
            $this->template->set('title','addendum');
            $this->template->load('template','addendum/view2',$data);

        } else {

            // Fungsi ketika tekan tombol simpan atau upload

            $this->preview_save($id, true); // Ini untuk upload;

        }
    }
    

    function view($id)
    {
		// $id = $this->session->userdata('sl_code');
        $query = $this->Addendum_model->get_addendum_by_id($id);
		$rows = $query->row();

        if ($rows->is_checked == 1) {
            // $this->session->set_flashdata('msg', 'Anda harus melengkapi tanda tangan terlebih dahulu!!!');

            $info = "Sehubungan dengan penambahan pasal pada perjanjian kemitraan, sesuai pasal dibawah ini, mohon dapat melakukan tanda tangan terlebih dahulu.<br><br>

            1. Melakukan setor dan tarik kembali saldo rekening dimana terindikasi menggunakan rekening pribadi, mitra lain dan Pihak Ketiga lainnya sehingga menimbulkan kerugian bagi PIHAK PERTAMA dan Klien PIHAK PERTAMA<br>
            2. Tidak diperbolehkan menawaran produk dan/atau layanan yang merugikan atau berpotensi merugikan calon customer dengan meyalahgunakan keadaan atau kondisi calon customer yang tidak memiliki pilihan lain dalam mengambil Keputusan seperti menawarkan program prakerja untuk kepentingan pribadi.<br>
            3. Tidak melakukan tindakan dan/atau perilaku yang memperkaya, menguntungkan dan/atau menyalagunakan kewengan, kesempatan dan/atau sarana atas dasar jabatan atau kedudukannya yang dapat mengakibatkan kerugian bagi customer.<br>
            4. Tidak menggunakan dan/atau memperjualbelikan rekening dan/atau atm milik nasabah maupun milik pribadi dari berbagai saranan untuk kepentingan pribadi dan/atau kegiatan judi online";
            
                        $this->session->set_flashdata('msg', $info);
            redirect('addendum');
        } else {
            $template_file = "";

            if($rows->agreement != ""){
                $template_file = $rows->agreement;
                
                $path     = 'upload/agreement/';
                $src = base_url($path. $template_file);

            } else {
                $src = site_url("addendum/doPreview/".$id);
                // $src = $this->preview($id);
            }
            // $row_signature= $rows->signature;
            // var_dump($row_signature); 
            // var_dump(isset($row_signature)); 
            // if(isset($row_signature)){
                // var_dump('masuk'); die;
            // }
            $data['preview'] = $src;
            $data['query']   = $query;
            $data['id'] = $id;
            $view = $this->sign_form();

            //load view
            $this->template->set('title','addendum');
            $this->template->load('template','addendum/'.$view, $data);
        }
    }
    function viewmobile($id)
    {
		// $id = $this->session->userdata('sl_code');
        $query = $this->Addendum_model->get_addendum_by_id($id);
		$rows = $query->row();
        
        $template_file = "";
       
        
        if($rows->agreement != ""){
            $template_file = $rows->agreement;
            
            $path     = 'upload/agreement/';
            $src = base_url($path. $template_file);

        } else {
            $src = site_url("addendum/doPreview/".$id);
            // $src = $this->preview($id);
        }
        // $row_signature= $rows->signature;
        // var_dump($row_signature); 
        // var_dump(isset($row_signature)); 
        // if(isset($row_signature)){
            // var_dump('masuk'); die;
        // }
        $data['preview'] = $src;
        $data['query']   = $query;
        $data['id'] = $id;
        // $view = $this->sign_form();


        //load view
        $this->template->set('title','addendum');
        $this->template->load('template','addendum/view_mobile',$data);
    }

    function pdf_viewer($id)
    {
		$template_name = $this->preview($id);
		$data['file'] = $template_name.'.pdf';
        $this->load->view('addendum/pdf_viewer', $data);
    }
	
    function pdf_viewer2($id)
    {
		$template_name = $this->preview_save($id);
		$data['file'] = $template_name.'.pdf';
        $this->load->view('addendum/pdf_viewer', $data);
    }

    function preview($id)
    {
        $query = $this->Addendum_model->get_addendum_by_id($id);
		$rows = $query->row();

        if ($rows->is_checked == 0) {
            $this->session->set_flashdata('msg', 'Dokumen tersebut belum di tanda tangan, silahkan tanda tangan dokumen lebih dulu!!!');
            redirect('addendum');
        } else {
            $this->doPreview($id);
        }
    }

    public function doPreview($id)
    {
        $data['query'] = $this->Addendum_model->get_addendum_by_id($id);
		$rows = $data['query']->row();

        $template_name     = $rows->template_name;
        $queryaddendum     = $this->Addendum_model->getAddendum($template_name);
        $row_addendum      = $queryaddendum->row();
        $total_page        = $row_addendum->Total_Page;
        $row_template_file = $row_addendum->Template_File;

        $source_file = FCPATH.'/admin/upload/agreement/' . $row_template_file;
        $row_template_id = $row_addendum->Template_ID;

        $get_page = $this->Addendum_model->get_page_mitra($row_template_id);					
        $row_page = $get_page[0]->Set_Page;		
        $page=[];

        foreach ($get_page as $row) {
            $page[] .= $row->Set_Page;
        }

        //get source file
        $this->f_pdf->pdf->setSourceFile($source_file);
        
        $n = 1;
        for ($i = $n; $i <= $total_page; $i++) {
            // add a page
            $this->f_pdf->pdf->AddPage();
            // import page 2
            $tplIdx = $this->f_pdf->pdf->importPage($i);
            
            // use the imported page and place it at position 10,10 with a width of 100 mm
            $this->f_pdf->pdf->useTemplate($tplIdx, 0, 0, null, null);

            //check page setup
            if (in_array($i, $page)) {
                //get fill text
                $text = $this->Addendum_model->get_data_mitra($row_template_id, $i, 'Text');
                // var_dump($text->result()); 
                foreach ($text->result() as $item) {
                    // var_dump($item); die();
                    $field_name_ = $item->Field_Name;
                    
                    if ( $field_name_ == 'Sign_Date' ) {
                        $field_name = $rows->updated_date;
                        if ($field_name == NULL){
                            $field_name = $this->ind_date(date('Y-m-d'));
                        }
                        else {
                            $hari1 = $rows->updated_date; {
                                $field_name=$this->ind_date(date('Y-m-d', strtotime( $hari1)));
                            }
                        }
                        
                    } else if( $field_name_ == 'Short_Date' ) {
                        $field_name = $rows->updated_date;
                        if ($field_name == NULL){
                            $field_name = $this->ind_date(date('Y-m-d'));
                        }
                        else {
                            $hari1 = $rows->updated_date; {
                                $field_name= $this->ind_date(date('Y-m-d', strtotime( $hari1)));
                            }
                        }
                        
                    }else if ($field_name_ == 'Day') {
                        $field_name = $rows->updated_date;
                        if ($field_name == NULL){
                            $field_name = $this->tgl_ind(date('Y-m-d H:i:s'), 'hari');
                        }
                        else {
                            $hari1 = $rows->updated_date; {
                                $field_name = $this->tgl_ind($hari1, 'hari');
                            }
                        }

                    } else if ($field_name_ == 'Date') {
                        $field_name = date('d');
                    } else if ($field_name_ == 'Month') {
                        $field_name = $this->tgl_ind(date('Y-m-d H:i:s'), 'bulan');
                    } else if ($field_name_ == 'Year') {
                        $field_name = date('Y');
                    }else if($field_name_ == 'PIC_Name'){
                        $field_name = $rows->pic_name;
                        // $field_name = 'non';
                    }else if($field_name_ == 'PIC_Position'){
                        $field_name = $rows->pic_position;
                        // $field_name = 'non';
                    }else if ($field_name_ == 'ID_Number') {
                        $field_name = $rows->no_ktp;
                    }else if ($field_name_ == 'Address_By_ID') {
                        $field_name= $rows->alamat;
                    }else if ($field_name_ == 'Name' ) {
                        
                        $field_name =$rows->sales_name;
                    
                    }else if ( $field_name_ == 'Partnership_Agreement_Date') {
                        $hari1 = $rows->join_date;{
                            // $field_name=  date('d' .' F' . ' Y', strtotime( $field_name));
                            $field_name=$this->ind_date(date('Y-m-d', strtotime( $hari1)));
                        }
                    }else if ($field_name_ == 'Nomor_Kuasa') {
                        $field_name =  $rows->no_surat;
                    }else if ($field_name_ == 'Long_Date') {
                        $field_name = $rows->updated_date;
                        if ($field_name == NULL){
                            $field_name = (ucwords($this->terbilang($this->tgl_aja(date('Y-m-d')))) . " bulan " . $this->bln_aja(date('Y-m-d')) . " tahun " . (ucwords($this->terbilang($this->thn_aja(date('Y'))))));
                        }else {
                            $hari1 = $rows->updated_date; {
                                $field_name = (ucwords($this->terbilang($this->tgl_aja($hari1))) . " bulan " . $this->bln_aja($hari1) . " tahun " . (ucwords($this->terbilang($this->thn_aja($hari1)))));
                            }
                            
                        }
                    }else if ($field_name_ == 'tanggal_latin') {
                        $field_name = $rows->updated_date;
                        if ($field_name == NULL){
                            $field_name = (ucwords($this->terbilang($this->tgl_aja(date('Y-m-d')))) );
                        }else {
                            $hari1 = $rows->updated_date; {
                                $field_name = (ucwords($this->terbilang($this->tgl_aja($hari1))) );
                            }
                            
                        }
                    }else if ($field_name_ == 'bulan_latin') {
                        $field_name = $rows->updated_date;
                        if ($field_name == NULL){
                            $field_name = (ucwords($this->bln_aja(date('Y-m-d')))) ;
                        }else {
                            $hari1 = $rows->updated_date; {
                                $field_name = (ucwords($this->bln_aja($hari1) ));
                            }
                            
                        }
                    }else if ($field_name_ == 'tahun_latin') {
                        $field_name = $rows->updated_date;
                        if ($field_name == NULL){
                            $field_name = (ucwords($this->terbilang($this->thn_aja(date('Y')))));
                        }else {
                            $hari1 = $rows->updated_date; {
                                $field_name = (ucwords($this->terbilang($this->thn_aja($hari1))) );
                            }
                            
                        }
                    }else if($field_name_ == 'Gender'){
                        $field_name = $rows->gender;
                    }else if($field_name_ == 'Place_Of_Birth'){
                        $field_name = $rows->place_of_birth;
                    }else if($field_name_ == 'Mobile_Phone_Number'){
                        $field_name = $rows->mobile_phone_number;
                    }else if($field_name_ == 'Branch'){
                        $field_name = $rows->branch;
                    }else if($field_name_ == 'Date_Of_Birth'){
                        $field_name = $rows->date_of_birth;
                    }else if($field_name_ == 'Agreement_Date'){
                        $field_name = date('Y-m-d');
                    }else if($field_name_ == 'Tanggal_Kuasa'){
                        $field_name = date('Y-m-d');
                    }else {
                        $field_name = $item->Set_Long == 0 ? $row_addendum->{$field_name_} : $this->custom_echo($row_addendum->{$field_name_}, $item->Set_Long);
                    }
                    if (is_array($field_name)) {
                        for ($j = 0; $j < count($field_name); $j++) {
                            $this->f_pdf->pdf->SetFont('Calibri', $item->Font_Type, '11');
                            $this->f_pdf->pdf->SetTextColor(0, 0, 0);
                            if ($j == 1) {
                                if ($item->Set_Page == 8) {
                                    $lineHeight = 9;
                                } else {
                                    $lineHeight = 4.5;
                                }
                                $this->f_pdf->pdf->SetXY($item->Set_X, ($item->Set_Y + ($lineHeight * $j)));
                            } else {
                                $this->f_pdf->pdf->SetXY($item->Set_X, $item->Set_Y);
                            }
                            $this->f_pdf->pdf->Write(0, $field_name[$j]);
                        }
                    } else {
                        $this->f_pdf->pdf->SetFont('Calibri', $item->Font_Type, '11');
                        $this->f_pdf->pdf->SetTextColor(0, 0, 0);
                        $this->f_pdf->pdf->SetXY($item->Set_X, $item->Set_Y);
                        $this->f_pdf->pdf->Write(0, $field_name);
                    }
                }

                $row_signature = $row_addendum->signature;
                
                // $getSignature = $this->Addendum_model->getSignature($recruitment);
                // $getSignature = $this->Addendum_model->getSignature($recruitment);
                $signature = $this->Addendum_model->get_data_mitra($row_template_id, $i, 'Signature');
                
                $signature_pic = 'https://one.ptdika.com/hrd/upload/signature_bsh/' . $row->PIC_Signature;
                
                // $signature_employee ='';

                if ($row_signature == '') {
                    $signature_employee =FCPATH .'upload/ttd/tes.png'; //dummyTTD
                }else{
                    $signature_employee =FCPATH .'upload/ttd/'.$row_signature; 
                }

                // $signature_employee =FCPATH .'upload/ttd/'.$row_signature;
                // var_dump($row_signature);die;
                foreach ($signature->result() as $item) {
                    
                    $signature_user_ = $item->Signature_User;

                    if ($signature_user_ == 'PIC') {
                        
                        $this->f_pdf->pdf->SetXY($item->Set_X, $item->Set_Y);
                        $this->f_pdf->pdf->Image($signature_pic, null, null, 29);
                    }

                    else if($signature_user_ == 'Employee'){ 
                        
                        // var_dump($item);die;
                        // if ($row->signature!="") {
                            
                        //     $signature_employee =FCPATH .'upload/ttd/'.$row_signature;;
                        // }

                        // else {
                            // var_dump("ada");die;
                            // $this->f_pdf->pdf->SetFont('Calibri','B', $item->Font_Type, '11');
                            $this->f_pdf->pdf->SetXY($item->Set_X, $item->Set_Y);
                            $this->f_pdf->pdf->Image($signature_employee, null, null, 70);
                        // }

                    }

                }

                // //get fill signature
                // // $recruitment = $rows->recruitment_id;
                // $row_signature = $row_addendum->signature;
                // // var_dump($row_signature);die();
                // // $getSignature = $this->Addendum_model->getSignature($recruitment);
                // // $getSignature = $this->Addendum_model->getSignature($recruitment);
                // $signature = $this->Addendum_model->get_data_mitra($row_template_id, $i, 'Signature');
                // // var_dump($signature); exit;
                // //$signature_pic = FCPATH.'/admin/upload/signature_bsh/' . $row->PIC_Signature;
                // $signature_pic = 'https://one.ptdika.com/hrd/upload/signature_bsh/' . $row->PIC_Signature;
                // // $signature_employee ='/var/www/html/development/public_html/dikaone/hrd/upload/signature/90524_ttd_1547721685.png' ;
                
                // $signature_employee =FCPATH .'upload/ttd/'.$row_signature;

                // foreach ($signature->result() as $item) {
                //     $signature_user_ = $item->Signature_User;
                //     if ($signature_user_ == 'PIC') {
                //         $this->f_pdf->pdf->SetXY($item->Set_X, $item->Set_Y);
                //         $this->f_pdf->pdf->Image($signature_pic, null, null, 29);
                //     }  
                //     elseif($signature_user_ == 'employee'){                
                //         $this->f_pdf->pdf->SetXY($item->Set_X, $item->Set_Y);
                //         $this->f_pdf->pdf->Image($signature_employee, null, null, 29);
                        
                //     }
                // }

                
            }
        }

        $pathfiletemplate = FCPATH.'admin/upload/agreement/'.$row_template_file;
        $file_name = $this->set_file_name($id);      
        $pathfile = FCPATH.'upload/agreement/'.$file_name;
        
        //Set Output
        if(!file_exists($pathfile)){
            $this->f_pdf->pdf->Output($pathfile, 'F');
        }else{
            $this->f_pdf->pdf->Output($pathfile, 'I');
        }
    }

    public function preview_save($id, $saveFile = false)
    {
        $data['query'] = $this->Addendum_model->get_addendum_by_id($id);
		$rows =$data['query']->row();

        $template_name = $rows->template_name;    
        $queryaddendum = $this->Addendum_model->getAddendum($template_name);
        $row_addendum = $queryaddendum->row();
        $total_page = $row_addendum->Total_Page;
        $row_template_file = $row_addendum->Template_File;
        
        $source_file = FCPATH.'/admin/upload/agreement/' . $row_template_file;
        $row_template_id = $row_addendum->Template_ID;

        $get_page = $this->Addendum_model->get_page_mitra($row_template_id);					
        $row_page = $get_page[0]->Set_Page;		
        $page=[];

        foreach ($get_page as $row) {
            $page[] .= $row->Set_Page;
        }

        //get source file
        $this->f_pdf->pdf->setSourceFile($source_file);
        
        $n = 1;
        for ($i = $n; $i <= $total_page; $i++) {
            // add a page
            $this->f_pdf->pdf->AddPage();
            // import page 2
            $tplIdx = $this->f_pdf->pdf->importPage($i);
            
            // use the imported page and place it at position 10,10 with a width of 100 mm
            $this->f_pdf->pdf->useTemplate($tplIdx, 0, 0, null, null);

            //check page setup
            if (in_array($i, $page)) {
                //get fill text
                $text = $this->Addendum_model->get_data_mitra($row_template_id, $i, 'Text');
                
                foreach ($text->result() as $item) {
                   
                    $field_name_ = $item->Field_Name;
                    
                    if ( $field_name_ == 'Sign_Date' ) {
                        $field_name = $rows->updated_date;
                        if ($field_name == NULL){
                            $field_name = $this->ind_date(date('Y-m-d'));
                        }
                        else {
                            $hari1 = $rows->updated_date; {
                                $field_name=$this->ind_date(date('Y-m-d', strtotime( $hari1)));
                            }
                        }
                        
                    } else if( $field_name_ == 'Short_Date' ) {
                        $field_name = $rows->updated_date;
                        if ($field_name == NULL){
                            $field_name = $this->ind_date(date('Y-m-d'));
                        }
                        else {
                            $hari1 = $rows->updated_date; {
                                $field_name= $this->ind_date(date('Y-m-d', strtotime( $hari1)));
                            }
                        }
                        
                    }else if ($field_name_ == 'Day') {
                        $field_name = $rows->updated_date;
                        if ($field_name == NULL){
                            $field_name = $this->tgl_ind(date('Y-m-d H:i:s'), 'hari');
                        }
                        else {
                            $hari1 = $rows->updated_date; {
                                $field_name = $this->tgl_ind($hari1, 'hari');
                            }
                        }


                    } else if ($field_name_ == 'Date') {
                        $field_name = date('d');
                    } else if ($field_name_ == 'Month') {
                        $field_name = $this->tgl_ind(date('Y-m-d H:i:s'), 'bulan');
                    } else if ($field_name_ == 'Year') {
                        $field_name = date('Y');
                    }else if($field_name_ == 'PIC_Name'){
                        $field_name = $rows->pic_name;
                    }else if($field_name_ == 'PIC_Position'){
                        $field_name = $rows->pic_position;
                    }else if ($field_name_ == 'ID_Number') {
                        $field_name = $rows->no_ktp;
                    }else if ($field_name_ == 'Address_By_ID') {
                        $field_name= $rows->alamat;
                    }else if ($field_name_ == 'Name' ) {
                        
                        $field_name =$rows->sales_name;
                    
                    }else if ( $field_name_ == 'Partnership_Agreement_Date') {
                        $hari1 = $rows->join_date;{                            
                            $field_name=$this->ind_date(date('Y-m-d', strtotime( $hari1)));
                        }
                    }else if ($field_name_ == 'Nomor_Kuasa') {
                        $field_name =  $rows->no_surat;

                    }else if ($field_name_ == 'Long_Date') {
                        $field_name = $rows->updated_date;
                        if ($field_name == NULL){
                            $field_name = (ucwords($this->terbilang($this->tgl_aja(date('Y-m-d')))) . " bulan " . $this->bln_aja(date('Y-m-d')) . " tahun " . (ucwords($this->terbilang($this->thn_aja(date('Y'))))));
                        }else {
                            $hari1 = $rows->updated_date; {
                                $field_name = (ucwords($this->terbilang($this->tgl_aja($hari1))) . " bulan " . $this->bln_aja($hari1) . " tahun " . (ucwords($this->terbilang($this->thn_aja($hari1)))));
                            }
                            
                        }                       
                    }else if ($field_name_ == 'tanggal_latin') {
                        $field_name = $rows->updated_date;
                        if ($field_name == NULL){
                            $field_name = (ucwords($this->terbilang($this->tgl_aja(date('Y-m-d')))) );
                        }else {
                            $hari1 = $rows->updated_date; {
                                $field_name = (ucwords($this->terbilang($this->tgl_aja($hari1))) );
                            }
                            
                        }
                         
                    
                    }else if ($field_name_ == 'bulan_latin') {
                        $field_name = $rows->updated_date;
                        if ($field_name == NULL){
                            $field_name = (ucwords($this->bln_aja(date('Y-m-d')))) ;
                        }else {
                            $hari1 = $rows->updated_date; {
                                $field_name = (ucwords($this->bln_aja($hari1) ));
                            }
                            
                        }

                    }else if ($field_name_ == 'tahun_latin') {
                        $field_name = $rows->updated_date;
                        if ($field_name == NULL){
                            $field_name = (ucwords($this->terbilang($this->thn_aja(date('Y')))));
                        }else {
                            $hari1 = $rows->updated_date; {
                                $field_name = (ucwords($this->terbilang($this->thn_aja($hari1))) );
                            }
                            
                        }

                    }else if($field_name_ == 'Gender'){
                        $field_name = $rows->gender;
                    }else if($field_name_ == 'Place_Of_Birth'){
                        $field_name = $rows->place_of_birth;
                    }else if($field_name_ == 'Mobile_Phone_Number'){
                        $field_name = $rows->mobile_phone_number;
                    }else if($field_name_ == 'Branch'){
                        $field_name = $rows->branch;
                    }else if($field_name_ == 'Date_Of_Birth'){
                        $field_name = $rows->date_of_birth;
                    }else if($field_name_ == 'Agreement_Date'){
                        $field_name = date('Y-m-d');
                    }else if($field_name_ == 'Tanggal_Kuasa'){
                        $field_name = date('Y-m-d');
                    }else {
                        $field_name = $item->Set_Long == 0 ? $row_addendum->{$field_name_} : $this->custom_echo($row_addendum->{$field_name_}, $item->Set_Long);
                    }
                    if (is_array($field_name)) {
                        for ($j = 0; $j < count($field_name); $j++) {
                            $this->f_pdf->pdf->SetFont('Calibri', $item->Font_Type, '11');
                            $this->f_pdf->pdf->SetTextColor(0, 0, 0);
                            if ($j == 1) {
                                if ($item->Set_Page == 8) {
                                    $lineHeight = 9;
                                } else {
                                    $lineHeight = 4.5;
                                }
                                $this->f_pdf->pdf->SetXY($item->Set_X, ($item->Set_Y + ($lineHeight * $j)));
                            } else {
                                $this->f_pdf->pdf->SetXY($item->Set_X, $item->Set_Y);
                            }
                            $this->f_pdf->pdf->Write(0, $field_name[$j]);
                        }
                    } else {
                        $this->f_pdf->pdf->SetFont('Calibri', $item->Font_Type, '11');
                        $this->f_pdf->pdf->SetTextColor(0, 0, 0);
                        $this->f_pdf->pdf->SetXY($item->Set_X, $item->Set_Y);
                        $this->f_pdf->pdf->Write(0, $field_name);
                    }

                   
                }
                //get fill signature
                $row_signature = $row_addendum->signature;

                $signature = $this->Addendum_model->get_data_mitra($row_template_id, $i, 'Signature');
               
                $signature_pic = 'https://one.ptdika.com/hrd/upload/signature_bsh/' . $row->PIC_Signature;
                
                // $signature_employee ='';
                $signature_employee = FCPATH .'upload/ttd/'.$row_signature;
               
                foreach ($signature->result() as $item) {
                    $signature_user_ = $item->Signature_User;
                    if ($signature_user_ == 'PIC') {
                        $this->f_pdf->pdf->SetXY($item->Set_X, $item->Set_Y);
                        $this->f_pdf->pdf->Image($signature_pic, null, null, 29);
                    }  
                    elseif($signature_user_ == 'Employee'){
                        $this->f_pdf->pdf->SetXY($item->Set_X, $item->Set_Y);
                        $this->f_pdf->pdf->Image($signature_employee, null, null, 29);
                        
                    }
                }
            }
        }


        if (!$saveFile) {

            $pathfile = FCPATH.'/admin/upload/agreement/'.$template_name;
        } else {

            $file_name = $this->set_file_name($id);
            $pathfile = FCPATH.'/admin/upload/agreement/'.$file_name;
        }
      
        if($saveFile){
            $this->f_pdf->pdf->Output($pathfile, 'F');
        } else {
            $this->f_pdf->pdf->Output($pathfile, 'I');
        }
    }

  
	public function tgl_ind($date, $type)
    {

        // array hari dan bulan
        $Hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
        $Bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

        // pemisahan tahun, bulan, hari, dan waktu
        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl = substr($date, 8, 2);
        $waktu = substr($date, 11, 5);
        $hari = date("w", strtotime($date));

        if ($type == 'hari') {
            $result = $Hari[$hari];
        } else if ($type == 'bulan') {
            $result = $Bulan[(int) $bulan - 1];
        } else if ($type == 'tahun') {
            $result = $tahun;
        } else {
            $result = $Hari[$hari] . ", " . $tgl . " " . $Bulan[(int) $bulan - 1] . " " . $tahun . " " . $waktu . " " . "Wib";
        }
        return $result;
    }

	public function custom_echo($x, $length)
    {
        $sets = array();
        if (strlen($x) <= $length) {
            return $x;
        } else {
            $sets[] = substr($x, 0, $length);
            $sets[] = substr($x, $length);
            return $sets;
        }
    }

	private function ind_date($date)
    {
        $month = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        );
        $explode = explode('-', $date);

       

        return $explode[2] . ' ' . $month[(int) $explode[1]] . ' ' . $explode[0];
    }
    
    function submit()
    {
        $sl_code     = $this->session->userdata('sl_code');
        $adendum_id  = $this->input->post('adendum_id');

        $query       = $this->Addendum_model->get_addendum_by_id($adendum_id);
		$row_adendum = $query->row();

        $file_name = $this->set_file_name($adendum_id);        
        $pathfile = FCPATH.'/upload/agreement/'.$file_name;

        
		$upload_image = FCPATH.'/upload/ttd/';
		// $id_image = $this->input->post('sales_code');
		
		$img = $this->input->post('output');
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data_image = base64_decode($img);
		$filename = $sl_code."_ttd_".time().".png";
        // var_dump($filename); die();
		$path = $upload_image .$filename;        
		$success = file_put_contents($path, $data_image);
       

        $data_update = array(
			'is_checked'	=> 1,
			'updated_date'	=> date('Y-m-d H:i:s'),
            'agreement'     => $file_name,
            'Signature'	    => $filename,			
		);

        $this->Addendum_model->update($data_update, $row_adendum->adendum_id);
        $data['message'] = "Data Tersimpan";
		
		echo json_encode(array("status" => TRUE));
        
    }

    private function set_file_name($adendum_id, $type = "agreement")
    {
        $sales_code = $this->session->userdata('sl_code');
		$temp = date('m');
        // $getAgreementTemplate = $this->Addendum_model->get_all_data();
        $getAgreementTemplate = $this->Addendum_model->get_data_adendum($adendum_id, $sales_code);
        $row_template         = $getAgreementTemplate->row();
        $template             = $row_template->template_name;

		//var_dump($template);die();
		// return $sales_code . '_Ex' . $type . '_' . $temp . '.pdf';
		// return 'files_' . $sales_code . '_' . $temp . '_' . $template .'.pdf';
		return 'files_' . $sales_code . '_' . $temp . '_' . $template .'.pdf';
    }

    
    function penyebut($nilai) {
        $nilai  = abs($nilai);
        $huruf  = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        $temp   = "";
        if ($nilai < 12) {
          $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
          $temp = $this->penyebut($nilai - 10). " belas";
        } else if ($nilai < 100) {
          $temp = $this->penyebut($nilai / 10)." puluh". $this->penyebut($nilai % 10);
        } else if ($nilai < 200) {
          $temp = " seratus" . $this->penyebut($nilai - 100);
        } else if ($nilai < 1000) {
          $temp = $this->penyebut($nilai / 100) . " ratus" . $this->penyebut($nilai % 100);
        } else if ($nilai < 2000) {
          $temp = " seribu" . $this->penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
          $temp = $this->penyebut($nilai / 1000) . " ribu" . $this->penyebut($nilai % 1000);
        } 
        
        return $temp;
      }
    
      function terbilang($nilai) {
        if ($nilai < 0) {
          $hasil = "minus " . trim($this->penyebut($nilai));
        } else {
          $hasil = trim($this->penyebut($nilai));
        }
        return $hasil;
      }
    
      // Fungsi ambil tanggal aja
      function tgl_aja($tgl) {
        $tanggal = substr($tgl, 8, 2);
        return $tanggal;
      }
    
      // Fungsi Ambil bulan aja
      function bln_aja($bulan) {
        $bulan = $this->getBulan(substr($bulan, 5, 2));
        return $bulan;
      }
    
      // Fungsi Ambil tahun aja
      function thn_aja($thn) {
        $tahun = substr($thn, 0, 4);
        return $tahun;
      }
      function longDate($today) {
        return "tanggal " . $this->terbilang($this->tgl_aja($today)) . " bulan " . $this->bln_aja($today) . " tahun " . $this->terbilang($this->thn_aja($today));
      }
      function getBulan($bln) {
        $bulan = [
          1 => "Januari",
          "Februari",
          "Maret",
          "April",
          "Mei",
          "Juni",
          "Juli",
          "Agustus",
          "September",
          "Oktober",
          "November",
          "Desember"
        ];
        return $bulan[(int) $bln];
      }
    private function isMobile() 
     {
	    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	  }

	private function sign_form()
	{
		if($this->isMobile()){
			$view = 'view_mobile';
		}
		else{
			$view = 'view2';
		}
		return $view;
	}
}
