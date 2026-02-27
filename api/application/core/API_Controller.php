<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '../vendor/autoload.php';
require APPPATH . '/libraries/REST_Controller.php';
use \Firebase\JWT\JWT;

class API_Controller  extends  CI_Controller {

    var $path_en;

    private $client_service;
    private $auth_key;
    private $content_type;
    protected $authorization;
    
    protected $secretkey;

    function __construct()
    {
        parent::__construct();
        //Load config
        $this->load->config('xml');

        //load untuk keperluan di inc/menu
        $this->load->model('user_mod');

        //Load helper
        $this->load->helper('frontend');

        $this->config->set_item('language', 'indonesia');

        // set key from XML Config
        $this->client_service = xml('client_service');
        $this->auth_key       = xml('auth_key');
        $this->content_type   = xml('content_type');
        $this->secretkey      = xml('secretkey');
        
        //path
        $this->path_en = 'backend/';

        // check auth
        $this->check_auth_client();        

    }

    // check API header
    function check_auth_client() 
    {
        $client_service_header = $this->input->get_request_header('Client-service', TRUE);
        $auth_key_header = $this->input->get_request_header('Auth-key', TRUE);
        $content_type_header = $this->input->get_request_header('Content-Type', TRUE);

        $form_data = explode(';',$content_type_header);
        
        if($content_type_header == $this->content_type && $client_service_header == $this->client_service && $auth_key_header == $this->auth_key){
            return TRUE;
        }
        elseif($form_data[0] == "multipart/form-data" && $client_service_header == $this->client_service && $auth_key_header == $this->auth_key){
            return TRUE;
        }
        else{
            // return $this->response($this->failed('Unauthorized Headers.'),404);
            echo json_output(array(
                "code"=> "404",
                "status"=> FALSE,
                "message"=> "Unauthorized@MYController",
            ));
            exit;          
        }
    }

    // method untuk mengecek token setiap melakukan post, put, etc
    public function check_token(){

        $this->load->model('user_mod');
        $this->load->model('company_mod');

        $jwt = $this->input->get_request_header('Authorization');

        $this->authorization = $jwt;
        // pre($this->authorization );
        try {
            $decode = JWT::decode($jwt,$this->secretkey,array('HS256'));
            
            // user Accounts type
            if (!empty($decode->user_type) && $decode->user_type == 1 && !empty($this->user_mod->get_byemail($decode->email))) {
                return TRUE;
            }
            elseif (!empty($decode->user_type) && $decode->user_type == 1 && !empty($this->user_mod->get_byphone($decode->phone))) {
                return TRUE;
            }

            // COMPANY type
            elseif (!empty($decode->user_type) && $decode->user_type == 2 && !empty($this->company_mod->get_byemail($decode->email))) {
                return TRUE;
            }
            elseif (!empty($decode->user_type) && $decode->user_type == 2 && !empty($this->company_mod->get_byphone($decode->phone))) {
                return TRUE;
            }
            else{
                exit(json_encode($this->failed("Invalid User Type"), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }

        } catch (Exception $e) {
            exit(json_encode($this->failed("Token is expired or invalid Token"), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
    }

    public function decode_token($token){
        return JWT::decode($token,$this->secretkey,array('HS256'));
    }

    public function image_upload($input_name, $path)
    {
        $config['upload_path']  = $path;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = '5000';

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload($input_name)) 
        {
            $err = str_replace('<p>','',$this->upload->display_errors());
            $err = str_replace('</p>','',$err);

            return array('status' => false ,'msg' => $err);
        }
        else
        {
            $data = $this->upload->data();
            
            $array = array(
                'status'    => true,
                'msg'       => '',
                'file_name' => $data['file_name'],
                'file_type' => $data['file_type'],
                'file_size' => $data['file_size']
            );
            return $array;
        }
    }

    public function document_upload($input_name, $path)
    {
        $config['upload_path']  = $path;
        $config['allowed_types'] = 'jpg|jpeg|png|pdf|zip';
        $config['max_size'] = '5000';

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload($input_name)) 
        {
            $err = str_replace('<p>','',$this->upload->display_errors());
            $err = str_replace('</p>','',$err);

            return array('status' => false ,'msg' => $err);
        }
        else
        {
            $data = $this->upload->data();
            
            $array = array(
                'status'    => true,
                'msg'       => '',
                'file_name' => $data['file_name'],
                'file_type' => $data['file_type'],
                'file_size' => $data['file_size']
            );
            return $array;
        }
    }

    // login check
    function is_logged_in()
    {
        if(!is_membership())
        {
            $url_callback = web_url('account/login?url='.uri_string());
            if(isset($_SERVER['QUERY_STRING'])){
               $url_callback .= !empty($_SERVER['QUERY_STRING']) ? '?' .$_SERVER['QUERY_STRING'] : ''; 
            }
            echo json_output(array(
                "code"=> "404",
                "status"=> "Failed",
                "message"=> "Login has been Expired",
            ));
            exit();   
        }
    }
    
    /*
    * Pagination function
    * $base_url = url utama yang nantinya akan ditambahkan $_GET per_page
    * $total_rows = jumlah semua rows
    * $per_page = jumlah row yang akan ditampilkan
    * $cur_page = page yang sedang aktif
    */
    function pagination($base_url,$total_rows,$per_page,$cur_page)
    {
        //Load library
        $this->load->library('pagination');
        //set config
        $config['base_url'] = $base_url;
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['cur_page'] = $cur_page;
        $config['page_query_string'] = TRUE;
        
        $config['next_link'] = '<span aria-hidden="true">&raquo;</span>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        
        $config['prev_link'] = '<span aria-hidden="true">&laquo;</span>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        
        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';
        
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        
        $config['first_link'] = '';        
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        
        $config['last_link'] = '';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        
        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }

    /********
    Generate code with JWT Library

    Example: 
    
    RETURN (string) eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX3R5cGUiOjIsImlkIjoiNCIsImVtYWlsIjoiZ29vQGdtYWlsLmNvbSIsImlhdCI6MTUyNzQwNjM5NSwiZXhwIjoxNTI3NDA5OTk1fQ.1_o036rH5dUeq2QKZ54VQrPyJrSv86IWefogmXPCXp0
    
    RETURN DECODE :
    stdClass Object
    (
        [user_type] => 2  // user type (1 : User Account OR 2 : Company Account)
        [id] => 4 // user ID
        [email] => goo@gmail.com // user Email
        [iat] => 1527406395 // date created in timestamp
        [exp] => 1527409995 // date expired in timestamp
    )
    ********/
    function generate_token($user_type, $user_id, $user_email=null,$user_phone=null,$other=null){
        $date = new DateTime();

        $payload['user_type'] = $user_type;
        $payload['id']        = $user_id;
        $payload['email']     = $user_email;
        $payload['phone']     = $user_phone;
        $payload['other']     = $other;
        $payload['iat']       = $date->getTimestamp(); //waktu di buat
        // $payload['exp']       = $date->getTimestamp() + 3600; //satu jam
        $payload['exp']       = NULL; //satu jam

        if(!empty($password)) $payload['password'] = trim($password);

        return JWT::encode($payload,$this->secretkey);
    }

    function set_token($user_id,$code)
    {
        return md5($user_id ."#". $code ."#". xml('generate_token_key'));
    }

    function set_code($length)
    {
        $characters = "12345678909";
        $string ='';
        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, 10)];
        }

        return $string;
    }

