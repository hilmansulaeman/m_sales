<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Absen extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'html', 'file','camera'));
        $this->load->library(array('template'));
        $this->load->model(['absen_model']);
        date_default_timezone_set('Asia/Jakarta');
    }

    function index($dsr_code)
    {
        // $branch['branch'] = $this->db->query("SELECT DISTINCT branch FROM db_welma.ref_branch")->result();;
    
        // var_dump($jabodetabek);
        $kategori = $this->uri->segment(2);
        $sales_code = $this->session->userdata('username');

        $date = date('Y-m-d');
        //$date_ = date('Y-m-d', strtotime('-1 day', strtotime($date)));
		
        if ($kategori == 'masuk' or $kategori == "sakit" or $kategori == 'izin' or $kategori == 'off') {

            $cek_absen = $this->db->query("SELECT * FROM data_absen WHERE sales_code = '$sales_code' 
									AND kategori = 'masuk' AND created_date LIKE '%$date%' AND approved_status IN('','Rejected') ");
            $absen_masuk = $cek_absen->num_rows();

            $cek_absen2 = $this->db->query("SELECT * FROM data_absen WHERE sales_code = '$sales_code' 
									AND kategori = 'izin' AND created_date LIKE '%$date%' ");
            $izin = $cek_absen2->num_rows();

            $cek_absen3 = $this->db->query("SELECT * FROM data_absen WHERE sales_code = '$sales_code' 
									AND kategori = 'off' AND created_date LIKE '%$date%' ");
            $off = $cek_absen3->num_rows();

            $cek_absen5 = $this->db->query("SELECT * FROM data_absen WHERE sales_code = '$sales_code' 
									AND kategori = 'sakit' AND created_date LIKE '%$date%' ");
            $sakit = $cek_absen5->num_rows();

            $cek_absen4 = $this->db->query("SELECT * FROM data_absen WHERE sales_code = '$sales_code' 
									AND kategori = 'masuk' AND created_date LIKE '%$date%' OR approved_status = 'Approved' ");
            $cek_request = $cek_absen4->num_rows();

        } else if ($kategori == 'pulang') {
            $cek_absen_masuk = $this->db->query("SELECT * FROM data_absen WHERE sales_code = '$sales_code' 
									AND kategori = 'masuk' AND created_date LIKE '%$date%' ");
            $absen_masuk = $cek_absen_masuk->num_rows();

            $cek_absen = $this->db->query("SELECT * FROM data_absen WHERE sales_code = '$sales_code' 
									AND kategori = 'pulang' AND created_date LIKE '%$date%' ");
            $absen_pulang = $cek_absen->num_rows();
        }

        if ($kategori == 'masuk' and $absen_masuk > 0) {
            echo ("<script LANGUAGE='JavaScript'>
									window.alert('Anda sudah melakukan absen !!');
									window.location.href='../';
									</script>");
        } else if ($kategori == 'pulang' and $absen_pulang > 0) {
            echo ("<script LANGUAGE='JavaScript'>
									window.alert('Anda sudah absen pulang !!');
									window.location.href='../';
									</script>");
        } else if (($kategori == 'izin' or $kategori == 'masuk' or $kategori == 'off' or $kategori == "sakit") and $izin > 0) {
            echo ("<script LANGUAGE='JavaScript'>
									window.alert('Anda sedang izin !!');
									window.location.href='../';
									</script>");
        } else if (($kategori == 'off' or $kategori == 'izin' or $kategori == 'masuk' or $kategori == "sakit") and $off > 0) {
            echo ("<script LANGUAGE='JavaScript'>
									window.alert('Anda sedang off !!');
									window.location.href='../';
									</script>");
        } else if (($kategori == 'off' or $kategori == 'izin' or $kategori == 'masuk' or $kategori == "sakit") and $sakit > 0) {
            echo ("<script LANGUAGE='JavaScript'>
									window.alert('Anda sedang sakit !!');
									window.location.href='../';
									</script>");
        } else {
			
            $this->load->library('form_validation');
            $this->form_validation->set_rules('sales_code', 'Sales Code', 'required');
            $this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

            if ($this->form_validation->run() == FALSE) {
                $data['branch'] = $this->absen_model->get_branch($dsr_code);
                $data['jb'] = $this->absen_model->get_branch_jabodetabek();
                $data['sb'] = $this->absen_model->get_branch_surabaya();
                $data['sm'] = $this->absen_model->get_branch_semarang();
                $data['md'] = $this->absen_model->get_branch_medan();
                $data['ml'] = $this->absen_model->get_branch_malang();
                $data['mk'] = $this->absen_model->get_branch_makassar();
                $data['bd'] = $this->absen_model->get_branch_bandung();
				//echo ("v");die();
                //load view
                $this->template->set('title', 'Absen');
                $this->template->load('template', 'absen/index', $data);
            }
			else {

				$config['file_name'] = $this->set_file_name_absen();

                $config['upload_path'] = './upload/temp/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']     = '8000';
               
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
					
                $this->load->library('image_lib');
                $this->load->helper('camera');
                

                if (!$this->upload->do_upload('foto')) {
					
					$data['error'] = $this->upload->display_errors();
					$this->session->set_flashdata('message',$data['error']);
					redirect('');
                }
                
				else {
					 
					$upload_data = $this->upload->data();
					$file_name = $upload_data['file_name'];
					$image_info = getimagesize($upload_data['full_path']);
					
					$image_width = $image_info[0];
					$image_height = $image_info[1];
					$new_width = $image_width * (60 / 100);
					$new_height = $image_height * (60 / 100);
					$geoInfo = $this->get_image_location('./upload/temp/' . $file_name);
					
					$imgLat = $geoInfo['latitude'];
					$imgLng = $geoInfo['longitude'];
					$geoLocation = $imgLat . ',' . $imgLng;
					
					/*if ($imgLat == null AND $imgLng == null)
					{
						echo ("<script LANGUAGE='JavaScript'>
						window.alert('Aktifkan GPS pada perangkat !!');
						window.location.href='$kategori';
						</script>");
						unlink('./upload/temp/' . $upload_data['file_name']);
						die;
					}
					else
					{*/
						// set the resize config
						$resize_conf = array(
							'source_image'  => $upload_data['full_path'],
							'new_image'     => './upload/absen/' . $upload_data['file_name'],
							'width'         => $new_width,
							'height'        => $new_height
						);
					 
						//$this->image_lib->initialize($config);
						$this->image_lib->initialize($resize_conf);
						
						// do it!
						if (!$this->image_lib->resize()) {
							// if got fail.
							$data['message'] = $this->image_lib->display_errors();
						}
						else {
							$kategori = $this->uri->segment(2);
							$camera = cameraUsed('./upload/temp/' . $upload_data['file_name']);
							$camera_date = $camera['date'];
							//cekvar($camera_date);

							$data_absen = array(
								'sales_code'            => $this->session->userdata('username'),
								'name'                  => $this->session->userdata('realname'),
								'position'              => $this->session->userdata('position'),
								'kategori'              => $kategori,
								'foto'                  => $file_name,
								'created_date_foto'     => $camera_date,
								'geo_info'              => $geoLocation,
								'office_branch'	        => $this->input->post('office_branch'),
								'keterangan'	        => $this->input->post('keterangan'),
								'branch'		        => $this->session->userdata('branch'),
								'user_agent'            => $_SERVER['HTTP_USER_AGENT']
							);
							$this->db->trans_start();
							$this->db->insert('data_absen', $data_absen);
							$id = $this->db->insert_id();

							$this->db->trans_complete();
							$this->session->set_flashdata('message','Data Berhasil Di upload');
							unlink('./upload/temp/' . $upload_data['file_name']);
							redirect('');
						}
					//}
				}
            }
        }
    }

    function get_office_branch(){
        $id_branch=$this->input->post('id_branch');
        $data=$this->absen_model->get_office_branch($id_branch);
        echo json_encode($data);
    }
    
    private function set_file_name_absen()
    {
        if (!empty($_FILES['foto']['name'])) {
            $file = $_FILES['foto']['name'];
            $gabung = str_replace(" ", "_", "$file");
            $pisah = explode(".", $gabung);
            $nama = $pisah[0];
            $temp = md5(time() . $nama);
            $ext = end($pisah);
        } else {
            $temp = '';
            $ext = '';
        }
        return $temp . '.' . $ext;
    }

    /**
     * get_image_location
     * Returns an array of latitude and longitude from the Image file
     * @param $image file path
     * @return multitype:array|boolean
     */
    private function get_image_location($image = '')
    {
        $exif = exif_read_data($image, 0, true);

        if ($exif && isset($exif['GPS'])) {
            $GPSLatitudeRef = $exif['GPS']['GPSLatitudeRef'];
            $GPSLatitude    = $exif['GPS']['GPSLatitude'];
            $GPSLongitudeRef = $exif['GPS']['GPSLongitudeRef'];
            $GPSLongitude   = $exif['GPS']['GPSLongitude'];

            $lat_degrees = count($GPSLatitude) > 0 ? $this->gps2Num($GPSLatitude[0]) : 0;
            $lat_minutes = count($GPSLatitude) > 1 ? $this->gps2Num($GPSLatitude[1]) : 0;
            $lat_seconds = count($GPSLatitude) > 2 ? $this->gps2Num($GPSLatitude[2]) : 0;

            $lon_degrees = count($GPSLongitude) > 0 ? $this->gps2Num($GPSLongitude[0]) : 0;
            $lon_minutes = count($GPSLongitude) > 1 ? $this->gps2Num($GPSLongitude[1]) : 0;
            $lon_seconds = count($GPSLongitude) > 2 ? $this->gps2Num($GPSLongitude[2]) : 0;

            $lat_direction = ($GPSLatitudeRef == 'W' or $GPSLatitudeRef == 'S') ? -1 : 1;
            $lon_direction = ($GPSLongitudeRef == 'W' or $GPSLongitudeRef == 'S') ? -1 : 1;

            $latitude = $lat_direction * ($lat_degrees + ($lat_minutes / 60) + ($lat_seconds / (60 * 60)));
            $longitude = $lon_direction * ($lon_degrees + ($lon_minutes / 60) + ($lon_seconds / (60 * 60)));

            return array('latitude' => $latitude, 'longitude' => $longitude);
        } else {
            return false;
        }
    }

    private function gps2Num($coordPart)
    {
        $parts = explode('/', $coordPart);
        if (count($parts) <= 0)
            return 0;
        if (count($parts) == 1)
            return $parts[0];
        return floatval($parts[0]) / floatval($parts[1]);
    }


}