    function encode_password($string)
    {
        $string .= xml('encryption_key');
    // Return the SHA-1 encryption
        return sha1($string);
    }

    // APIsucces message response
    public function response($response = NULL, $http_code = NULL)
    {
        $this->output->set_status_header($http_code);
        $this->output->set_content_type('application/json', 'utf-8');
        $this->output->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function success($message = NULL, $data=FALSE)
    {
        return array(
            'code'=> '200',
            'message'=> strip_tags($message),
            'data'=> $data,
        );
    }

    public function failed($message = NULL, $data = null)
    {
        return array(
            'code'=> '404',
            'message'=> strip_tags($message),
            'data'=> $data,
        );
    }

    /******
    Get data token atau get Data user yang sedang aktif/login
    *******/
    public function data_token($key)
    {   
        if(!empty($key)){            
            $token = $this->input->get_request_header('Authorization', TRUE);
            $decode_token = JWT::decode($token, $this->secretkey, array('HS256'));
            
            return $decode_token->{$key};
        }
    }


    public function notification_push($fcm_token, $type, $message)
    {
        $this->check_token();

        if($this->data_token('user_type') == 1){ // 1 = profesional

            // DISINI SENDER ADALAH PROFESIONAL, DAN RECEIVER ADALAH COMPANY
            $this->load->model('company_mod');
            $user_receiver = $this->company_mod->get_fcm_token($fcm_token);          
        }else{

            $this->load->model('user_mod');
            $user_receiver = $this->user_mod->get_fcm_token($fcm_token);          
        }

        $url = 'https://fcm.googleapis.com/fcm/send';

        $data_post = array(
          'title' => 'Exigo Notification',
          'type' => $type,
          'message' => $message,
        );
        $fields = array(
             'registration_ids' => $user_receiver->fcm_token,
             'data' => json_encode($data_post)
            );
        
        // $headers = array(
        //     'Authorization:key = AIzaSyCKUE8C6i6gvwnhq6AuG21Qfwywcuea-Bo',
        //     'Content-Type: application/json'
        //     );
        $headers = array(
            'Authorization:key = AIzaSyCK4Xt3P3nb9rUobv4R8lnuSl7qiVyIAdc',
            'Content-Type: application/json'
            );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);           
        if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
      
        // generate response         
        if($result){ 
            $this->response($this->success('Success. Notification has send.',$result),200);
        }
        else{ 
            $this->response($this->failed('Failed. Notification is not send.'),404);  
        }
    }
}